<?php

$this->setLayoutVar('title', 'アカウント');

?>

<h2>アカウント</h2>

<p>
    ユーザID:
    <a href="<?= $base_url; ?>/user/<?= $user['user_name']; ?>">
        <strong><?= $this->escape($user['user_name']); ?></strong>
    </a>
</p>

<ul>
    <li><a href="<?= $base_url; ?>/">ホーム</a></li>
    <li><a href="<?= $base_url ?>/account/signout">ログアウト</a></li>
</ul>