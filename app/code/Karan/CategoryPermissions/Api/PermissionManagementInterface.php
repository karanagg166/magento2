<?php

declare(strict_types=1);

namespace Karan\CategoryPermissions\Api;

interface PermissionManagementInterface
{
    public function savePermissions(
        int $categoryId,
        array $permissions
    ): void;
     public function canBrowse(int $categoryId): bool;

    public function canViewPrice(int $categoryId): bool;

    public function canAddToCart(int $categoryId): bool;
}