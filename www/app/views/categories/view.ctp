<?php echo $this->element('sidebar'); ?>

<h2>
    <?php echo $category['Category']['name']; ?>
</h2>
<?php echo $category['Category']['description']; ?>

<?php echo $this->element('products'); ?>
