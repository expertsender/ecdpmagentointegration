<?php
/**
 * @author Endora
 * @copyright Copyright (c) Endora (https://endora.software)
 * @package ExpertSender_Ecdp
 */

namespace ExpertSender\Ecdp\Api;

use ExpertSender\Ecdp\Api\Data\OrderStatusMappingInterface;
use Magento\Framework\Api\SearchCriteriaInterface;

interface OrderStatusMappingRepositoryInterface
{
    /**
     * @param int $id
     * @return \ExpertSender\Ecdp\Api\Data\OrderStatusMappingInterface
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function get(int $id);

    /**
     * @param \ExpertSender\Ecdp\Api\Data\OrderStatusMappingInterface $orderStatusMapping
     * @return \ExpertSender\Ecdp\Api\Data\OrderStatusMappingInterface
     * @throws \Magento\Framework\Exception\AlreadyExistsException
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Magento\Framework\Exception\CouldNotSaveException
     */
    public function save(OrderStatusMappingInterface $orderStatusMapping);

    /**
     * @param \ExpertSender\Ecdp\Api\Data\OrderStatusMappingInterface $orderStatusMapping
     * @return bool
     * @throws \Magento\Framework\Exception\CouldNotDeleteException
     */
    public function delete(OrderStatusMappingInterface $orderStatusMapping);

    /**
     * @param int $id
     * @return bool
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Magento\Framework\Exception\CouldNotDeleteException
     */
    public function deleteById(int $id);

    /**
     * @param \Magento\Framework\Api\SearchCriteriaInterface
     * @return \ExpertSender\Ecdp\Api\OrderStatusMappingSearchResultInterface
     */
    public function getList(SearchCriteriaInterface $searchCriteria);

    /**
     * @param string $orderStatus
     * @param int $store
     * @return \ExpertSender\Ecdp\Api\Data\OrderStatusMappingInterface|null
     */
    public function getByMagentoStatus(string $orderStatus, int $store);

    /**
     * @param string $ecdpStatus
     * @param int $store
     * @return \ExpertSender\Ecdp\Api\Data\OrderStatusMappingInterface|null
     */
    public function getByEcdpStatus(string $ecdpStatus, int $store);

    /**
     * @return \ExpertSender\Ecdp\Api\OrderStatusMappingSearchResultInterface
     */
    public function getAll();
}
