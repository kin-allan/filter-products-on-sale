<?php

namespace TechNimbus\CategoryOnSaleFilter\Model\Layer\Filter;

use Magento\Catalog\Model\Layer\Filter\AbstractFilter;

class OnSale extends AbstractFilter {

    /**
     * Constructor
     *
     * @param \Magento\Catalog\Model\Layer\Filter\ItemFactory $filterItemFactory
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     * @param \Magento\Catalog\Model\Layer $layer
     * @param \Magento\Catalog\Model\Layer\Filter\Item\DataBuilder $itemDataBuilder
     * @param array $data
     * @throws \Magento\Framework\Exception\LocalizedException
     */
     public function __construct(
         \Magento\Catalog\Model\Layer\Filter\ItemFactory $filterItemFactory,
         \Magento\Store\Model\StoreManagerInterface $storeManager,
         \Magento\Catalog\Model\Layer $layer,
         \Magento\Catalog\Model\Layer\Filter\Item\DataBuilder $itemDataBuilder,
         array $data = []
     ) {
        parent::__construct(
            $filterItemFactory,
            $storeManager,
            $layer,
            $itemDataBuilder,
            $data
        );

        $this->_requestVar = 'on_sale';
    }

    public function getName()
    {
        return __('On Sale');
    }

    /**
     * {@inheritdoc}
     */
    public function apply(\Magento\Framework\App\RequestInterface $request)
    {
        if ($request->getParam($this->getRequestVar())) {
            $collection = $this->getLayer()->getProductCollection();
            $this->filterCollectionBySaleItems($collection);
            $this->getLayer()->getState()->addFilter($this->_createItem('On Sale', 1));
        }

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    protected function _getItemsData()
    {
        $collection = $this->filterCollectionBySaleItems($this->getLayer()->getProductCollection());
        $this->itemDataBuilder->addItemData(__('On Sale'), true, $collection->count());
        return $this->itemDataBuilder->build();
    }

    private function filterCollectionBySaleItems(\Magento\Catalog\Model\ResourceModel\Product\Collection $collection)
    {
        $collection->getSelect()->where('price_index.final_price > 0 && price_index.final_price < price_index.price');
        $collection->clear();
        return $collection;
    }
}
