<?php
/**
 * @author Endora
 * @copyright Copyright (c) Endora (https://endora.software)
 * @package ExpertSender_Ecdp
 */

namespace ExpertSender\Ecdp\Api;

use ExpertSender\Ecdp\Api\Data\TaskInterface;
use Magento\Framework\Api\SearchCriteriaInterface;

interface TaskRepositoryInterface
{
    /**
     * @param int $id
     * @return \ExpertSender\Ecdp\Api\Data\TaskInterface
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function get(int $id);

    /**
     * @param \ExpertSender\Ecdp\Api\Data\TaskInterface $task
     * @return \ExpertSender\Ecdp\Api\Data\TaskInterface
     * @throws \Magento\Framework\Exception\AlreadyExistsException
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Magento\Framework\Exception\CouldNotSaveException
     */
    public function save(TaskInterface $task);

    /**
     * @param \ExpertSender\Ecdp\Api\Data\TaskInterface $task
     * @return bool
     * @throws \Magento\Framework\Exception\CouldNotDeleteException
     */
    public function delete(TaskInterface $task);

    /**
     * @param int $id
     * @return bool
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Magento\Framework\Exception\CouldNotDeleteException
     */
    public function deleteById(int $id);

    /**
     * @param \Magento\Framework\Api\SearchCriteriaInterface
     * @return \ExpertSender\Ecdp\Api\TaskSearchResultInterface
     */
    public function getList(SearchCriteriaInterface $searchCriteria);

    /**
     * @param string $task
     * @param int $objectId
     * @return \ExpertSender\Ecdp\Api\Data\TaskInterface|null
     */
    public function getByTaskAndObjectId(string $task, int $objectId);
}
