<?php
/**
 * @author Endora
 * @copyright Copyright (c) Endora (https://endora.software)
 * @package Endora_ExpertSenderCdp
 */

namespace Endora\ExpertSenderCdp\Cron\Model\Config;

use Magento\Framework\App\Cache\TypeListInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\App\Config\Value;
use Magento\Framework\App\Config\ValueFactory;
use Magento\Framework\Data\Collection\AbstractDb;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Model\Context;
use Magento\Framework\Model\ResourceModel\AbstractResource;
use Magento\Framework\Registry;

class OrderSyncInterval extends Value
{
    protected const CRON_EXPR_PATH =
        'crontab/endora_expertsender/jobs/endora_expertsender_order_sync/schedule/cron_expr';
    protected const CRON_MODEL_PATH = 'crontab/endora_expertsender/jobs/endora_expertsender_order_sync/run/model';
    protected const ORDER_SYNC_INTERVAL_PATH = 'groups/cron/fields/order_sync_interval/value';

    protected $configValueFactory;

    protected $runModelPath = '';

    public function __construct(
        Context $context,
        Registry $registry,
        ScopeConfigInterface $config,
        ValueFactory $configValueFactory,
        TypeListInterface $cacheTypeList,
        AbstractResource $resource = null,
        AbstractDb $resourceCollection = null,
        $runModelPath = '',
        array $data = []
    ) {
        parent::__construct(
            $context,
            $registry,
            $config,
            $cacheTypeList,
            $resource,
            $resourceCollection,
            $data
        );

        $this->runModelPath = $runModelPath;
        $this->configValueFactory = $configValueFactory;
    }

    public function afterSave()
    {
        $cronExprString = '';
        $interval = $this->getData(self::ORDER_SYNC_INTERVAL_PATH);

        if ($interval) {
            $cronExprString = '*/' . $interval . ' * * * *';
        }

        try {
            $this->configValueFactory->create()->load(
                self::CRON_EXPR_PATH,
                'path'
            )->setValue(
                $cronExprString
            )->setPath(
                self::CRON_EXPR_PATH
            )->save();

            $this->configValueFactory->create()->load(
                self::CRON_MODEL_PATH,
                'path'
            )->setValue(
                $this->runModelPath
            )->setPath(
                self::CRON_MODEL_PATH
            )->save();
        } catch (\Exception $ex) {
            throw new LocalizedException(__('Something went wrong while saving Cron schedule.'));
        }

        return parent::afterSave();
    }
}
