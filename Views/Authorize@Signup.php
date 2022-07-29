<?php
/**
 * @var array $data -> Prepared parameters
 */

?>

<h1>Registration</h1>

<hr>
<nav class="nav">
    <?php if ( $auth ): ?>
        <div class="nav__row">
            <a href="/"><button>Home</button></a> |
            <a href="/account"><button>Account</button></a> |
            <a href="/exit"><button>Exit</button></a>
        </div>
    <?php else: ?>
        <div class="nav__row">
            <a href="/login"><button>Log in</button></a> |
            <a href="/"><button>Home</button></a>
        </div>
    <?php endif; ?>
</nav>

<?php include $part['notify']; ?>

<?php if ( empty($auth) ): ?>
<form name="signup" method="post" action="/signup">
    <div class="edit">
    <div class="edit__item">
        <label for="username">Login</label>
        <input
            type="text"
            id="username"
            name="username"
            pattern="[a-zA-Z0-9-_]{3,30}"
            placeholder="Come up with a login"
            value="<?= !empty( $username ) ? \Base\General::in($username) : ''; ?>"
            required
        >
    </div>
    <div class="edit__item">
        <label for="password">Password</label>
        <input
            type="password"
            id="password"
            name="password"
            pattern="[a-zA-Z0-9-_.=+*#@$!?:]{4,50}"
            placeholder="Come up with a password"
            value="<?= !empty( $password ) ? \Base\General::in($password) : ''; ?>"
            required>
    </div>
    <div class="right mt-1">
        <button type="submit">Register</button>
    </div>
</form>
</div>
<?php else: ?>
<div class="notify success center footer">
    You are already registered!
</div>
<?php endif; ?>
