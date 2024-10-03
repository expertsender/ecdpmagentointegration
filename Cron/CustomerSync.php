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
use Endora\ExpertSenderCdp\Service\CustomerService;
use Magento\Framework\Api\SearchCriteriaBuilderFactory;
use Psr\Log\LoggerInterface;

class CustomerSync
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
     * @var \Endora\ExpertSenderCdp\Service\CustomerService
     */
    protected $customerService;

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
     * @param \Endora\ExpertSenderCdp\Service\CustomerService $customerService
     * @param \Endora\ExpertSenderCdp\Model\Config $config
     * @param \Psr\Log\LoggerInterface $logger
     */
    public function __construct(
        TaskRepositoryInterface $taskRepository,
        SearchCriteriaBuilderFactory $searchCriteriaBuilderFactory,
        CustomerService $customerService,
        Config $config,
        LoggerInterface $logger
    ) {
        $this->taskRepository = $taskRepository;
        $this->searchCriteriaBuilderFactory = $searchCriteriaBuilderFactory;
        $this->customerService = $customerService;
        $this->config = $config;
        $this->logger = $logger;
    }

    /**
     * @return void
     */
    public function execute()
    {
        $searchCriteria = $this->searchCriteriaBuilderFactory->create()
            ->addFilter(
                'task',
                [
                    TaskInterface::TASK_CUSTOMER_ADD,
                    TaskInterface::TASK_CUSTOMER_UPDATE,
                    TaskInterface::TASK_GUEST_CUSTOMER_UPDATE
                ],
                'in'
            )->create();

        $tasks = $this->taskRepository->getList($searchCriteria);

        foreach ($tasks->getItems() as $task) {
            try {
                if (TaskInterface::TASK_CUSTOMER_ADD === $task->getTask()) {
                    $this->customerService->sendNewCustomer(
                        $task->getObjectId(),
                        $task->getCustomData()
                    );
                } elseif (TaskInterface::TASK_CUSTOMER_UPDATE === $task->getTask()) {
                    $this->customerService->sendCustomerUpdate(
                        $task->getObjectId(),
                        $task->getCustomData()
                    );
                } else {
                    $this->customerService->sendGuestCustomerUpdate(
                        $task->getCustomData(),
                        $task->getStore()
                    );
                }

                $this->taskRepository->delete($task);
            } catch (\Exception $ex) {
                $this->logger->error($ex->getMessage(), ['taskId' => $task->getId()]);
                $attempts = $task->getAttempts() + 1;

                if ($attempts >= $this->config->getCustomerSyncMaxAttempts()) {
                    $this->taskRepository->delete($task);
                } else {
                    $task->setAttempts($attempts);
                    $this->taskRepository->save($task);
                }
            }
        }
    }
}
