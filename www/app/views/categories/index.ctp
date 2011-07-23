<?php echo $this->element('sidebar'); ?>

<h2><?php echo $category['Category']['name']; ?></h2>
<?php echo $category['Category']['description']; ?>

<h3>Features</h3>

<?php echo $this->element('products'); ?>
