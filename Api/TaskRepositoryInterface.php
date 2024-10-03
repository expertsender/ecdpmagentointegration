<?php
/**
 * @author Endora
 * @copyright Copyright (c) Endora (https://endora.software)
 * @package Endora_ExpertSenderCdp
 */

namespace Endora\ExpertSenderCdp\Api;

use Endora\ExpertSenderCdp\Api\Data\TaskInterface;
use Magento\Framework\Api\SearchCriteriaInterface;

interface TaskRepositoryInterface
{
    /**
     * @param int $id
     * @return \Endora\ExpertSenderCdp\Api\Data\TaskInterface
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function get(int $id);

    /**
     * @param \Endora\ExpertSenderCdp\Api\Data\TaskInterface $task
     * @return \Endora\ExpertSenderCdp\Api\Data\TaskInterface
     * @throws \Magento\Framework\Exception\AlreadyExistsException
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Magento\Framework\Exception\CouldNotSaveException
     */
    public function save(TaskInterface $task);

    /**
     * @param \Endora\ExpertSenderCdp\Api\Data\TaskInterface $task
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
     * @return \Endora\ExpertSenderCdp\Api\TaskSearchResultInterface
     */
    public function getList(SearchCriteriaInterface $searchCriteria);

    /**
     * @param string $task
     * @param int $objectId
     * @return \Endora\ExpertSenderCdp\Api\Data\TaskInterface|null
     */
    public function getByTaskAndObjectId(string $task, int $objectId);
}
