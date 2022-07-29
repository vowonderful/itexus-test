<?php
/**
 * @var array $data -> Prepared parameters
 */
?>

<h1>Forbidden</h1>

<?php include $part['menu']; ?>
<?php include $part['notify']; ?>

<?php if ( $auth ): ?>
    <div class="notify error footer center">Not enough rights to view this page.</div>
<?php else: ?>
    <div class="notify error footer center">
        Access to this page is allowed<br>only for authorized users.<br><br>
        Please, <a href="/login">log in</a> or <a href="/signup">register</a>.
    </div>
<?php endif; ?>

