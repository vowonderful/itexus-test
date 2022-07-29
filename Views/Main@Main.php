<?php
/**
 * @var array $data -> Prepared parameters
 */
?>

<h1><?= _SITE_NAME ?></h1>

<?php include $part['menu']; ?>
<?php include $part['notify']; ?>

<?php if ( !empty($data) && !empty($data['users']) ): ?>
<h2 class="title mt-2">Last users:</h2>
<div class="user main">
<?php foreach($data['users'] as $key => $user) : ?>
    <div class="user__item">
        <i><?= ($key + 1) ?>.</i> <a href="/account/<?= $user['user_id'] ?>"><?= $user['username'] ?></a>
        <?php if ( !empty($user['name']) ): ?>
            <small>(<?= $user['name'] ?>)</small>
        <?php endif; ?>
    </div>
<?php endforeach; ?>
</div>
<?php endif; ?>

<h2 class="title mt-2">Тестовое задание</h2>
<div class="user main">
    <b><i>Демонстрация реализации тестового задания от компании <u>iTexUS</u>.</i></b>
    <br><hr>
    Условия, требования и ТЗ:
    <div class="user__item">
        Реализовать MVC + route приложение на PHP.
    </div>
    <div class="user__item">
        Реализовать свой самописный проект, который с лёгкостью можно расширять.
    </div>
    <div class="user__item row">
        <div>Реализовать три страницы:</div>
        <div><b>1.</b> главная;</div>
        <div><b>2.</b> вход/авторизация пользователя;</div>
        <div><b>3.</b> личный кабинет, где можно отредактировать имя пользователя</div>
    </div>
</div>

<h2 class="title mt-2">Параметры администратора</h2>
<div class="user main">
    <div class="user__item">
        Логин: <code style="margin-left: 5px">Admin</code>
    </div>
    <div class="user__item">
        Пароль: <code style="margin-left: 5px">admin</code>
    </div>
</div>