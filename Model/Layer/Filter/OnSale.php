<?php

namespace TechNimbus\CategoryOnSaleFilter\Model\Layer\Filter;

use Magento\Catalog\Model\Layer\Filter\AbstractFilter;

class OnSale extends AbstractFilter {

    /**
     * @var \Magento\Framework\App\ResourceConnection
     */
    protected $resourceConnection;

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
         \Magento\Framework\App\ResourceConnection $resourceConnection,
         array $data = []
     ) {
        parent::__construct(
            $filterItemFactory,
            $storeManager,
            $layer,
            $itemDataBuilder,
            $data
        );
        $this->resourceConnection = $resourceConnection;
        $this->_requestVar = 'on_sale';
    }

    /**
     * Get filter name
     * @return mixed
     */
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
            $this->getLayer()
                ->getProductCollection()
                ->getSelect()
                ->where('price_index.final_price > 0 && price_index.final_price < price_index.price');

            $this->getLayer()->getState()->addFilter($this->_createItem('On Sale', 1));
        }

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    protected function _getItemsData()
    {
        $this->itemDataBuilder->addItemData(
            __('On Sale'),
            true,
            $this->getOnSalesCount($this->getLayer()->getProductCollection())
        );
        return $this->itemDataBuilder->build();
    }

    /**
     * Get count of how many products are in sale on the currenct filter
     * @param  \Magento\Catalog\Model\ResourceModel\Product\Collection $collection
     * @return integer
     */
    private function getOnSalesCount(\Magento\Catalog\Model\ResourceModel\Product\Collection $collection)
    {
        $select = clone $collection->getSelect();
        $select->reset(\Magento\Framework\DB\Select::COLUMNS);
        $select->reset(\Magento\Framework\DB\Select::ORDER);
        $select->reset(\Magento\Framework\DB\Select::LIMIT_COUNT);
        $select->reset(\Magento\Framework\DB\Select::LIMIT_OFFSET);
        $select->columns('count(*) as count');
        $select->where('price_index.final_price > 0 && price_index.final_price < price_index.price');

        $connection = $this->resourceConnection->getConnection();
        $result = $connection->fetchAll($select);
        return $result[0]['count'];
    }
}
