<?php
/**
 * @author Endora
 * @copyright Copyright (c) Endora (https://endora.software)
 * @package ExpertSender_Ecdp
 */

namespace ExpertSender\Ecdp\Cron;

use ExpertSender\Ecdp\Api\Data\TaskInterface;
use ExpertSender\Ecdp\Api\TaskRepositoryInterface;
use ExpertSender\Ecdp\Model\Config;
use ExpertSender\Ecdp\Service\OrderService;
use Magento\Framework\Api\SearchCriteriaBuilderFactory;
use Psr\Log\LoggerInterface;

class OrderSync
{
    /**
     * @var \ExpertSender\Ecdp\Api\TaskRepositoryInterface
     */
    protected $taskRepository;

    /**
     * @var \Magento\Framework\Api\SearchCriteriaBuilderFactory
     */
    protected $searchCriteriaBuilderFactory;

    /**
     * @var \ExpertSender\Ecdp\Service\OrderService
     */
    protected $orderService;

    /**
     * @var \ExpertSender\Ecdp\Model\Config
     */
    protected $config;

    /**
     * @var \Psr\Log\LoggerInterface
     */
    protected $logger;

    /**
     * @param \ExpertSender\Ecdp\Api\TaskRepositoryInterface $taskRepository
     * @param \Magento\Framework\Api\SearchCriteriaBuilderFactory $searchCriteriaBuilderFactory
     * @param \ExpertSender\Ecdp\Service\OrderService $orderService
     * @param \ExpertSender\Ecdp\Model\Config $config
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
