<?php
/**
 * @author Endora
 * @copyright Copyright (c) Endora (https://endora.software)
 * @package Endora_ExpertSenderCdp
 */

namespace Endora\ExpertSenderCdp\Model;

use Endora\ExpertSenderCdp\Api\Data\TaskInterface;
use Magento\Framework\Model\AbstractModel;

class Task extends AbstractModel implements TaskInterface
{
    protected $_eventPrefix = 'endora_expertsender_task';
    protected $_eventObject = 'task';

    /**
     * {@inheritdoc}
     */
    protected function _construct()
    {
        $this->_init(\Endora\ExpertSenderCdp\Model\ResourceModel\Task::class);
    }

    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return $this->getData(self::ID);
    }

    /**
     * {@inheritdoc}
     */
    public function setId($id)
    {
        return $this->setData(self::ID, $id);
    }

    /**
     * {@inheritdoc}
     */
    public function getTask()
    {
        return $this->getData(self::TASK);
    }

    /**
     * {@inheritdoc}
     */
    public function setTask(string $task)
    {
        return $this->setData(self::TASK, $task);
    }

    /**
     * {@inheritdoc}
     */
    public function getObjectId()
    {
        return $this->getData(self::OBJECT_ID);
    }

    /**
     * {@inheritdoc}
     */
    public function setObjectId(?int $objectId)
    {
        return $this->setData(self::OBJECT_ID, $objectId);
    }

    /**
     * {@inheritdoc}
     */
    public function getAttempts()
    {
        return $this->getData(self::ATTEMPTS);
    }

    /**
     * {@inheritdoc}
     */
    public function setAttempts(int $attempts)
    {
        return $this->setData(self::ATTEMPTS, $attempts);
    }

    /**
     * {@inheritdoc}
     */
    public function getCustomData()
    {
        return json_decode($this->getData(self::CUSTOM_DATA), true);
    }

    /**
     * {@inheritdoc}
     */
    public function setCustomData(?array $customData)
    {
        if (null !== $customData) {
            $customData = json_encode($customData);
        }
        return $this->setData(self::CUSTOM_DATA, $customData);
    }

    /**
     * {@inheritdoc}
     */
    public function getStore()
    {
        return $this->getData(self::STORE);
    }

    /**
     * {@inheritdoc}
     */
    public function setStore(int $store)
    {
        return $this->setData(self::STORE, $store);
    }
}
