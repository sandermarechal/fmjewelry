<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<?php echo $html->charset(); ?>
	<title>
		<?php echo $title_for_layout; ?>
		Full Metal Jewelry
	</title>
	<?php
		echo $html->meta('icon');
		echo $html->css('reset.css');
		echo $html->css('main.css');
		echo $scripts_for_layout;
	?>
</head>
<body>
	<div id="container">
		<div id="header">
                        <h1>Full Metal Jewelry</h1>
		</div>
		<div class="nav">
			<ul>
				<li><a href="/">Home</a></li>
				<?php if ($Auth):?>
                    <li><a href="/users/view">Your account</a></li>
                    <li><a href="/users/logout">Logout</a></li>
				<?php else:?>
                    <li><a href="/users/login">Login</a></li>
				<?php endif;?>
                <li><a href="/cart">Cart</a></li>
                <li><a href="/pages/contact">Contact</a></li>
			</ul>
		</div>
		<div id="content">
			<?php echo $session->flash(); ?>
			<?php echo $content_for_layout; ?>
		</div>
		<div class="nav">
			<?php
			if ($adminControllers) {
			echo '<ul>';
				foreach ($adminControllers as $controller) {
					echo '<li>' . $this->Html->link($controller, '/admin/' . strtolower($controller) . '/index') . '</li>';
				}
				echo '</ul>';
			}
			?>
		</div>
	</div>
    <div id="footer">
        <p>&copy; Copyright 2010-<?php echo date('Y'); ?> Full Metal Jewelry
        | Some rights reserved
        | Design by <a href="http://www.jejik.com">Lone Wolves</a>
        | View <a href="http://github.com/sandermarechal/fmjewelry">source code</a></p>
    </div>
	<?php echo $this->element('sql_dump'); ?>
</body>
</html>
