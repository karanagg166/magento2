<?php
declare(strict_types=1);

namespace Karan\RatingFilter\Setup\Patch\Data;

use Karan\RatingFilter\Model\Product\Attribute\Source\Rating as RatingSource;
use Magento\Catalog\Model\Product;
use Magento\Eav\Model\Entity\Attribute\Backend\ArrayBackend;
use Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface;
use Magento\Eav\Setup\EavSetupFactory;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\Patch\DataPatchInterface;
use Magento\Framework\Setup\Patch\PatchRevertableInterface;

/**
 * Creates the "rating_filter" product attribute the rating layered navigation filter is built on.
 *
 * Values are filled in automatically from the review data, see Karan\RatingFilter\Model\RatingSync.
 */
class AddRatingFilterAttribute implements DataPatchInterface, PatchRevertableInterface
{
    /**
     * @var ModuleDataSetupInterface
     */
    private $moduleDataSetup;

    /**
     * @var EavSetupFactory
     */
    private $eavSetupFactory;

    /**
     * @param ModuleDataSetupInterface $moduleDataSetup
     * @param EavSetupFactory $eavSetupFactory
     */
    public function __construct(ModuleDataSetupInterface $moduleDataSetup, EavSetupFactory $eavSetupFactory)
    {
        $this->moduleDataSetup = $moduleDataSetup;
        $this->eavSetupFactory = $eavSetupFactory;
    }

    /**
     * @inheritDoc
     */
    public function apply()
    {
        $this->eavSetupFactory->create(['setup' => $this->moduleDataSetup])->addAttribute(
            Product::ENTITY,
            RatingSource::ATTRIBUTE_CODE,
            [
                'type' => 'text',
                'label' => 'Customer Rating',
                'input' => 'multiselect',
                'backend' => ArrayBackend::class,
                'source' => RatingSource::class,
                'required' => false,
                'user_defined' => false,
                // Review summaries are per store view, so the value has to be too.
                'global' => ScopedAttributeInterface::SCOPE_STORE,
                'visible' => true,
                'visible_on_front' => false,
                'used_in_product_listing' => true,
                'searchable' => false,
                // 1 = "Filterable (with results)": star buckets without products are hidden.
                'filterable' => 1,
                'filterable_in_search' => true,
                'comparable' => false,
                'is_used_in_grid' => false,
                'sort_order' => 200,
            ]
        );

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function revert()
    {
        $this->eavSetupFactory->create(['setup' => $this->moduleDataSetup])
            ->removeAttribute(Product::ENTITY, RatingSource::ATTRIBUTE_CODE);
    }

    /**
     * @inheritDoc
     */
    public static function getDependencies()
    {
        return [];
    }

    /**
     * @inheritDoc
     */
    public function getAliases()
    {
        return [];
    }
}
