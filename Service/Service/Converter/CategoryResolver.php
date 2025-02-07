<?php
/**
 * @author Endora
 * @copyright Copyright (c) Endora (https://endora.software)
 * @package ExpertSender_Ecdp
 */

namespace ExpertSender\Ecdp\Service\Converter;

use ExpertSender\Ecdp\Model\Config;
use ExpertSender\Ecdp\Model\Config\Source\OrderData\CategoryFormat;
use Magento\Catalog\Api\CategoryRepositoryInterface;
use Magento\Framework\Exception\NoSuchEntityException;

class CategoryResolver
{
    /**
     * @var \Magento\Catalog\Api\CategoryRepositoryInterface
     */
    protected $categoryRepository;

    /**
     * @var \ExpertSender\Ecdp\Model\Config
     */
    protected $config;

    /**
     * @param \Magento\Catalog\Api\CategoryRepositoryInterface $categoryRepository
     * @param \ExpertSender\Ecdp\Model\Config $config
     */
    public function __construct(CategoryRepositoryInterface $categoryRepository, Config $config)
    {
        $this->categoryRepository = $categoryRepository;
        $this->config = $config;
    }

    /**
     * @param array $categoryIds
     * @param int $storeId
     *
     * @return string
     */
    public function execute(array $categoryIds, int $storeId)
    {
        $category = '';
        $categoryFormat = $this->config->getCategoryFormat($storeId);

        switch ($categoryFormat) {
            case CategoryFormat::VALUE_LAST:
                $category = $this->handleCategoryFormatLast($categoryIds, $storeId);
                break;
            case CategoryFormat::VALUE_ALL:
            default:
                $category = $this->handleCategoryFormatAll($categoryIds, $storeId);
        }
        
        return $category;
    }

    /**
     * @param array $categoryIds
     * @param int $storeId
     *
     * @return string
     */
    public function handleCategoryFormatLast(array $categoryIds, int $storeId)
    {
        if (empty($categoryIds)) {
            return '';
        }

        $categoryId = (int) array_pop($categoryIds);

        try {
            $category = $this->categoryRepository->get($categoryId, $storeId);
        } catch (NoSuchEntityException $ex) {
            return '';
        }

        return $category->getName();
    }

    /**
     * @param array $categoryIds
     * @param int $storeId
     *
     * @return string
     */
    public function handleCategoryFormatAll(array $categoryIds, int $storeId)
    {
        $categories = [];

        foreach ($categoryIds as $categoryId) {
            try {
                $category = $this->categoryRepository->get((int) $categoryId, $storeId);
                $categories[] = $category->getName();
            } catch (NoSuchEntityException $ex) {
                continue;
            }
        }

        return implode(' | ', $categories);
    }
}