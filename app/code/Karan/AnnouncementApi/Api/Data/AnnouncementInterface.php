<?php
/**
 * Copyright © Karan. All rights reserved.
 */
declare(strict_types=1);

namespace Karan\AnnouncementApi\Api\Data;

interface AnnouncementInterface
{
    public const ENTITY_ID = 'entity_id';
    public const TITLE = 'title';
    public const CONTENT = 'content';
    public const AUTHOR = 'author';
    public const CREATED_AT = 'created_at';

    /**
     * Get Entity ID
     *
     * @return int|null
     */
    public function getEntityId();

    /**
     * Set Entity ID
     *
     * @param int $entityId
     * @return $this
     */
    public function setEntityId($entityId);

    /**
     * Get Title
     *
     * @return string|null
     */
    public function getTitle();

    /**
     * Set Title
     *
     * @param string $title
     * @return $this
     */
    public function setTitle($title);

    /**
     * Get Content
     *
     * @return string|null
     */
    public function getContent();

    /**
     * Set Content
     *
     * @param string $content
     * @return $this
     */
    public function setContent($content);

    /**
     * Get Author
     *
     * @return string|null
     */
    public function getAuthor();

    /**
     * Set Author
     *
     * @param string $author
     * @return $this
     */
    public function setAuthor($author);

    /**
     * Get Created At
     *
     * @return string|null
     */
    public function getCreatedAt();

    /**
     * Set Created At
     *
     * @param string $createdAt
     * @return $this
     */
    public function setCreatedAt($createdAt);
}
