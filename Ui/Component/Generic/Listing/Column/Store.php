<?php
/**
 * @author Endora
 * @copyright Copyright (c) Endora (https://endora.software)
 * @package Endora_ExpertSenderCdp
 */

namespace Endora\ExpertSenderCdp\Ui\Component\Generic\Listing\Column;

use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Framework\View\Element\UiComponentFactory;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Ui\Component\Listing\Columns\Column;

class Store extends Column
{
    /**
     * @var array
     */
    protected $options;

    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $storeManager;

    /**
     * {@inheritdoc}
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     */
    public function __construct(
        StoreManagerInterface $storeManager,
        ContextInterface $context,
        UiComponentFactory $uiComponentFactory,
        array $components = [],
        array $data = []
    ) {
        parent::__construct($context, $uiComponentFactory, $components, $data);
        $this->storeManager = $storeManager;
        $this->prepareOptions();
    }

    /**
     * {@inheritdoc}
     */
    public function prepareDataSource(array $dataSource)
    {
        if (isset($dataSource['data']['items'])) {
            foreach ($dataSource['data']['items'] as &$item) {
                if (isset($this->options[$item['store']])) {
                    $item['store_id'] = $item['store'];
                    $item['store'] = $this->options[$item['store']];
                }
            }
        }

        return $dataSource;
    }

    /**
     * @return void
     */
    protected function prepareOptions()
    {
        $options = [];

        foreach ($this->storeManager->getStores() as $store) {
            $options[(int) $store->getId()] = $store->getName();
        }

        $this->options = $options;
    }
}
