<?php
/**
 * @author Endora
 * @copyright Copyright (c) Endora (https://endora.software)
 * @package ExpertSender_Ecdp
 */

namespace ExpertSender\Ecdp\Model;

use ExpertSender\Ecdp\Api\Data\TaskInterface;
use ExpertSender\Ecdp\Api\Data\TaskInterfaceFactory;
use ExpertSender\Ecdp\Api\TaskRepositoryInterface;
use ExpertSender\Ecdp\Api\TaskSearchResultInterfaceFactory;
use ExpertSender\Ecdp\Model\ResourceModel\Task as TaskResource;
use ExpertSender\Ecdp\Model\ResourceModel\Task\CollectionFactory;
use Magento\Framework\Api\SearchCriteria\CollectionProcessor;
use Magento\Framework\Api\SearchCriteriaBuilderFactory;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Exception\AlreadyExistsException;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;

class TaskRepository implements TaskRepositoryInterface
{
    /**
     * @var \ExpertSender\Ecdp\Api\Data\TaskInterface
     */
    protected $taskFactory;

    /**
     * @var \ExpertSender\Ecdp\Model\ResourceModel\Task
     */
    protected $resource;

    /**
     * @var \ExpertSender\Ecdp\Api\TaskSearchResultInterfaceFactory
     */
    protected $taskSearchResultFactory;

    /**
     * @var \ExpertSender\Ecdp\Model\ResourceModel\FieldMapping\CollectionFactory
     */
    protected $collectionFactory;

    /**
     * @var \Magento\Framework\Api\SearchCriteriaBuilderFactory
     */
    protected $searchCriteriaBuilderFactory;

    /**
     * @var \Magento\Framework\Api\SearchCriteria\CollectionProcessor
     */
    protected $collectionProcessor;

    /**
     * @param \ExpertSender\Ecdp\Api\Data\TaskInterfaceFactory $taskFactory
     * @param \ExpertSender\Ecdp\Model\ResourceModel\Task $resource
     * @param \ExpertSender\Ecdp\Api\TaskSearchResultInterfaceFactory $taskSearchResultFactory
     * @param \ExpertSender\Ecdp\Model\ResourceModel\Task\CollectionFactory $collectionFactory
     * @param \Magento\Framework\Api\SearchCriteriaBuilderFactory $searchCriteriaBuilderFactory
     * @param \Magento\Framework\Api\SearchCriteria\CollectionProcessor $collectionProcessor
     */
    public function __construct(
        TaskInterfaceFactory $taskFactory,
        TaskResource $resource,
        TaskSearchResultInterfaceFactory $taskSearchResultFactory,
        CollectionFactory $collectionFactory,
        SearchCriteriaBuilderFactory $searchCriteriaBuilderFactory,
        CollectionProcessor $collectionProcessor
    ) {
        $this->taskFactory = $taskFactory;
        $this->resource = $resource;
        $this->taskSearchResultFactory = $taskSearchResultFactory;
        $this->collectionFactory = $collectionFactory;
        $this->searchCriteriaBuilderFactory = $searchCriteriaBuilderFactory;
        $this->collectionProcessor = $collectionProcessor;
    }

    /**
     * {@inheritdoc}
     */
    public function get(int $id)
    {
        $task = $this->taskFactory->create();
        $task->load($id);

        if (!$task->getId()) {
            throw new NoSuchEntityException(__('Task with ID "%1" does not exist.', $id));
        }

        return $task;
    }

    /**
     * {@inheritdoc}
     */
    public function save(TaskInterface $task)
    {
        try {
            $id = $task->getId();

            if ($id) {
                $this->get($id);
            }

            $this->resource->save($task);
        } catch (AlreadyExistsException $ex) {
            throw $ex;
        } catch (NoSuchEntityException $ex) {
            throw $ex;
        } catch (LocalizedException $ex) {
            throw new CouldNotSaveException(__($ex->getMessage()));
        }

        return $task;
    }

    /**
     * {@inheritdoc}
     */
    public function delete(TaskInterface $task)
    {
        try {
            $this->resource->delete($task);
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
        $searchResults = $this->taskSearchResultFactory->create();
        $collection = $this->collectionFactory->create();
        $this->collectionProcessor->process($searchCriteria, $collection);
        $searchResults->setSearchCriteria($searchCriteria);
        $searchResults->setTotalCount($collection->getSize());
        $searchResults->setItems($collection->getItems());

        return $searchResults;
    }

    /**
     * {@inheritdoc}
     */
    public function getByTaskAndObjectId(string $task, int $objectId)
    {
        $searchCriteria = $this->searchCriteriaBuilderFactory->create()
            ->addFilter('task', $task)
            ->addFilter('object_id', $objectId)
            ->create();

        $tasks = $this->getList($searchCriteria)->getItems();

        return array_shift($tasks);
    }
}
