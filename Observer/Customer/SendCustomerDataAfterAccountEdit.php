<?php
/**
 * @author Endora
 * @copyright Copyright (c) Endora (https://endora.software)
 * @package Endora_ExpertSenderCdp
 */

namespace Endora\ExpertSenderCdp\Observer\Customer;

use Endora\ExpertSenderCdp\Observer\AbstractTaskObserver;
use Endora\ExpertSenderCdp\Service\TaskService;
use Magento\Customer\Api\CustomerRepositoryInterface;
use Magento\Framework\Event\Observer;
use Magento\Framework\Exception\LocalizedException;
use Psr\Log\LoggerInterface;

class SendCustomerDataAfterAccountEdit extends AbstractTaskObserver
{
    /**
     * @var \Magento\Customer\Api\CustomerRepositoryInterface
     */
    protected $customerRepository;

    /**
     * @param \Magento\Customer\Api\CustomerRepositoryInterface $customerRepository
     * @param \Endora\ExpertSenderCdp\Service\TaskService $taskService
     * @param \Psr\Log\LoggerInterface $logger
     */
    public function __construct(
        CustomerRepositoryInterface $customerRepository,
        TaskService $taskService,
        LoggerInterface $logger
    ) {
        parent::__construct($taskService, $logger);
        $this->customerRepository = $customerRepository;
    }

    /**
     * {@inheritdoc}
     */
    public function execute(Observer $observer)
    {
        $customerEmail = $observer->getEvent()->getEmail();

        try {
            $customer = $this->customerRepository->get($customerEmail);
            $this->taskService->createCustomerUpdateTask($customer->getId());
        } catch (LocalizedException $ex) {
            $this->logger->error(
                'There was an error while creating customer synchronisation task: ' . $ex->getMessage(),
                ['customerId' => $customer->getId()]
            );
        }
    }
}
