<?php

namespace Kunal\Mod1;

// use Magento\Catalog\Api\Data\CategoryInterface;
use Kunal\Mod1\Api\Data\CategoryInterface;
use Kunal\Mod1\Logger\Logger;
class Test
{
    // private CategoryInterface $category;
    private array $params;
    private string $message;

    public function __construct(
        private CategoryInterface $category,
        private Logger $logger,
        array $params = [],
        string $message = ''
    ) {
        $this->params = $params;
        $this->message = $message;
    }

    public function displayParams():string
    {
        $output= "<h3>Array Values</h3>";
         $json = json_encode(
            $this->params,
            JSON_PRETTY_PRINT
        );

        $this->logger->info($json);
        foreach ($this->params as $key => $value) {
            $output.= $key . " : " . $value . "<br>";
        }

        $output.= "<br>";

        $output.= "<h3>Message</h3>";
        $output.= $this->message;
        return $output;
    }
}