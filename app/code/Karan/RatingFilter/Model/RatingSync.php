<?php
declare(strict_types=1);

namespace Karan\RatingFilter\Model;

use Karan\RatingFilter\Model\Product\Attribute\Source\Rating as RatingSource;
use Magento\Catalog\Model\Product\Action as ProductAction;
use Magento\Framework\App\ResourceConnection;
use Magento\Review\Model\ResourceModel\Review as ReviewResource;
use Magento\Review\Model\Review;

/**
 * Copies the aggregated review rating of a product into the "rating_filter" attribute.
 *
 * A product with an average of 4 stars is stored as "1,2,3,4" so the layered navigation option
 * "3 Stars & Up" simply matches every product whose value contains 3.
 */
class RatingSync
{
    /**
     * @var ResourceConnection
     */
    private $resource;

    /**
     * @var ProductAction
     */
    private $productAction;

    /**
     * @var ReviewResource
     */
    private $reviewResource;

    /**
     * @param ResourceConnection $resource
     * @param ProductAction $productAction
     * @param ReviewResource $reviewResource
     */
    public function __construct(
        ResourceConnection $resource,
        ProductAction $productAction,
        ReviewResource $reviewResource
    ) {
        $this->resource = $resource;
        $this->productAction = $productAction;
        $this->reviewResource = $reviewResource;
    }

    /**
     * Rebuild the attribute value of the given products, or of every reviewed product.
     *
     * @param int[]|null $productIds null means "all products that have review data"
     * @return int number of products updated
     */
    public function sync(?array $productIds = null): int
    {
        // Group products by store and by resulting value, so one query can update many products.
        $groups = [];
        foreach ($this->getSummaries($productIds) as $row) {
            $value = $this->buildValue((int)$row['reviews_count'], (int)$row['rating_summary']);
            $groups[(int)$row['store_id']][(string)$value][] = (int)$row['entity_pk_value'];
        }

        $updated = [];
        foreach ($groups as $storeId => $valueGroups) {
            foreach ($valueGroups as $value => $ids) {
                $this->productAction->updateAttributes(
                    $ids,
                    [RatingSource::ATTRIBUTE_CODE => $value === '' ? null : $value],
                    $storeId
                );
                $updated += array_flip($ids);
            }
        }

        return count($updated);
    }

    /**
     * The star buckets of a rating, e.g. "1,2,3,4" for a product rated 4 stars.
     *
     * @param int $reviewsCount
     * @param int $ratingPercent 0-100
     * @return string|null null when the product has no approved review
     */
    private function buildValue(int $reviewsCount, int $ratingPercent): ? string
    {
        if ($reviewsCount <= 0) {
            return null;
        }

        $stars = (int)round($ratingPercent / (100 / RatingSource::MAX_STARS));
        $stars = max(1, min(RatingSource::MAX_STARS, $stars));

        return implode(',', range(1, $stars));
    }

    /**
     * Review summaries of products, one row per store view.
     *
     * Rows are kept by Magento even when the last review is deleted (with reviews_count = 0),
     * which is what lets this method clear values that are no longer valid.
     *
     * @param int[]|null $productIds
     * @return array
     */
    private function getSummaries(? array $productIds): array
    {
        $connection = $this->resource->getConnection();
        $select = $connection->select()
            ->from(
                $this->resource->getTableName('review_entity_summary'),
                ['entity_pk_value', 'store_id', 'rating_summary', 'reviews_count']
            )
            ->where('entity_type = ? ', (int)$this->reviewResource->getEntityIdByCode(Review::ENTITY_PRODUCT_CODE));

        if ($productIds !== null) {
            if (!$productIds) {
                return [];
            }
            $select->where('entity_pk_value IN (?)', $productIds);
        }

        return $connection->fetchAll($select);
    }
}
