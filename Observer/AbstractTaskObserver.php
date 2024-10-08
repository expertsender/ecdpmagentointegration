<?php
/**
 * @author Endora
 * @copyright Copyright (c) Endora (https://endora.software)
 * @package ExpertSender_Ecdp
 */

namespace ExpertSender\Ecdp\Observer;

use ExpertSender\Ecdp\Service\TaskService;
use Magento\Framework\Event\ObserverInterface;
use Psr\Log\LoggerInterface;

abstract class AbstractTaskObserver implements ObserverInterface
{
    /**
     * @var \ExpertSender\Ecdp\Service\TaskService
     */
    protected $taskService;

    /**
     * @var \Psr\Log\LoggerInterface
     */
    protected $logger;

    /**
     * @param \ExpertSender\Ecdp\Service\TaskService $taskService
     * @param \Psr\Log\LoggerInterface $logger
     */
    public function __construct(TaskService $taskService, LoggerInterface $logger)
    {
        $this->taskService = $taskService;
        $this->logger = $logger;
    }
}
