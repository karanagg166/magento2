<?php
declare(strict_types=1);

namespace Karan\RatingFilter\Plugin\Review;

use Karan\RatingFilter\Model\RatingSync;
use Magento\Framework\Model\AbstractModel;
use Magento\Review\Model\ResourceModel\Review as ReviewResource;
use Psr\Log\LoggerInterface;

/**
 * Keeps the rating filter up to date. Every path that changes a review - storefront submission,
 * admin save, mass approve/reject, delete - ends up calling Review::aggregate(), which is where
 * the review summary of the product is written, so one plugin here covers all of them.
 */
class SyncRatingFilterOnAggregate
{
    /**
     * @var RatingSync
     */
    private $ratingSync;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @param RatingSync $ratingSync
     * @param LoggerInterface $logger
     */
    public function __construct(RatingSync $ratingSync, LoggerInterface $logger)
    {
        $this->ratingSync = $ratingSync;
        $this->logger = $logger;
    }

    /**
     * Sync rating filter attribute after review aggregate
     *
     * @param ReviewResource $subject
     * @param mixed $result
     * @param AbstractModel $object
     * @return mixed
     */
    public function afterAggregate(ReviewResource $subject, $result, $object)
    {
        $productId = (int)$object->getEntityPkValue();
        if ($productId) {
            try {
                $this->ratingSync->sync([$productId]);
            } catch (\Throwable $e) {
                // Never break a review save because of the filter - the CLI command can repair it.
                $this->logger->error(
                    sprintf('Karan_RatingFilter: could not sync product %d: %s', $productId, $e->getMessage()),
                    ['exception' => $e]
                );
            }
        }

        return $result;
    }
}
