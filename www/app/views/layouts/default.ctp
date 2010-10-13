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
			<?php echo $header->h1('Full Metal Jewelry');?>
		</div>
		<div class="nav">
			<ul>
				<li><?php echo $this->Button->link('Home', '/');?></li>
				<?php if ($Auth):?>
					<li><?php echo $this->Button->link('Your account', '/users/view');?></li>
					<li><?php echo $this->Button->link('Logout', '/users/logout');?></li>
				<?php else:?>
					<li><?php echo $this->Button->link('Login', '/users/login');?></li>
					<li><?php echo $this->Button->link('Register', '/users/register');?></li>
				<?php endif;?>
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
					echo '<li>' . $this->Button->link($controller, '/admin/' . strtolower($controller) . '/index') . '</li>';
				}
				echo '</ul>';
			}
			?>
		</div>
		<div id="footer">
			<p>&copy; 2010 Full Metal Jewelry
			| Design by <a href="http://www.jejik.com">Lone Wolves</a>
			| Valid XHTML, CSS, WCAG 1.0 A</p>
		</div>
	</div>
	<?php echo $this->element('sql_dump'); ?>
</body>
</html>
