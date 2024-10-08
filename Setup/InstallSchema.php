<?php
/**
 * @author Endora
 * @copyright Copyright (c) Endora (https://endora.software)
 * @package ExpertSender_Ecdp
 */

namespace ExpertSender\Ecdp\Setup;

use ExpertSender\Ecdp\Api\Data\ConsentFormElementInterface;
use ExpertSender\Ecdp\Api\Data\ConsentInterface;
use ExpertSender\Ecdp\Api\Data\FieldMappingInterface;
use ExpertSender\Ecdp\Api\Data\OrderStatusMappingInterface;
use ExpertSender\Ecdp\Api\Data\TaskInterface;
use ExpertSender\Ecdp\Model\ResourceModel\Consent;
use ExpertSender\Ecdp\Model\ResourceModel\Consent\FormElement;
use ExpertSender\Ecdp\Model\ResourceModel\FieldMapping;
use ExpertSender\Ecdp\Model\ResourceModel\OrderStatusMapping;
use ExpertSender\Ecdp\Model\ResourceModel\Task;
use Magento\Framework\DB\Adapter\AdapterInterface;
use Magento\Framework\DB\Ddl\Table;
use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;

class InstallSchema implements InstallSchemaInterface
{
    /**
     * {@inheritdoc}
     */
    public function install(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $installer = $setup;
        $installer->startSetup();
        $connection = $installer->getConnection();

        if (!$installer->tableExists(FieldMapping::TABLE_NAME)) {
            $table = $connection->newTable(
                $installer->getTable(FieldMapping::TABLE_NAME)
            )->addColumn(
                FieldMappingInterface::ID,
                Table::TYPE_INTEGER,
                null,
                [
                    'identity' => true,
                    'nullable' => false,
                    'primary' => true,
                    'unsigned' => true
                ]
            )->addColumn(
                FieldMappingInterface::MAGENTO_FIELD,
                Table::TYPE_TEXT,
                255,
                ['nullable' => false],
                'Magento field name'
            )->addColumn(
                FieldMappingInterface::ECDP_FIELD,
                Table::TYPE_TEXT,
                255,
                ['nullable' => false],
                'ECDP field name'
            )->addColumn(
                FieldMappingInterface::ENTITY,
                Table::TYPE_SMALLINT,
                null,
                ['nullable' => false, 'unsigned' => true],
                'Entity class'
            )->setComment('ExpertSender custom mappings');

            $connection->createTable($table);
        }

        if (!$installer->tableExists(Task::TABLE_NAME)) {
            $table = $connection->newTable(
                $installer->getTable(Task::TABLE_NAME)
            )->addColumn(
                TaskInterface::ID,
                Table::TYPE_INTEGER,
                null,
                [
                    'identity' => true,
                    'nullable' => false,
                    'primary' => true,
                    'unsigned' => true
                ]
            )->addColumn(
                TaskInterface::TASK,
                Table::TYPE_TEXT,
                255,
                ['nullable' => false],
                'Task name'
            )->addColumn(
                TaskInterface::OBJECT_ID,
                Table::TYPE_INTEGER,
                null,
                [
                    'nullable' => true,
                    'unsigned' => true
                ],
                'Entity ID'
            )->addColumn(
                TaskInterface::ATTEMPTS,
                Table::TYPE_SMALLINT,
                null,
                ['nullable' => false, 'unsigned' => true],
                'Attempts'
            )->addColumn(
                TaskInterface::CUSTOM_DATA,
                Table::TYPE_TEXT,
                null,
                ['nullable' => true],
                'Object data'
            )->setComment('ExpertSender tasks');

            $connection->createTable($table);
        }

        if (!$installer->tableExists(Consent::TABLE_NAME)) {
            $table = $connection->newTable(
                $installer->getTable(Consent::TABLE_NAME)
            )->addColumn(
                ConsentInterface::ID,
                Table::TYPE_INTEGER,
                null,
                [
                    'identity' => true,
                    'nullable' => false,
                    'primary' => true,
                    'unsigned' => true
                ]
            )->addColumn(
                ConsentInterface::NAME,
                Table::TYPE_TEXT,
                255,
                ['nullable' => false],
                'Name'
            )->addColumn(
                ConsentInterface::ECDP_ID,
                Table::TYPE_SMALLINT,
                null,
                ['nullable' => false, 'unsigned' => true],
                'ECDP Consent ID'
            );

            $connection->createTable($table);

            $connection->addIndex(
                $installer->getTable(Consent::TABLE_NAME),
                $installer->getIdxName(
                    $installer->getTable(Consent::TABLE_NAME),
                    [ConsentInterface::NAME],
                    AdapterInterface::INDEX_TYPE_UNIQUE
                ),
                [ConsentInterface::NAME],
                AdapterInterface::INDEX_TYPE_UNIQUE
            );

            $connection->addIndex(
                $installer->getTable(Consent::TABLE_NAME),
                $installer->getIdxName(
                    $installer->getTable(Consent::TABLE_NAME),
                    [ConsentInterface::ECDP_ID],
                    AdapterInterface::INDEX_TYPE_UNIQUE
                ),
                [ConsentInterface::ECDP_ID],
                AdapterInterface::INDEX_TYPE_UNIQUE
            );
        }

        if (!$installer->tableExists(FormElement::TABLE_NAME)) {
            $table = $connection->newTable(
                $installer->getTable(FormElement::TABLE_NAME)
            )->addColumn(
                ConsentFormElementInterface::ID,
                Table::TYPE_INTEGER,
                null,
                [
                    'identity' => true,
                    'nullable' => false,
                    'primary' => true,
                    'unsigned' => true
                ]
            )->addColumn(
                ConsentFormElementInterface::CONTENT,
                Table::TYPE_TEXT,
                255,
                ['nullable' => false],
                'Content'
            )->addColumn(
                ConsentFormElementInterface::FORM,
                Table::TYPE_TEXT,
                255,
                ['nullable' => false],
                'Form'
            )->addColumn(
                ConsentFormElementInterface::CONSENT_IDS,
                Table::TYPE_TEXT,
                null,
                ['nullable' => false],
                'ECDP Consent IDs'
            )->addColumn(
                ConsentFormElementInterface::ENABLED,
                Table::TYPE_BOOLEAN,
                null,
                ['nullable' => false],
                'Is enabled'
            )->addColumn(
                ConsentFormElementInterface::SORT_ORDER,
                Table::TYPE_SMALLINT,
                null,
                ['nullable' => true, 'unsigned' => true]
            );

            $connection->createTable($table);
        }

        if (!$installer->tableExists(OrderStatusMapping::TABLE_NAME)) {
            $table = $connection->newTable(
                $installer->getTable(OrderStatusMapping::TABLE_NAME)
            )->addColumn(
                OrderStatusMappingInterface::ID,
                Table::TYPE_INTEGER,
                null,
                [
                    'identity' => true,
                    'nullable' => false,
                    'primary' => true,
                    'unsigned' => true
                ]
            )->addColumn(
                OrderStatusMappingInterface::ECDP_ORDER_STATUS,
                Table::TYPE_TEXT,
                255,
                ['nullable' => false],
                'ECDP order status'
            )->addColumn(
                OrderStatusMappingInterface::MAGENTO_ORDER_STATUSES,
                Table::TYPE_TEXT,
                null,
                ['nullable' => false],
                'Magento order statuses'
            );

            $connection->createTable($table);

            $connection->addIndex(
                $installer->getTable(OrderStatusMapping::TABLE_NAME),
                $installer->getIdxName(
                    $installer->getTable(OrderStatusMapping::TABLE_NAME),
                    [OrderStatusMappingInterface::ECDP_ORDER_STATUS],
                    AdapterInterface::INDEX_TYPE_UNIQUE
                ),
                [OrderStatusMappingInterface::ECDP_ORDER_STATUS],
                AdapterInterface::INDEX_TYPE_UNIQUE
            );
        }

        $installer->endSetup();
    }
}
