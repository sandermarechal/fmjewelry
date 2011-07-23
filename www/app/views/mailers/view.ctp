<?php echo $this->element('sidebar'); ?>

<h2>
    <?php echo $mailer['User']['name']; ?>
</h2>
<?php echo $mailer['User']['description_html']; ?>

<?php echo $this->element('products'); ?>
