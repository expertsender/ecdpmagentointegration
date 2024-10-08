<?php
/**
 * @author Endora
 * @copyright Copyright (c) Endora (https://endora.software)
 * @package ExpertSender_Ecdp
 */

namespace ExpertSender\Ecdp\Setup;

use ExpertSender\Ecdp\Api\Data\ConsentInterface;
use ExpertSender\Ecdp\Api\Data\FieldMappingInterface;
use ExpertSender\Ecdp\Api\Data\OrderStatusMappingInterface;
use ExpertSender\Ecdp\Model\ResourceModel\Consent;
use ExpertSender\Ecdp\Model\ResourceModel\Consent\FormElement;
use ExpertSender\Ecdp\Model\ResourceModel\FieldMapping;
use ExpertSender\Ecdp\Model\ResourceModel\OrderStatusMapping;
use ExpertSender\Ecdp\Model\ResourceModel\Task;
use Magento\Framework\DB\Adapter\AdapterInterface;
use Magento\Framework\DB\Ddl\Table;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\UpgradeSchemaInterface;

class UpgradeSchema implements UpgradeSchemaInterface
{
    /**
     * {@inheritdoc}
     */
    public function upgrade(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $installer = $setup;
        $installer->startSetup();

        if (version_compare($context->getVersion(), '1.0.1', '<')) {
            $this->addStoreIdColumn($installer, FieldMapping::TABLE_NAME);
            $this->addStoreIdColumn($installer, FormElement::TABLE_NAME);
            $this->addStoreIdColumn($installer, Consent::TABLE_NAME);
            $this->addStoreIdColumn($installer, OrderStatusMapping::TABLE_NAME);
            $this->addStoreIdColumn($installer, Task::TABLE_NAME);
            $installer->getConnection()->dropColumn(
                FormElement::TABLE_NAME,
                'store_ids'
            );
            $this->updateOrderStatusMappingUniqueIndex($installer);
            $this->addConsentEcdpLabelColumn($installer);
        }

        if (version_compare($context->getVersion(), '1.0.2', '<')) {
            $installer->getConnection()->addIndex(
                $installer->getTable(FieldMapping::TABLE_NAME),
                $installer->getIdxName(
                    $installer->getTable(FieldMapping::TABLE_NAME),
                    [FieldMappingInterface::ECDP_FIELD, FieldMappingInterface::STORE, FieldMappingInterface::ENTITY],
                    AdapterInterface::INDEX_TYPE_UNIQUE
                ),
                [FieldMappingInterface::ECDP_FIELD, FieldMappingInterface::STORE, FieldMappingInterface::ENTITY],
                AdapterInterface::INDEX_TYPE_UNIQUE
            );
        }

        if (version_compare($context->getVersion(), '1.0.3', '<')) {
            $this->updateConsentUniqueIndex($installer);
        }

        $installer->endSetup();
    }

    /**
     * @param \Magento\Framework\Setup\SchemaSetupInterface $installer
     * @param string $table
     * @return void
     */
    private function addStoreIdColumn(SchemaSetupInterface $installer, string $table)
    {
        if ($installer->tableExists($table)) {
            $installer->getConnection()->truncateTable($table);
            $connection = $installer->getConnection();

            $connection->addColumn(
                $installer->getTable($table),
                'store',
                [
                    'type' => Table::TYPE_SMALLINT,
                    'unsigned' => true,
                    'nullable' => false,
                    'comment' => 'Store ID'
                ]
            );

            $connection->addForeignKey(
                $installer->getFkName(
                    $table,
                    'store',
                    'store',
                    'store_id'
                ),
                $table,
                'store',
                'store',
                'store_id'
            );
        }
    }

    /**
     * @param \Magento\Framework\Setup\SchemaSetupInterface $installer
     * @return void
     */
    private function updateOrderStatusMappingUniqueIndex(SchemaSetupInterface $installer)
    {
        $installer->getConnection()->dropIndex(
            OrderStatusMapping::TABLE_NAME,
            $installer->getIdxName(
                $installer->getTable(OrderStatusMapping::TABLE_NAME),
                [OrderStatusMappingInterface::ECDP_ORDER_STATUS],
                AdapterInterface::INDEX_TYPE_UNIQUE
            )
        );

        $installer->getConnection()->addIndex(
            $installer->getTable(OrderStatusMapping::TABLE_NAME),
            $installer->getIdxName(
                $installer->getTable(OrderStatusMapping::TABLE_NAME),
                [OrderStatusMappingInterface::ECDP_ORDER_STATUS, OrderStatusMappingInterface::STORE],
                AdapterInterface::INDEX_TYPE_UNIQUE
            ),
            [OrderStatusMappingInterface::ECDP_ORDER_STATUS, OrderStatusMappingInterface::STORE],
            AdapterInterface::INDEX_TYPE_UNIQUE
        );
    }

    /**
     * @param \Magento\Framework\Setup\SchemaSetupInterface $installer
     * @return void
     */
    private function addConsentEcdpLabelColumn(SchemaSetupInterface $installer)
    {
        if ($installer->tableExists(Consent::TABLE_NAME)) {
            $connection = $installer->getConnection();

            $connection->addColumn(
                $installer->getTable(Consent::TABLE_NAME),
                'ecdp_label',
                [
                    'type' => Table::TYPE_TEXT,
                    'nullable' => false,
                    'comment' => 'ECDP Label'
                ]
            );
        }
    }

    /**
     * @param \Magento\Framework\Setup\SchemaSetupInterface $installer
     * @return void
     */
    private function updateConsentUniqueIndex(SchemaSetupInterface $installer)
    {
        $installer->getConnection()->dropIndex(
            Consent::TABLE_NAME,
            $installer->getIdxName(
                $installer->getTable(Consent::TABLE_NAME),
                [ConsentInterface::ECDP_ID],
                AdapterInterface::INDEX_TYPE_UNIQUE
            )
        );

        $installer->getConnection()->addIndex(
            $installer->getTable(Consent::TABLE_NAME),
            $installer->getIdxName(
                $installer->getTable(Consent::TABLE_NAME),
                [ConsentInterface::ECDP_ID, ConsentInterface::STORE],
                AdapterInterface::INDEX_TYPE_UNIQUE
            ),
            [ConsentInterface::ECDP_ID, ConsentInterface::STORE],
            AdapterInterface::INDEX_TYPE_UNIQUE
        );
    }
}
