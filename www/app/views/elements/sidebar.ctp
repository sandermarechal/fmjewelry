<div id="sidebar">
    <?php if ($subcategories): ?>
        <div class="browse">
            <h4>Browse by category</h4>
            <ul>
                <?php foreach ($subcategories as $subcategory): ?>
                    <li>
                        <a href="/categories/view/<?php echo $subcategory['Category']['slug']; ?>">
                            <?php echo $subcategory['Category']['name']; ?>
                        </a>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <?php if ($mailers): ?>
        <div class="browse">
            <h4>Browse by mailer</h4>
            <ul>
                <?php foreach ($mailers as $mailer): ?>
                    <li>
                        <a href="/mailers/view/<?php echo $mailer['User']['slug']; ?>">
                            <?php echo $mailer['User']['name']; ?>
                        </a>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>
</div>
