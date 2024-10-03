<?php
/**
 * @author Endora
 * @copyright Copyright (c) Endora (https://endora.software)
 * @package Endora_ExpertSenderCdp
 */

namespace Endora\ExpertSenderCdp\Api;

use Endora\ExpertSenderCdp\Api\Data\OrderStatusMappingInterface;
use Magento\Framework\Api\SearchCriteriaInterface;

interface OrderStatusMappingRepositoryInterface
{
    /**
     * @param int $id
     * @return \Endora\ExpertSenderCdp\Api\Data\OrderStatusMappingInterface
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function get(int $id);

    /**
     * @param \Endora\ExpertSenderCdp\Api\Data\OrderStatusMappingInterface $orderStatusMapping
     * @return \Endora\ExpertSenderCdp\Api\Data\OrderStatusMappingInterface
     * @throws \Magento\Framework\Exception\AlreadyExistsException
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Magento\Framework\Exception\CouldNotSaveException
     */
    public function save(OrderStatusMappingInterface $orderStatusMapping);

    /**
     * @param \Endora\ExpertSenderCdp\Api\Data\OrderStatusMappingInterface $orderStatusMapping
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
     * @return \Endora\ExpertSenderCdp\Api\OrderStatusMappingSearchResultInterface
     */
    public function getList(SearchCriteriaInterface $searchCriteria);

    /**
     * @param string $orderStatus
     * @param int $store
     * @return \Endora\ExpertSenderCdp\Api\Data\OrderStatusMappingInterface|null
     */
    public function getByMagentoStatus(string $orderStatus, int $store);

    /**
     * @param string $ecdpStatus
     * @param int $store
     * @return \Endora\ExpertSenderCdp\Api\Data\OrderStatusMappingInterface|null
     */
    public function getByEcdpStatus(string $ecdpStatus, int $store);

    /**
     * @return \Endora\ExpertSenderCdp\Api\OrderStatusMappingSearchResultInterface
     */
    public function getAll();
}
