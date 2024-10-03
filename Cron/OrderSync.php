<?php
/**
 * @author Endora
 * @copyright Copyright (c) Endora (https://endora.software)
 * @package Endora_ExpertSenderCdp
 */

namespace Endora\ExpertSenderCdp\Cron;

use Endora\ExpertSenderCdp\Api\Data\TaskInterface;
use Endora\ExpertSenderCdp\Api\TaskRepositoryInterface;
use Endora\ExpertSenderCdp\Model\Config;
use Endora\ExpertSenderCdp\Service\OrderService;
use Magento\Framework\Api\SearchCriteriaBuilderFactory;
use Psr\Log\LoggerInterface;

class OrderSync
{
    /**
     * @var \Endora\ExpertSenderCdp\Api\TaskRepositoryInterface
     */
    protected $taskRepository;

    /**
     * @var \Magento\Framework\Api\SearchCriteriaBuilderFactory
     */
    protected $searchCriteriaBuilderFactory;

    /**
     * @var \Endora\ExpertSenderCdp\Service\OrderService
     */
    protected $orderService;

    /**
     * @var \Endora\ExpertSenderCdp\Model\Config
     */
    protected $config;

    /**
     * @var \Psr\Log\LoggerInterface
     */
    protected $logger;

    /**
     * @param \Endora\ExpertSenderCdp\Api\TaskRepositoryInterface $taskRepository
     * @param \Magento\Framework\Api\SearchCriteriaBuilderFactory $searchCriteriaBuilderFactory
     * @param \Endora\ExpertSenderCdp\Service\OrderService $orderService
     * @param \Endora\ExpertSenderCdp\Model\Config $config
     * @param \Psr\Log\LoggerInterface $logger
     */
    public function __construct(
        TaskRepositoryInterface $taskRepository,
        SearchCriteriaBuilderFactory $searchCriteriaBuilderFactory,
        OrderService $orderService,
        Config $config,
        LoggerInterface $logger
    ) {
        $this->taskRepository = $taskRepository;
        $this->searchCriteriaBuilderFactory = $searchCriteriaBuilderFactory;
        $this->orderService = $orderService;
        $this->config = $config;
        $this->logger = $logger;
    }

    public function execute()
    {
        $searchCriteria = $this->searchCriteriaBuilderFactory->create()
            ->addFilter(
                'task',
                [
                    TaskInterface::TASK_ORDER_ADD,
                    TaskInterface::TASK_ORDER_UPDATE,
                    TaskInterface::TASK_ORDER_STATUS_UPDATE
                ],
                'in'
            )->create();

        $tasks = $this->taskRepository->getList($searchCriteria);

        foreach ($tasks->getItems() as $task) {
            try {
                if (TaskInterface::TASK_ORDER_ADD === $task->getTask()) {
                    $this->orderService->sendNewOrder($task->getObjectId());
                } elseif (TaskInterface::TASK_ORDER_UPDATE === $task->getTask()) {
                    $this->orderService->sendOrderUpdate($task->getObjectId());
                } else {
                    $this->orderService->sendOrderStatusUpdate($task->getObjectId());
                }

                $this->taskRepository->delete($task);
            } catch (\Exception $ex) {
                $this->logger->error($ex->getMessage(), ['task' => $task]);
                $attempts = $task->getAttempts() + 1;

                if ($attempts >= $this->config->getOrderSyncMaxAttempts()) {
                    $this->taskRepository->delete($task);
                } else {
                    $task->setAttempts($attempts);
                    $this->taskRepository->save($task);
                }
            }
        }
    }
}
