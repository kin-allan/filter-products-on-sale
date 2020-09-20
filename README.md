<p align="center">
    <h1 align="center">Tech Nimbus On Sale Filter</h1>
    <br>
</p>

<h3>Instructions</h3>

<ul>
    <li>1. Go to the Magento root directory</li>
    <li>1. Run the command: <code>composer config repositories.kin-allan-filter-products-on-sale git https://github.com/kin-allan/filter-products-on-sale</code></li>
    <li>2. Then: <code>composer require kin-allan/filter-products-on-sale:1.0.1</code></li>
    <li>3. After the composer process is finished, run those commands:</li>
    <li><code>php bin/magento module:enable TechNimbus_CategoryOnSaleFilter</code></li>
    <li><code>php bin/magento setup:upgrade</code></li>
    <li><code>php bin/magento setup:di:compile</code></li>
    <li><code>php bin/magento cache:flush</code></li>
</ul>
