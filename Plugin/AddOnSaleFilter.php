<?php

namespace TechNimbus\CategoryOnSaleFilter\Plugin;

use Magento\Framework\ObjectManagerInterface;

class AddOnSaleFilter {

    /**
     * @var ObjectManager
     */
    protected $objectManager;

    /**
     * @var TechNimbus\CategoryOnSaleFilter\Model\Layer\Filter\OnSale
     */
    private $loadedModel;

    /**
     * Constructor.
     * @param ObjectManagerInterface $objectManager [description]
     */
    public function __construct(
        ObjectManagerInterface $objectManager
    ) {
        $this->objectManager = $objectManager;
    }

    public function beforeGetFilters(\Magento\Catalog\Model\Layer\FilterList $subject, \Magento\Catalog\Model\Layer $layer)
    {
        $this->loadedModel = $this->objectManager->create('TechNimbus\CategoryOnSaleFilter\Model\Layer\Filter\OnSale', ['layer' => $layer]);
        return [$layer];
    }

    public function afterGetFilters(\Magento\Catalog\Model\Layer\FilterList $subject, array $filters)
    {
        $filters[] = $this->loadedModel;
        return $filters;
    }
}
