<?php
declare(strict_types=1);

namespace Kunal\RatingFilter\Plugin\Layer;

use Kunal\RatingFilter\Model\Layer\Filter\Rating as RatingFilter;
use Kunal\RatingFilter\Model\Layer\Filter\RatingFactory;
use Kunal\RatingFilter\Model\Product\Attribute\Source\Rating as RatingSource;
use Magento\Catalog\Model\Layer;
use Magento\Catalog\Model\Layer\FilterList;
use Magento\Framework\ObjectManager\ResetAfterRequestInterface;

/**
 * Replaces the generic attribute filter Magento builds for "rating_filter" with our star filter.
 *
 * Plugging into the filter list covers the category pages and the search results page at once,
 * because both use a virtual type of this class.
 */
class AddRatingFilter implements ResetAfterRequestInterface
{
    /**
     * @var RatingFactory
     */
    private $ratingFilterFactory;

    /**
     * @var RatingFilter|null
     */
    private $ratingFilter;

    /**
     * @param RatingFactory $ratingFilterFactory
     */
    public function __construct(RatingFactory $ratingFilterFactory)
    {
        $this->ratingFilterFactory = $ratingFilterFactory;
    }

    /**
     * @param FilterList $subject
     * @param array $result
     * @param Layer $layer
     * @return array
     */
    public function afterGetFilters(FilterList $subject, $result, Layer $layer)
    {
        if (!is_array($result)) {
            return $result;
        }

        foreach ($result as $key => $filter) {
            // The category filter has no attribute behind it, hence getData() instead of a getter.
            $attribute = $filter->getData('attribute_model');
            if ($attribute && $attribute->getAttributeCode() === RatingSource::ATTRIBUTE_CODE) {
                $result[$key] = $this->getRatingFilter($layer, $attribute);
            }
        }

        return $result;
    }

    /**
     * The navigation block applies the filters first and renders them later, both times through
     * this plugin, so the same instance has to be returned every time.
     *
     * @param Layer $layer
     * @param \Magento\Catalog\Model\ResourceModel\Eav\Attribute $attribute
     * @return RatingFilter
     */
    private function getRatingFilter(Layer $layer, $attribute): RatingFilter
    {
        if ($this->ratingFilter === null) {
            $this->ratingFilter = $this->ratingFilterFactory->create(
                [
                    'data' => ['attribute_model' => $attribute],
                    'layer' => $layer,
                ]
            );
        }

        return $this->ratingFilter;
    }

    /**
     * @inheritDoc
     */
    public function _resetState(): void
    {
        $this->ratingFilter = null;
    }
}
