<?php
$exception = $info['exception'];
?>
<h3>Ошибка 404 — Страница не найдена</h3>

<div class="lithium-exception-class">
	<?=get_class($exception);?>

	<?php if ($code = $exception->getCode()): ?>
		<span class="code">(code <?=$code; ?>)</span>
	<?php endif ?>
</div>

<div class="lithium-exception-message"><?=$exception->getMessage(); ?></div>