<?php
/**
 * @author Endora
 * @copyright Copyright (c) Endora (https://endora.software)
 * @package ExpertSender_Ecdp
 */

namespace ExpertSender\Ecdp\Model\Consent\FormElement;

use ExpertSender\Ecdp\Model\ResourceModel\Consent\FormElement\CollectionFactory;
use Magento\Ui\DataProvider\AbstractDataProvider;

class DataProvider extends AbstractDataProvider
{
    /**
     * @var \ExpertSender\Ecdp\Model\ResourceModel\Consent\FormElement\Collection
     */
    protected $collection;

    /**
     * @var array
     */
    protected $loadedData = [];

    /**
     * @param \ExpertSender\Ecdp\Model\ResourceModel\Consent\FormElement\CollectionFactory $collectionFactory
     * @param string $name
     * @param string $primaryFieldName
     * @param string $requestFieldName
     * @param array $meta
     * @param array $data
     */
    public function __construct(
        CollectionFactory $collectionFactory,
        string $name,
        string $primaryFieldName,
        string $requestFieldName,
        array $meta = [],
        array $data = []
    ) {
        parent::__construct(
            $name,
            $primaryFieldName,
            $requestFieldName,
            $meta,
            $data
        );

        $this->collection = $collectionFactory->create();
    }

    /**
     * {@inheritdoc}
     */
    public function getData()
    {
        if (empty($this->loadedData)) {
            foreach ($this->collection->getItems() as $item) {
                $this->loadedData[$item->getId()] = $item->getData();
            }
        }

        return $this->loadedData;
    }
}
