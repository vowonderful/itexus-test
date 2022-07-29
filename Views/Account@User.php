<?php
/**
 * @var array $data -> Prepared parameters
 */

use Base\General;

?>

<?php if ( !empty($data) && !empty($data['username']) ): ?>
<h1>
    <?php if ( $data['user_id'] === \Base\General::getID() && !empty($pageParams['canonical']) ): ?>
        Hello, <?= $data['username'] ?>!
        <?php if ( $data['status'] !== 'blocked' ) : ?>
        <span class="btn-block">
            <a href="/settings"
               title="Change profile information and account settings?"
            >
                Edit profile
            </a>
        </span>
        <?php endif; ?>
    <?php else: ?>
        <?= $data['username'] ?>
    <?php endif; ?>
    <?php if ( !empty($data['status']) && !empty($_SESSION['role']) &&
        $data['status'] === 'active' && General::isRole(\Role::admin) &&
        $data['user_id'] !== \Base\General::getID() ):
    ?>
    <span class="btn-block">
        <a href="/account/<?= $data['user_id'] ?>/block" title="Block this user?">
            Block
        </a>
    </span>
    <?php elseif ( !empty($data['status']) && !empty($_SESSION['role']) &&
        $data['status'] === 'blocked' && General::isRole(\Role::admin) &&
        $data['user_id'] !== \Base\General::getID() ):
        ?>
    <span class="btn-block unblock">
        <a href="/account/<?= $data['user_id'] ?>/unblock" title="Unlock this user?">
            Unlock
        </a>
    </span>
    <?php endif; ?>
</h1>
<?php else: ?>
<h1>User Profile</h1>
<?php endif; ?>

<?php include $part['menu']; ?>
<?php include $part['notify']; ?>

<?php if ( !empty($data) ): ?>

    <?php if ( !empty($data['status']) && $data['status'] === 'active' || General::isRole(\Role::admin) ): ?>

        <?php if ( !empty($data['status']) && $data['status'] === 'blocked' &&
            General::isRole(\Role::admin) && $data['user_id'] !== \Base\General::getID() ): ?>
            <div class="notify error footer center">
                This user is blocked!
                <br><br>
                <a href="/account/<?= $data['user_id'] ?>/unblock"
                   title="The user will be instantly unblocked">
                    Unblock <?= $data['username'] ?>?
                </a>
            </div>
        <?php endif; ?>

        <div class="user">
            <div class="user__item title">
                <span class="username">
                    <?= !empty($data['name']) ? $data['name'] : '[Name not specified]' ?>
                </span>
                <?php if ( !empty($data['balance']) && General::isRole(\Role::admin) ): ?>
                <span class="balance">
                    <?= $data['balance'] ?>
                </span>
                <?php endif; ?>
            </div>
            <?php if (
                isset($data['balance'] ) && !empty($pageParams['canonical']) ||
                General::isRole(\Role::admin)
            ): ?>
            <br>
            <?php endif; ?>
            <?php if ( isset($data['age'] ) ): ?>
            <div class="user__item">
                Age: <i><?= $data['age'] ?></i>
            </div>
            <?php endif; ?>
            <?php if ( !empty($data['country'] ) ): ?>
            <div class="user__item">
                Country: <i><?= $data['country'] ?></i>
            </div>
            <?php endif; ?>
            <?php if ( !empty($data['email'] ) ): ?>
            <div class="user__item">
                Email: <i><a href="mailto:<?= $data['email'] ?>" target="_blank"><?= $data['email'] ?></a></i>
            </div>
            <?php if ( !empty($data['birthday_last']) && !empty($pageParams['canonical']) ) : ?>
                <div class="user__item red center birthday mt-2"><?= $data['birthday_last'] ?></div>
            <?php endif; ?>
            <?php endif; ?>
        </div>
    <?php else: ?>
    <div class="notify error center footer">
        This user is blocked, information is unavailable.
    </div>
    <?php endif; ?>

<?php endif; ?>
