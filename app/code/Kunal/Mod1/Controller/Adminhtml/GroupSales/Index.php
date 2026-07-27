<?php

namespace Kunal\Mod1\Controller\Adminhtml\GroupSales;

use Kunal\Mod1\Model\CustomerGroupSalesProvider;
use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\Controller\Result\JsonFactory;

/**
 * Read back the totals accumulated by the custom_order_placement observer.
 *
 * Admin only — these are revenue figures, so they are behind the same ACL
 * resource as the module's configuration section.
 *
 * URL: <admin>/mod1/groupsales/index
 *      optional ?group_id=N to fetch a single group.
 */
class Index extends Action implements HttpGetActionInterface
{
    public const ADMIN_RESOURCE = 'Kunal_Mod1::config';

    private CustomerGroupSalesProvider $salesProvider;
    private JsonFactory $jsonFactory;

    public function __construct(
        Context $context,
        CustomerGroupSalesProvider $salesProvider,
        JsonFactory $jsonFactory
    ) {
        parent::__construct($context);
        $this->salesProvider = $salesProvider;
        $this->jsonFactory = $jsonFactory;
    }

    public function execute()
    {
        $result = $this->jsonFactory->create();
        $groupId = $this->getRequest()->getParam('group_id');

        if ($groupId !== null && $groupId !== '') {
            $row = $this->salesProvider->getByGroupId((int) $groupId);

            return $result->setData([
                'customer_group_id' => (int) $groupId,
                'total_sales'       => $this->salesProvider->getTotalByGroupId((int) $groupId),
                'row'               => $row ? $row->getData() : null,
            ]);
        }

        $rows = [];
        foreach ($this->salesProvider->getAll() as $model) {
            $rows[] = $model->getData();
        }

        return $result->setData([
            'grand_total' => $this->salesProvider->getGrandTotal(),
            'groups'      => $rows,
        ]);
    }
}
