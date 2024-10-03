<?php
/**
 * @author Endora
 * @copyright Copyright (c) Endora (https://endora.software)
 * @package Endora_ExpertSenderCdp
 */

namespace Endora\ExpertSenderCdp\Logger\Handler;

use Endora\ExpertSenderCdp\Model\Config;
use Magento\Framework\Filesystem\DriverInterface;
use Magento\Framework\Logger\Handler\Base;
use Monolog\Logger;

class ApiHandler extends Base
{
    /**
     * @var int
     */
    protected $loggerType = Logger::DEBUG;

    /**
     * @var string
     */
    protected $fileName = '/var/log/expertsender/api.log';

    /**
     * @var bool
     */
    protected $enabled;

    /**
     * @param \Endora\ExpertSenderCdp\Model\Config $config
     * @param \Magento\Framework\Filesystem\DriverInterface $filesystem
     * @param string $filePath
     * @param string $fileName
     */
    public function __construct(
        Config $config,
        DriverInterface $filesystem,
        $filePath = null,
        $fileName = null
    ) {
        parent::__construct(
            $filesystem,
            $filePath,
            $fileName
        );

        $this->enabled = $config->isApiLogEnabled();
    }

    /**
     * {@inheritdoc}
     */
    public function write(array $record): void
    {
        if (true === $this->enabled) {
            parent::write($record);
        }
    }
}
