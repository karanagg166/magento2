<?php
/**
 * Copyright © Karan. All rights reserved.
 */
declare(strict_types=1);

namespace Karan\AnnouncementApi\Model;

use Karan\AnnouncementApi\Api\Data\AnnouncementInterface;
use Magento\Framework\Model\AbstractModel;

class Announcement extends AbstractModel implements AnnouncementInterface
{
    /**
     * Cache tag
     */
    public const CACHE_TAG = 'karan_announcement';

    /**
     * @var string
     */
    protected $_cacheTag = 'karan_announcement';

    /**
     * @var string
     */
    protected $_eventPrefix = 'karan_announcement';

    /**
     * Initialize resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init(\Karan\AnnouncementApi\Model\ResourceModel\Announcement::class);
    }

    /**
     * @inheritdoc
     */
    public function getEntityId()
    {
        return $this->getData(self::ENTITY_ID);
    }

    /**
     * @inheritdoc
     */
    public function setEntityId($entityId)
    {
        return $this->setData(self::ENTITY_ID, $entityId);
    }

    /**
     * @inheritdoc
     */
    public function getTitle()
    {
        return $this->getData(self::TITLE);
    }

    /**
     * @inheritdoc
     */
    public function setTitle($title)
    {
        return $this->setData(self::TITLE, $title);
    }

    /**
     * @inheritdoc
     */
    public function getContent()
    {
        return $this->getData(self::CONTENT);
    }

    /**
     * @inheritdoc
     */
    public function setContent($content)
    {
        return $this->setData(self::CONTENT, $content);
    }

    /**
     * @inheritdoc
     */
    public function getAuthor()
    {
        return $this->getData(self::AUTHOR);
    }

    /**
     * @inheritdoc
     */
    public function setAuthor($author)
    {
        return $this->setData(self::AUTHOR, $author);
    }

    /**
     * @inheritdoc
     */
    public function getCreatedAt()
    {
        return $this->getData(self::CREATED_AT);
    }

    /**
     * @inheritdoc
     */
    public function setCreatedAt($createdAt)
    {
        return $this->setData(self::CREATED_AT, $createdAt);
    }
}
