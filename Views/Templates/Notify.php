<?php if ( !empty($messageText) && !empty($messageStatus) ): ?>
    <div class="notify center <?= $messageStatus; ?>"><?= $messageText; ?></div>
<?php endif; ?>