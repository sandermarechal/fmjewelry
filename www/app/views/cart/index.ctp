<?php if (count($cart)): ?>
    <div id="cart">
        <h2>Your cart</h2>
        <table>
            <tr>
                <th>Item</th>
                <th>Quantity</th>
                <th>Price</th>
                <th>Actions</th>
            </tr>
            <?php foreach ($cart as $index => $item): ?>
            <tr>
                <td class="item"><?php echo $this->Html->link($item['name'], array('controller' => 'products', 'action' => 'view', $item['slug'])); ?></td>
                <td class="qty"><?php echo $item['quantity']; ?></td>
                <td class="price">&euro; <?php echo $item['price_total']; ?></td>
                <td class="actions">
                    <?php echo $this->Html->image('icons/delete.png', array('url' => array('action' => 'delete', $index))); ?>
                </td>
            </tr>
            <?php endforeach; ?>
            <tr class="total">
                <td>Total</td>
                <td>&nbsp;</td>
                <td>&euro; <?php echo $price_total; ?></td>
                <td>&nbsp;</td>
            </tr>
        </table>
    </div>

    <div class="actions">
        <ul>
            <li><?php echo $this->Html->link('Checkout', '/cart/checkout');?></li>
            <li><?php echo $this->Html->link('Continue shopping', '/');?></li>
            <li><?php echo $this->Html->link('Empty cart', '/cart/clear');?></li>
        </ul>
    </div>
<?php else: ?>
    <div id="cart">
        <h2>Your cart is empty</h2>
    </div>

    <div class="actions">
        <ul>
            <li><?php echo $this->Html->link('Continue shopping', '/');?></li>
        </ul>
    </div>
<?php endif; ?>
