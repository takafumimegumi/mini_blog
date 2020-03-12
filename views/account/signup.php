<?php

$this->setLayoutVar('title', 'アカウント登録');

?>

<h2>アカウント登録</h2>

<form action="<?= $base_url; ?>/account/register" method="post">
    <!-- トークンをセットしCSRF対策 -->
    <input type="hidden" name="_token" value="<?= $this->escape($_token); ?>">

    <table>
        <tbody>
            <tr>
                <th>ユーザID</th>
                <td><input type="text" name="user_name" value=""></td>
            </tr>
            <tr>
                <th>パスワード</th>
                <td><input type="text" name="password" value=""></td>
            </tr>
        </tbody>
    </table>

    <p><input type="submit" value="登録"></p>
</form>