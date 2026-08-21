<?php
/**
 * Copyright © Karan. All rights reserved.
 */
declare(strict_types=1);

namespace Karan\Popup\Ui\Component\Listing\Column;

use Magento\Framework\UrlInterface;
use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Framework\View\Element\UiComponentFactory;
use Magento\Ui\Component\Listing\Columns\Column;

class PopupActions extends Column
{
    /**
     * @var UrlInterface
     */
    private $urlBuilder;

    /**
     * @param ContextInterface $context
     * @param UiComponentFactory $uiComponentFactory
     * @param UrlInterface $urlBuilder
     * @param array $components
     * @param array $data
     */
    public function __construct(
        ContextInterface $context,
        UiComponentFactory $uiComponentFactory,
        UrlInterface $urlBuilder,
        array $components = [],
        array $data = []
    ) {
        $this->urlBuilder = $urlBuilder;
        parent::__construct($context, $uiComponentFactory, $components, $data);
    }

    /**
     * Prepare Data Source for grid actions column
     *
     * @param array $dataSource
     * @return array
     */
    public function prepareDataSource(array $dataSource): array
    {
        if (isset($dataSource['data']['items'])) {
            foreach ($dataSource['data']['items'] as & $item) {
                if (isset($item['popup_id'])) {
                    $item[$this->getData('name')] = [
                        'edit' => [
                            'href' => $this->urlBuilder->getUrl(
                                'karan_popup/index/edit',
                                ['popup_id' => $item['popup_id']]
                            ),
                            'label' => __('Edit')
                        ],
                        'delete' => [
                            'href' => $this->urlBuilder->getUrl(
                                'karan_popup/index/delete',
                                ['popup_id' => $item['popup_id']]
                            ),
                            'label' => __('Delete'),
                            'confirm' => [
                                'title' => __('Delete %1', $item['title']),
                                'message' => __('Are you sure you want to delete this popup?')
                            ]
                        ]
                    ];
                }
            }
        }

        return $dataSource;
    }
}
