<form class="dcf-form" action="" method="post">
    <input name="uid" type="hidden" value="<?php echo UNL_MediaHub::escape($context->uid); ?>" />
    <input name="delete" type="hidden" value="delete" />
    <input type="hidden" id="feed_id" name="feed_id" value="<?php echo (int)$_GET['feed_id']; ?>" />
    <input type="hidden" id="__unlmy_posttarget" name="__unlmy_posttarget" value="feed_users" />
    <input type="hidden" name="<?php echo $controller->getCSRFHelper()->getTokenNameKey() ?>" value="<?php echo $controller->getCSRFHelper()->getTokenName() ?>" />
    <input type="hidden" name="<?php echo $controller->getCSRFHelper()->getTokenValueKey() ?>" value="<?php echo $controller->getCSRFHelper()->getTokenValue() ?>">
    <button class="dcf-btn dcf-btn-primary dcf-circle dcf-h-6 dcf-w-6 dcf-p-0 dcf-m-0 dcf-d-flex dcf-jc-center dcf-ai-center" type="submit" value="Remove" title="Remove">
        <svg class="dcf-h-4 dcf-w-4 dcf-fill-current dcf-d-block"
            width="24" height="24" viewBox="0 0 24 24" focusable="false" aria-hidden="true">
        <path style="rotate: 45deg; transform-origin: center center;"
            d="M1,13h22c0.6,0,1-0.4,1-1c0-0.6-0.4-1-1-1H1c-0.6,0-1,0.4-1,1C0,12.6,0.4,13,1,13z"/>
        <path style="rotate: -45deg; transform-origin: center center;"
            d="M1,13h22c0.6,0,1-0.4,1-1c0-0.6-0.4-1-1-1H1c-0.6,0-1,0.4-1,1C0,12.6,0.4,13,1,13z"/>
        </svg>
    </button>
</form>
