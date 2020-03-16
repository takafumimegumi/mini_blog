<?php

$this->setLayoutVar('title', $user['user_name']);

?>

<h2><?= $this->escape($user['user_name']); ?></h2>

<div id="statuses">
    <?php foreach ($statuses as $status): ?>
    <?= $this->render('status/status', ['status' => $status]); ?>
    <?php endforeach; ?>
</div>