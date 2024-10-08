<?php
/**
 * @author Endora
 * @copyright Copyright (c) Endora (https://endora.software)
 * @package ExpertSender_Ecdp
 */

namespace ExpertSender\Ecdp\Model\Consent;

use ExpertSender\Ecdp\Api\Data\ConsentFormElementInterface;
use ExpertSender\Ecdp\Api\Data\ConsentFormElementInterfaceFactory;
use ExpertSender\Ecdp\Api\ConsentFormElementRepositoryInterface;
use ExpertSender\Ecdp\Api\ConsentFormElementSearchResultInterfaceFactory;
use ExpertSender\Ecdp\Model\ResourceModel\Consent\FormElement as ConsentFormElementResource;
use ExpertSender\Ecdp\Model\ResourceModel\Consent\FormElement\CollectionFactory;
use Magento\Framework\Api\SearchCriteria\CollectionProcessor;
use Magento\Framework\Api\SearchCriteriaBuilderFactory;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Exception\AlreadyExistsException;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;

class FormElementRepository implements ConsentFormElementRepositoryInterface
{
    /**
     * @var \ExpertSender\Ecdp\Api\Data\ConsentFormElementInterface
     */
    protected $formElementFactory;

    /**
     * @var \ExpertSender\Ecdp\Model\ResourceModel\ConsentFormElement
     */
    protected $resource;

    /**
     * @var \ExpertSender\Ecdp\Api\ConsentFormElementSearchResultInterfaceFactory
     */
    protected $formElementSearchResultFactory;

    /**
     * @var \ExpertSender\Ecdp\Model\ResourceModel\FieldMapping\CollectionFactory
     */
    protected $collectionFactory;

    /**
     * @var \Magento\Framework\Api\SearchCriteria\CollectionProcessor
     */
    protected $collectionProcessor;

    /**
     * @var \Magento\Framework\Api\SearchCriteriaBuilderFactory
     */
    protected $searchCriteriaBuilderFactory;

    /**
     * @param \ExpertSender\Ecdp\Api\Data\ConsentFormElementInterfaceFactory $formElementFactory
     * @param \ExpertSender\Ecdp\Model\ResourceModel\ConsentFormElement $resource
     * @param \ExpertSender\Ecdp\Api\ConsentFormElementSearchResultInterfaceFactory $formElementSearchResultFactory
     * @param \ExpertSender\Ecdp\Model\ResourceModel\ConsentFormElement\CollectionFactory $collectionFactory
     * @param \Magento\Framework\Api\SearchCriteria\CollectionProcessor $collectionProcessor
     * @param \Magento\Framework\Api\SearchCriteriaBuilderFactory $searchCriteriaBuilderFactory
     */
    public function __construct(
        ConsentFormElementInterfaceFactory $formElementFactory,
        ConsentFormElementResource $resource,
        ConsentFormElementSearchResultInterfaceFactory $formElementSearchResultFactory,
        CollectionFactory $collectionFactory,
        CollectionProcessor $collectionProcessor,
        SearchCriteriaBuilderFactory $searchCriteriaBuilderFactory
    ) {
        $this->formElementFactory = $formElementFactory;
        $this->resource = $resource;
        $this->formElementSearchResultFactory = $formElementSearchResultFactory;
        $this->collectionFactory = $collectionFactory;
        $this->collectionProcessor = $collectionProcessor;
        $this->searchCriteriaBuilderFactory = $searchCriteriaBuilderFactory;
    }

    /**
     * {@inheritdoc}
     */
    public function get(int $id)
    {
        $formElement = $this->formElementFactory->create();
        $formElement->load($id);

        if (!$formElement->getId()) {
            throw new NoSuchEntityException(__('Consent Form Element with ID "%1" does not exist.', $id));
        }

        return $formElement;
    }

    /**
     * {@inheritdoc}
     */
    public function save(ConsentFormElementInterface $formElement)
    {
        try {
            $id = $formElement->getId();

            if ($id) {
                $this->get($id);
            }

            foreach ($formElement->getConsentIds() as $consentId) {
                $searchCriteria = $this->searchCriteriaBuilderFactory->create()
                    ->addFilter(ConsentFormElementInterface::STORE, $formElement->getStore())
                    ->addFilter(ConsentFormElementInterface::CONSENT_IDS, $consentId, 'finset')
                    ->addFilter(ConsentFormElementInterface::FORM, $formElement->getForm())
                    ->create();

                $result = $this->getList($searchCriteria);

                if ($result->getTotalCount() > 0) {
                    if ($result->getTotalCount() === 1) {
                        $items = $result->getItems();
                        $item = array_shift($items);

                        if ($item->getId() === $formElement->getId()) {
                            continue;
                        }
                    }

                    throw new AlreadyExistsException(__(
                        'Consent Form Element with consent ID "%1" already exists for store ID "%2".',
                        $consentId,
                        $formElement->getStore()
                    ));
                }
            }

            $this->resource->save($formElement);
        } catch (AlreadyExistsException $ex) {
            throw $ex;
        } catch (NoSuchEntityException $ex) {
            throw $ex;
        } catch (LocalizedException $ex) {
            throw new CouldNotSaveException(__($ex->getMessage()));
        }

        return $formElement;
    }

    /**
     * {@inheritdoc}
     */
    public function delete(ConsentFormElementInterface $formElement)
    {
        try {
            $this->resource->delete($formElement);
        } catch (\Exception $ex) {
            throw new CouldNotDeleteException(__($ex->getMessage()));
        }

        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function deleteById(int $id)
    {
        return $this->delete($this->get($id));
    }

    /**
     * {@inheritdoc}
     */
    public function getList(SearchCriteriaInterface $searchCriteria)
    {
        $searchResults = $this->formElementSearchResultFactory->create();
        $collection = $this->collectionFactory->create();
        $this->collectionProcessor->process($searchCriteria, $collection);
        $searchResults->setSearchCriteria($searchCriteria);
        $searchResults->setTotalCount($collection->getSize());
        $searchResults->setItems($collection->getItems());

        return $searchResults;
    }
}
