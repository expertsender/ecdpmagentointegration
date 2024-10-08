<?php
/**
 * @author Endora
 * @copyright Copyright (c) Endora (https://endora.software)
 * @package ExpertSender_Ecdp
 */

namespace ExpertSender\Ecdp\Api\Data;

interface TaskInterface
{
    public const ID = 'id';
    public const TASK = 'task';
    public const OBJECT_ID = 'object_id';
    public const ATTEMPTS = 'attempts';
    public const CUSTOM_DATA = 'custom_data';
    public const STORE = 'store';

    public const TASK_ORDER_ADD = 'order_add';
    public const TASK_ORDER_UPDATE = 'order_update';
    public const TASK_CUSTOMER_ADD = 'customer_add';
    public const TASK_CUSTOMER_UPDATE = 'customer_update';
    public const TASK_GUEST_CUSTOMER_UPDATE = 'guest_customer_update';
    public const TASK_ORDER_STATUS_UPDATE = 'order_status_update';

    /**
     * @return int
     */
    public function getId();

    /**
     * @param int $id
     * @return self
     */
    public function setId($id);

    /**
     * @return string
     */
    public function getTask();

    /**
     * @param string $task
     * @return self
     */
    public function setTask(string $task);

    /**
     * @return int|null
     */
    public function getObjectId();

    /**
     * @param int|null $objectId
     * @return self
     */
    public function setObjectId(?int $objectId);

    /**
     * @return int
     */
    public function getAttempts();

    /**
     * @param int $attempts
     * @return self
     */
    public function setAttempts(int $attempts);

    /**
     * @return array
     */
    public function getCustomData();

    /**
     * @param array|null $customData
     * @return self
     */
    public function setCustomData(?array $customData);

    /**
     * @return int
     */
    public function getStore();

    /**
     * @param int $store
     * @return self
     */
    public function setStore(int $store);
}
