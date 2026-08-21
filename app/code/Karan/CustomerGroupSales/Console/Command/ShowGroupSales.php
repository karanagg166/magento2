<?php
/**
 * Copyright © Karan. All rights reserved.
 */
declare(strict_types=1);

namespace Karan\CustomerGroupSales\Console\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Helper\Table;
use Karan\CustomerGroupSales\Model\ResourceModel\GroupSales\CollectionFactory as GroupSalesCollectionFactory;
use Magento\Customer\Api\GroupRepositoryInterface;

class ShowGroupSales extends Command
{
    /**
     * @var GroupSalesCollectionFactory
     */
    private GroupSalesCollectionFactory $collectionFactory;

    /**
     * @var GroupRepositoryInterface
     */
    private GroupRepositoryInterface $groupRepository;

    /**
     * @param GroupSalesCollectionFactory $collectionFactory
     * @param GroupRepositoryInterface $groupRepository
     * @param string|null $name
     */
    public function __construct(
        GroupSalesCollectionFactory $collectionFactory,
        GroupRepositoryInterface $groupRepository,
        ?string $name = null
    ) {
        parent::__construct($name);
        $this->collectionFactory = $collectionFactory;
        $this->groupRepository = $groupRepository;
    }

    /**
     * Configure command
     *
     * @return void
     */
    protected function configure(): void
    {
        $this->setName('group:sales:show')
            ->setDescription('Display stored customer group total sales data.');
        parent::configure();
    }

    /**
     * Execute command
     *
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $collection = $this->collectionFactory->create();

        if ($collection->getSize() === 0) {
            $output->writeln('<comment>No customer group sales data found in database.</comment>');
            return Command::SUCCESS;
        }

        $table = new Table($output);
        $table->setHeaders(['Entity ID', 'Group ID', 'Group Code', 'Order Count', 'Total Sales', 'Updated At']);

        foreach ($collection as $item) {
            $groupId = (int) $item->getCustomerGroupId();
            $groupCode = 'Unknown';

            try {
                $group = $this->groupRepository->getById($groupId);
                $groupCode = $group->getCode();
            } catch (\Exception $e) {
                $groupCode = 'Unknown';
            }

            $table->addRow([
                $item->getId(),
                $groupId,
                $groupCode,
                $item->getOrderCount(),
                sprintf('$%.2f', (float) $item->getTotalSales()),
                $item->getUpdatedAt()
            ]);
        }

        $table->render();
        return Command::SUCCESS;
    }
}
