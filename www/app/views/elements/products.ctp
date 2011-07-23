<?php if ($products): ?>
    <div id="products">
        <div class="product-group">
        <?php foreach ($products as $i => $product): ?>
            <div class="product-box<?php if (($i + 1) % 2 == 0) { echo ' last'; } ?>">
                <h4><?php echo $this->Html->link($product['Product']['name'], array('controller' => 'products', 'action' => 'view', $product['Product']['slug'])); ?></h4>
                <?php
                    if (isset($product['Product']['image'])) {
                        echo $html->image('/img/products/' . $product['Product']['image'], array(
                            'url' => array('controller' => 'products', 'action' => 'view', $product['Product']['slug']),
                        ));
                    }
                    echo $product['Product']['lead'];
                ?>
            </div>
            <?php if (($i + 1) % 2 == 0 && count($products) > $i + 1): ?>
                </div><div class="product-group">
            <?php endif; ?>
        <?php endforeach; ?>
        </div>
    </div>
<?php endif; ?>
