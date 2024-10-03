<?php
/**
 * @author Endora
 * @copyright Copyright (c) Endora (https://endora.software)
 * @package Endora_ExpertSenderCdp
 */

namespace Endora\ExpertSenderCdp\Api;

use Endora\ExpertSenderCdp\Api\Data\ConsentFormElementInterface;
use Magento\Framework\Api\SearchCriteriaInterface;

interface ConsentFormElementRepositoryInterface
{
    /**
     * @param int $id
     * @return \Endora\ExpertSenderCdp\Api\Data\ConsentFormElementInterface
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function get(int $id);

    /**
     * @param \Endora\ExpertSenderCdp\Api\Data\ConsentFormElementInterface $formElement
     * @return \Endora\ExpertSenderCdp\Api\Data\ConsentFormElementInterface
     * @throws \Magento\Framework\Exception\AlreadyExistsException
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Magento\Framework\Exception\CouldNotSaveException
     */
    public function save(ConsentFormElementInterface $formElement);

    /**
     * @param \Endora\ExpertSenderCdp\Api\Data\ConsentFormElementInterface $formElement
     * @return bool
     * @throws \Magento\Framework\Exception\CouldNotDeleteException
     */
    public function delete(ConsentFormElementInterface $formElement);

    /**
     * @param int $id
     * @return bool
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Magento\Framework\Exception\CouldNotDeleteException
     */
    public function deleteById(int $id);

    /**
     * @param \Magento\Framework\Api\SearchCriteriaInterface
     * @return \Endora\ExpertSenderCdp\Api\ConsentFormElementSearchResultInterface
     */
    public function getList(SearchCriteriaInterface $searchCriteria);
}
