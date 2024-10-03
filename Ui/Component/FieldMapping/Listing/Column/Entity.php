<?php
/**
 * @author Endora
 * @copyright Copyright (c) Endora (https://endora.software)
 * @package Endora_ExpertSenderCdp
 */

namespace Endora\ExpertSenderCdp\Ui\Component\FieldMapping\Listing\Column;

use Endora\ExpertSenderCdp\Api\Data\FieldMappingInterface;
use Endora\ExpertSenderCdp\Model\FieldMapping\Entity as FieldMappingEntity;
use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Framework\View\Element\UiComponentFactory;
use Magento\Ui\Component\Listing\Columns\Column;

class Entity extends Column
{
    /**
     * @var \Endora\ExpertSenderCdp\Model\FieldMapping\Entity
     */
    protected $fieldMappingEntity;

    /**
     * @param \Endora\ExpertSenderCdp\Model\FieldMapping\Entity $fieldMappingEntity
     * @param \Magento\Framework\View\Element\UiComponentFactory $uiComponentFactory
     * @param array $components
     * @param array $data
     */
    public function __construct(
        FieldMappingEntity $fieldMappingEntity,
        ContextInterface $context,
        UiComponentFactory $uiComponentFactory,
        array $components,
        array $data
    ) {
        parent::__construct($context, $uiComponentFactory, $components, $data);
        $this->fieldMappingEntity = $fieldMappingEntity;
    }

    /**
     * {@inheritdoc}
     */
    public function prepareDataSource(array $dataSource)
    {
        if (isset($dataSource['data']['items'])) {
            foreach ($dataSource['data']['items'] as &$item) {
                if ($item[FieldMappingInterface::ENTITY]) {
                    $item[FieldMappingInterface::ENTITY] = $this->fieldMappingEntity->getLabel(
                        (int) $item[FieldMappingInterface::ENTITY]
                    );
                }
            }
        }

        return $dataSource;
    }
}