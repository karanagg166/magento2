<?php
declare(strict_types=1);

namespace Kunal\RatingFilter\Model\Product\Attribute\Source;

use Magento\Eav\Model\Entity\Attribute\Source\AbstractSource;

/**
 * Options of the "rating_filter" product attribute: 5 stars down to 1 star and up.
 *
 * The attribute is a multiselect that holds every star bucket a product belongs to, so a product
 * rated 4 stars is stored as "1,2,3,4". Filtering by option 3 then means "3 stars & up" and the
 * search engine can do it with a plain term filter - no custom search query needed.
 */
class Rating extends AbstractSource
{
    public const ATTRIBUTE_CODE = 'rating_filter';

    public const MAX_STARS = 5;

    /**
     * @return array
     */
    public function getAllOptions()
    {
        if ($this->_options === null) {
            $this->_options = [];
            for ($stars = self::MAX_STARS; $stars >= 1; $stars--) {
                $this->_options[] = [
                    'value' => (string)$stars,
                    'label' => $this->getStarsLabel($stars),
                ];
            }
        }

        return $this->_options;
    }

    /**
     * Plain text label of a star bucket, for example "4 Stars & Up".
     *
     * @param int $stars
     * @return string
     */
    public function getStarsLabel(int $stars): string
    {
        if ($stars >= self::MAX_STARS) {
            return (string)__('%1 Stars', self::MAX_STARS);
        }

        return $stars === 1 ? (string)__('1 Star & Up') : (string)__('%1 Stars & Up', $stars);
    }
}
