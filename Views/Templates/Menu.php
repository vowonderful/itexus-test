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
            <a href="/"><button>Home</button></a> |
            <a href="/signup"><button>Sign up</button></a> |
            <a href="/login"><button>Log in</button></a>
        </div>
    <?php endif; ?>
</nav>