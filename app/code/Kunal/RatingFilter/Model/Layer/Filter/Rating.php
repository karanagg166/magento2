<?php
declare(strict_types=1);

namespace Kunal\RatingFilter\Model\Layer\Filter;

use Kunal\RatingFilter\Model\Product\Attribute\Source\Rating as RatingSource;
use Magento\Catalog\Model\Layer;
use Magento\Catalog\Model\Layer\Filter\Item\DataBuilder;
use Magento\Catalog\Model\Layer\Filter\ItemFactory;
use Magento\CatalogSearch\Model\Layer\Filter\Attribute;
use Magento\Framework\Escaper;
use Magento\Framework\Filter\StripTags;
use Magento\Store\Model\StoreManagerInterface;

/**
 * The "Customer Rating" filter of the layered navigation.
 *
 * Only the display is custom: options are listed from 5 stars down to 1 star and up, and each one
 * is drawn with star icons. Applying the filter is left to the standard attribute filter, so the
 * search engine keeps doing the filtering and the counting.
 */
class Rating extends Attribute
{
    /**
     * Query parameter used on the storefront, e.g. ?rating=4
     */
    public const REQUEST_VAR = 'rating';

    /**
     * @var Escaper
     */
    private $escaper;

    /**
     * @param ItemFactory $filterItemFactory
     * @param StoreManagerInterface $storeManager
     * @param Layer $layer
     * @param DataBuilder $itemDataBuilder
     * @param StripTags $tagFilter
     * @param Escaper $escaper
     * @param array $data
     */
    public function __construct(
        ItemFactory $filterItemFactory,
        StoreManagerInterface $storeManager,
        Layer $layer,
        DataBuilder $itemDataBuilder,
        StripTags $tagFilter,
        Escaper $escaper,
        array $data = []
    ) {
        parent::__construct($filterItemFactory, $storeManager, $layer, $itemDataBuilder, $tagFilter, $data);
        $this->escaper = $escaper;
        $this->_requestVar = self::REQUEST_VAR;
    }

    /**
     * Build the star options, highest rating first.
     *
     * @return array
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    protected function _getItemsData()
    {
        $attribute = $this->getAttributeModel();
        // Product counts per option, calculated by the search engine.
        $facetedData = $this->getLayer()->getProductCollection()->getFacetedData($attribute->getAttributeCode());

        foreach ($attribute->getSource()->getAllOptions() as $option) {
            $stars = (int)$option['value'];
            $count = isset($facetedData[$stars]['count']) ? (int)$facetedData[$stars]['count'] : 0;

            // Attribute is set to "Filterable (with results)", so hide the empty buckets.
            if ($count === 0) {
                continue;
            }

            $this->itemDataBuilder->addItemData($this->renderStars($stars, (string)$option['label']), $stars, $count);
        }

        return $this->itemDataBuilder->build();
    }

    /**
     * Star icons followed by the option label. The filter template prints labels unescaped,
     * so everything that is not our own markup is escaped here.
     *
     * @param int $stars
     * @param string $label
     * @return string
     */
    private function renderStars(int $stars, string $label): string
    {
        $empty = RatingSource::MAX_STARS - $stars;

        return '<span class="rating-filter-stars" title="' . $this->escaper->escapeHtmlAttr($label) . '">'
            . '<span class="rating-filter-stars-on">' . str_repeat('★', $stars) . '</span>'
            . ($empty > 0 ? '<span class="rating-filter-stars-off">' . str_repeat('☆', $empty) . '</span>' : '')
            . '</span>'
            . '<span class="rating-filter-label">' . $this->escaper->escapeHtml($label) . '</span>';
    }
}
