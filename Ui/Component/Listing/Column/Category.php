<?php

namespace ChintakExtensions\CatalogFilter\Ui\Component\Listing\Column;

use Magento\Framework\View\Element\UiComponentFactory;
use Magento\Framework\View\Element\UiComponent\ContextInterface;

class Category extends \Magento\Ui\Component\Listing\Columns\Column
{
    /**
     * @var \Magento\Catalog\Model\ProductCategoryList
     */
    private $productCategory;

    /**
     * @var \Magento\Catalog\Api\CategoryRepositoryInterface
     */
    private $categoryRepository;

    /**
     * @param \Magento\Framework\View\Element\UiComponent\ContextInterface $context
     * @param \Magento\Framework\View\Element\UiComponentFactory $uiComponentFactory
     * @param array $components
     * @param array $data
     * @param \Magento\Catalog\Model\ProductCategoryList $productCategory
     * @param \Magento\Catalog\Api\CategoryRepositoryInterface $categoryRepository
     */
    public function __construct(
        ContextInterface $context,
        UiComponentFactory $uiComponentFactory,
        \Magento\Catalog\Model\ProductCategoryList $productCategory,
        \Magento\Catalog\Api\CategoryRepositoryInterface $categoryRepository,
        array $components = [],
        array $data = []
    ) {
        $this->productCategory = $productCategory;
        $this->categoryRepository = $categoryRepository;
        parent::__construct($context, $uiComponentFactory, $components, $data);
    }

    /**
     * Prepare data for the category column
     * @param  array  $dataSource
     * @return array
     */
    public function prepareDataSource(array $dataSource)
    {
        $fieldName = $this->getData('name');
        if (isset($dataSource['data']['items'])) {
            foreach ($dataSource['data']['items'] as &$item) {
                $productId = $item['entity_id'];
                $categoryIds = $this->getCategoryIds($productId);
                // echo "<pre>";
                // print_r($categoryIds);
                // echo "</pre>";
                $categories = [];
                if (count($categoryIds)) {
                    foreach ($categoryIds as $categoryId) {
                        $categoryData = $this->categoryRepository->get($categoryId);
                        // echo "<pre>";
                        // print_r($categoryData);
                        // echo "</pre>";
                        $categories[] = $categoryData->getName();
                        // echo "<pre>";
                        // print_r($categories);
                        // echo "</pre>";
                    }
                }
                $item[$fieldName] = implode('/', $categories);
                // print_r($item[$fieldName]);
            }
        }
        return $dataSource;
    }

    /**
     * Retrieve all the category ids
     *
     * @param integer $productId
     * @return array
     */
    private function getCategoryIds($productId)
    {
        $categoryIds = $this->productCategory->getCategoryIds($productId);
        $_categoryIds = [];
        if ($categoryIds) {
            $_categoryIds = array_unique($categoryIds);
        }
        // print_r($_categoryIds);die;
        return $_categoryIds;
    }
}
