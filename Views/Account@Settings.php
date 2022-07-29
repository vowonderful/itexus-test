<?php
/**
 * @var array $data -> Prepared parameters
 */
?>

<?php if ( !empty($data) && !empty($data['username']) &&
    \Base\General::isAuth() && \Base\General::isRole(Role::admin) ) : ?>
<h1>Edit Profile: <u><?= $data['username'] ?></u></h1>
<?php else: ?>
<h1>Edit Profile</h1>
<?php endif; ?>

<?php include $part['menu']; ?>
<?php include $part['notify']; ?>

<?php if ( !empty($data) ): ?>

<form name="edit_profile" method="post" action="/settings">
    <div class="edit">
        <div class="edit__item">
            <label for="name">Full Name:</label>
            <input
                    type="text"
                    id="name"
                    name="name"
                    pattern="[a-zA-Zа-яА-ЯёЁ0-9-()\' ]{2,40}"
                    placeholder="Enter full name..."
                    value="<?= !empty( $data['name'] ) ? \Base\General::out($data['name']) : ''; ?>"
            >
        </div>
        <div class="edit__item">
            <label for="birthday">Birthday:</label>
            <input
                    type="date"
                    id="birthday"
                    name="birthday"
                    placeholder="Enter birthday..."
                    value="<?= !empty( $data['birthday'] ) ? $data['birthday'] : ''; ?>"
            >
        </div>
        <div class="edit__item">
            <label for="country">Country:</label>
            <input
                    type="text"
                    id="country"
                    name="country"
                    pattern="[a-zA-Zа-яА-ЯёЁ0-9-().,\' ]{2,20}"
                    placeholder="Enter country..."
                    value="<?= !empty( $data['country'] ) ? \Base\General::out($data['country']) : ''; ?>"
            >
        </div>
        <div class="edit__item">
            <label for="email">Email:</label>
            <input
                    type="email"
                    id="email"
                    name="email"
                    placeholder="Enter email..."
                    value="<?= !empty( $data['email'] ) ? \Base\General::out($data['email']) : ''; ?>"
            >
        </div>
        <div class="edit__item password">
            <label for="password">Password:</label>
            <input
                    type="password"
                    id="password"
                    name="password"
                    pattern="[a-zA-Z0-9-_.=+*#@$!?:]{4,50}"
                    placeholder="Enter password..."
                    value=""
            >
            <small><i>* Enter a new password only if you want to change it.</i></small>
        </div>
    </div>
    <div class="btn-row">
        <button type="submit" class="btn__only">Update</button>
    </div>
</form>

<?php else: ?>
    <div class="notify error footer center">Failed to load data</div>
<?php endif; ?>

