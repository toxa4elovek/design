<?php
/**
 * Lithium: the most rad php framework
 *
 * @copyright     Copyright 2011, Union of RAD (http://union-of-rad.org)
 * @license       http://opensource.org/licenses/bsd-license.php The BSD License
 */

/**
 * This layout is used to render error pages in both development and production. It is recommended
 * that you maintain a separate, simplified layout for rendering errors that does not involve any
 * complex logic or dynamic data, which could potentially trigger recursive errors.
 */
?>
<!doctype html>
<html>
<head>
	<?php echo $this->html->charset(); ?>
	<title>Unhandled exception</title>
	<?php echo $this->html->style(array('debug', 'lithium')); ?>
	<?php echo $this->scripts(); ?>
	<?php echo $this->html->link('Icon', null, array('type' => 'icon')); ?>
</head>
<body class="app">
	<div id="container">
		<div id="header">
			<h1>Брошено необработанное исключение!</h1>
			<p>
				Не беспокойтесь, информация об этой ошибке сохранена и баг будет исправлен!
			</p>
		</div>
		<div id="content">
			<?php echo $this->content(); ?>
		</div>
	</div>
</body>
</html>