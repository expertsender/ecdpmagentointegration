<?php
/**
 * @author Endora
 * @copyright Copyright (c) Endora (https://endora.software)
 * @package Endora_ExpertSenderCdp
 */

namespace Endora\ExpertSenderCdp\Service;

use Endora\ExpertSenderCdp\Api\ConsentRepositoryInterface;
use Endora\ExpertSenderCdp\Api\Data\TaskInterface;
use Endora\ExpertSenderCdp\Api\Data\TaskInterfaceFactory;
use Endora\ExpertSenderCdp\Api\TaskRepositoryInterface;
use Endora\ExpertSenderCdp\Model\Api\Dto\Customer\Consent;
use Endora\ExpertSenderCdp\Model\FormsConfig;
use Magento\Store\Model\StoreManagerInterface;
use Psr\Log\LoggerInterface;

class TaskService
{
    /**
     * @var \Endora\ExpertSenderCdp\Api\Data\TaskInterfaceFactory
     */
    protected $taskFactory;

    /**
     * @var \Endora\ExpertSenderCdp\Api\TaskRepositoryInterface
     */
    protected $taskRepository;

    /**
     * @var \Endora\ExpertSenderCdp\Api\ConsentRepositoryInterface
     */
    protected $consentRepository;

    /**
     * @var \Psr\Log\LoggerInterface
     */
    protected $logger;

    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $storeManager;

    /**
     * @param \Endora\ExpertSenderCdp\Api\Data\TaskInterfaceFactory $taskFactory
     * @param \Endora\ExpertSenderCdp\Api\TaskRepositoryInterface $taskRepository
     * @param \Endora\ExpertSenderCdp\Api\ConsentRepositoryInterface $consentRepository
     * @param \Psr\Log\LoggerInterface $logger
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     */
    public function __construct(
        TaskInterfaceFactory $taskFactory,
        TaskRepositoryInterface $taskRepository,
        ConsentRepositoryInterface $consentRepository,
        LoggerInterface $logger,
        StoreManagerInterface $storeManager
    ) {
        $this->taskFactory = $taskFactory;
        $this->taskRepository = $taskRepository;
        $this->consentRepository = $consentRepository;
        $this->logger = $logger;
        $this->storeManager = $storeManager;
    }

    /**
     * @param string $task
     * @param int|null $objectId
     * @param array|null $customData
     * @param int|null $storeId
     * @return bool
     */
    public function createTask(
        string $taskName,
        ?int $objectId,
        ?array $customData = null,
        ?int $storeId = null
    ) {
        if (null === $objectId && null === $customData) {
            return false;
        }

        $task = null;

        if (null !== $objectId) {
            $task = $this->taskRepository->getByTaskAndObjectId($taskName, $objectId);
        }

        if (null === $task) {
            if (null === $storeId) {
                $storeId = (int) $this->storeManager->getStore()->getId();
            }

            $task = $this->taskFactory->create()
                ->setTask($taskName)
                ->setObjectId($objectId)
                ->setStore($storeId);
        }

        $task->setAttempts(0)->setCustomData($customData);
        $this->taskRepository->save($task);

        return true;
    }

    /**
     * @param int $orderId
     * @param int|null $storeId
     * @return bool
     */
    public function createOrderAddTask(int $orderId, ?int $storeId = null)
    {
        return $this->createTask(TaskInterface::TASK_ORDER_ADD, $orderId, null, $storeId);
    }

    /**
     * @param int $orderId
     * @param int|null $storeId
     * @return bool
     */
    public function createOrderUpdateTask(int $orderId, ?int $storeId = null)
    {
        return $this->createTask(TaskInterface::TASK_ORDER_UPDATE, $orderId, null, $storeId);
    }

    /**
     * @param int $customerId
     * @param array|null $customData
     * @param int|null $storeId
     * @return bool
     */
    public function createCustomerAddTask(int $customerId, ?array $customData = null, ?int $storeId = null)
    {
        return $this->createTask(TaskInterface::TASK_CUSTOMER_ADD, $customerId, $customData, $storeId);
    }

    /**
     * @param int $customerId
     * @param array|null $customData
     * @param int|null $storeId
     * @return bool
     */
    public function createCustomerUpdateTask(int $customerId, ?array $customData = null, ?int $storeId = null)
    {
        return $this->createTask(TaskInterface::TASK_CUSTOMER_UPDATE, $customerId, $customData, $storeId);
    }

    /**
     * @param array $customData
     * @return bool
     */
    public function createGuestCustomerUpdateTask(array $customData)
    {
        return $this->createTask(TaskInterface::TASK_GUEST_CUSTOMER_UPDATE, null, $customData);
    }

    /**
     * @param int $customerId
     * @param int|null $storeId
     * @return bool
     */
    public function createOrderStatusUpdateTask(int $orderId, ?int $storeId = null)
    {
        return $this->createTask(TaskInterface::TASK_ORDER_STATUS_UPDATE, $orderId, null, $storeId);
    }

    /**
     * @param array $customData
     * @param array $consents
     * @param string $formType
     * @param int|null $messageId
     */
    public function addConsentsCustomData(array $customData, array $consents, string $formType, ?int $messageId = null)
    {
        $consentsArr = [];

        foreach ($consents as $consent) {
            try {
                if (is_array($consent)) {
                    $value = Consent::VALUE_FALSE;

                    if (true === $consent['value']) {
                        if (FormsConfig::FORM_TYPE_DOUBLE_OPT_IN === $formType) {
                            $value = Consent::VALUE_AWAITING_CONFIRMATION;
                        } else {
                            $value = Consent::VALUE_TRUE;
                        }
                    }
                    $consentsArr[$this->getEcdpConsentId($consent['id'])] = $value;
                } elseif (is_string($consent)) {
                    foreach (explode(',', $consent) as $consentId) {
                        $value = FormsConfig::FORM_TYPE_DOUBLE_OPT_IN === $formType ?
                            Consent::VALUE_AWAITING_CONFIRMATION : Consent::VALUE_TRUE;
                        $consentsArr[$this->getEcdpConsentId($consentId)] = $value;
                    }
                } else {
                    $value = FormsConfig::FORM_TYPE_DOUBLE_OPT_IN === $formType
                        ? Consent::VALUE_AWAITING_CONFIRMATION : Consent::VALUE_TRUE;
                    $consentsArr[$this->getEcdpConsentId($consent)] = $value;
                }
            } catch (\Exception $ex) {
                $this->logger->debug('Task service consent data error: ' . $ex->getMessage());
            }
        }

        $customData['consentsData'] = [
            'consents' => $consentsArr,
            'messageId' => $messageId
        ];

        return $customData;
    }

    /**
     * @return int
     */
    protected function getEcdpConsentId($consent)
    {
        $consentId = is_array($consent) ? (int) $consent['id'] : (int) $consent;
        $consentModel = $this->consentRepository->get($consentId);

        return $consentModel->getEcdpId();
    }
}
