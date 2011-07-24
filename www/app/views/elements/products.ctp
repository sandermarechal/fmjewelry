<?php if ($products): ?>
    <div id="products">
        <div class="product-group">
        <?php foreach ($products as $i => $product): ?>
            <div class="product-box<?php if (($i + 1) % 2 == 0) { echo ' last'; } ?>">
                <h4><?php echo $this->Html->link($product['Product']['name'], array('controller' => 'products', 'action' => 'view', $product['Product']['slug'])); ?></h4>
                <?php
                    if (isset($product['Product']['image'])) {
                        printf('<a href="/products/view/%s" class="product-thumb"><img src="/img/products/%s"></a>',
                            $product['Product']['slug'], $product['Product']['image']);
                    }
                ?>
                <p><?php echo $product['Product']['lead']; ?></p>
                <p>Price: <strong>&euro; <?php echo str_replace('.', ',', $product['Product']['price']); ?></strong></p>
            </div>
            <?php if (($i + 1) % 2 == 0 && count($products) > $i + 1): ?>
                </div><div class="product-group">
            <?php endif; ?>
        <?php endforeach; ?>
        </div>
    </div>
<?php endif; ?>
