<?php
/**
 * @author Endora
 * @copyright Copyright (c) Endora (https://endora.software)
 * @package Endora_ExpertSenderCdp
 */

namespace Endora\ExpertSenderCdp\Observer;

use Endora\ExpertSenderCdp\Service\TaskService;
use Magento\Framework\Event\ObserverInterface;
use Psr\Log\LoggerInterface;

abstract class AbstractTaskObserver implements ObserverInterface
{
    /**
     * @var \Endora\ExpertSenderCdp\Service\TaskService
     */
    protected $taskService;

    /**
     * @var \Psr\Log\LoggerInterface
     */
    protected $logger;

    /**
     * @param \Endora\ExpertSenderCdp\Service\TaskService $taskService
     * @param \Psr\Log\LoggerInterface $logger
     */
    public function __construct(TaskService $taskService, LoggerInterface $logger)
    {
        $this->taskService = $taskService;
        $this->logger = $logger;
    }
}
