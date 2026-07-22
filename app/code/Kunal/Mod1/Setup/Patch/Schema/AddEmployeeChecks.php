<?php

namespace Kunal\Mod1\Setup\Patch\Schema;

use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\Patch\SchemaPatchInterface;

class AddEmployeeChecks implements SchemaPatchInterface
{
    public function __construct(
        private ModuleDataSetupInterface $moduleDataSetup
    ) {
    }

    public function apply()
    {
        $connection = $this->moduleDataSetup->getConnection();

        $this->moduleDataSetup->startSetup();

        $table = $this->moduleDataSetup->getTable('employee_table');

        $connection->query("
            ALTER TABLE {$table}
            ADD CONSTRAINT chk_first_name
            CHECK (first_name REGEXP '^[A-Za-z]{1,30}$')
        ");

        $connection->query("
            ALTER TABLE {$table}
            ADD CONSTRAINT chk_last_name
            CHECK (last_name REGEXP '^[A-Za-z]{1,30}$')
        ");

        $connection->query("
            ALTER TABLE {$table}
            ADD CONSTRAINT chk_phone
            CHECK (phone_number REGEXP '^[0-9]{10}$')
        ");

        $connection->query("
            ALTER TABLE {$table}
            ADD CONSTRAINT chk_address
            CHECK (CHAR_LENGTH(address) >= 30)
        ");


        $this->moduleDataSetup->endSetup();

        return $this;
    }

    public static function getDependencies()
    {
        return [];
    }

    public function getAliases()
    {
        return [];
    }
}