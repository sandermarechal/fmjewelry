<?php
$this->Html->script('jquery-1.4.2.min.js', array('inline' => false));
$this->Html->script('jquery.fancybox-1.3.1.pack.js', array('inline' => false));
$this->Html->script('jquery.easing-1.3.pack.js', array('inline' => false));
$this->Html->css('jquery.fancybox-1.3.1', null, array('inline' => false));
?>

<?php echo $this->element('sidebar'); ?>

<div id="product-view">
	<h2><?php echo $product['Product']['name']; ?></h2>
	
    <?php
        if (count($product['Image'])) {
            $image = array_shift($product['Image']);
            printf('<a href="/img/products/%s" class="product-image spotlight" rel="gallery"><img src="/img/products/%s"></a>',
                $image['path_full'], $image['path_thumb']);
        }
    ?>

	<?php echo $product['Product']['description_html']; ?>

	<form method="post" action="/cart/add" id="add-to-cart">
        <input type="hidden" name="data[Product][id]" value="<?php echo $product['Product']['id'];?>" />
        <h4>
            Price: $ <span id="product-price"><?php echo $product['Product']['price']; ?></span>
            <input type="submit" name="submit" value="Add to cart" />
        </h4>
	</form>

    <div id="product-images">
        <div class="product-images-row">
            <?php foreach ($product['Image'] as $i => $image): ?>
                <?php
                    printf('<a href="/img/products/%s" class="product-image" rel="gallery"><img src="/img/products/%s"></a>',
                        $image['path_full'], $image['path_thumb']);
                ?>
                <?php if (($i + 1) % 4 == 0): ?>
                    </div><div class="product-images-row">
                <?php endif; ?>
            <?php endforeach; ?>
        </div>
    </div>
</div>

<script type="text/javascript">
	$(document).ready(function() {
        $('a.product-image').fancybox();
	});
</script>
