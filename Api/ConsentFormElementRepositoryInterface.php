<?php
/**
 * @author Endora
 * @copyright Copyright (c) Endora (https://endora.software)
 * @package ExpertSender_Ecdp
 */

namespace ExpertSender\Ecdp\Api;

use ExpertSender\Ecdp\Api\Data\ConsentFormElementInterface;
use Magento\Framework\Api\SearchCriteriaInterface;

interface ConsentFormElementRepositoryInterface
{
    /**
     * @param int $id
     * @return \ExpertSender\Ecdp\Api\Data\ConsentFormElementInterface
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function get(int $id);

    /**
     * @param \ExpertSender\Ecdp\Api\Data\ConsentFormElementInterface $formElement
     * @return \ExpertSender\Ecdp\Api\Data\ConsentFormElementInterface
     * @throws \Magento\Framework\Exception\AlreadyExistsException
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Magento\Framework\Exception\CouldNotSaveException
     */
    public function save(ConsentFormElementInterface $formElement);

    /**
     * @param \ExpertSender\Ecdp\Api\Data\ConsentFormElementInterface $formElement
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
     * @return \ExpertSender\Ecdp\Api\ConsentFormElementSearchResultInterface
     */
    public function getList(SearchCriteriaInterface $searchCriteria);
}
