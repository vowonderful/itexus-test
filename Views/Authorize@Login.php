<?php
/**
 * @var array $data -> Prepared parameters
 */

?>

<h1>Login</h1>

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
            <a href="/signup"><button>Sign up</button></a> |
            <a href="/"><button>Home</button></a>
        </div>
    <?php endif; ?>
</nav>

<?php include $part['notify']; ?>

<?php if ( empty($auth) ): ?>
<div class="edit">
    <form name="login" method="post" action="/login">
    <div class="edit__item">
        <label for="username">Login</label>
        <input
            type="text"
            id="username"
            name="username"
            pattern="[a-zA-Z0-9-_]{3,30}"
            placeholder="Your login..."
            value="<?= !empty( $data['username'] ) ? \Base\General::in($data['username']) : ''; ?>"
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
            placeholder="Enter password..."
            value="<?= !empty( $data['password'] ) ? \Base\General::in($data['password']) : ''; ?>"
            required>
    </div>

    <div class="right mt-1">
        <button type="submit">Log in</button>
    </div>
</form>
</div>
<?php else: ?>
<div class="notify success center footer">
    You are already logged in!
</div>
<?php endif; ?>
