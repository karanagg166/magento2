<?php
namespace Karan\LowStockNotification\Logger;

use Magento\Framework\Logger\Handler\Base;
use Monolog\Logger;

class Handler extends Base
{
    protected $loggerType = Logger::INFO;

    protected $fileName = '/var/log/low_stock_notification.log';
}
