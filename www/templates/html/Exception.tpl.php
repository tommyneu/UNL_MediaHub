<?php
if (headers_sent() === FALSE && $code = $context->getCode()) {
    header('HTTP/1.1 '.$code);
    header('Status: '.$code);
}

switch ($code) {
    case 200:
        $title = 'Media Not Ready';
        break;

    case 404:
        $title = 'Media Not Found';
        break;

    case 403:
        $title = 'Access Denied!';
        break;

    default:
        $title = 'Whoops! Sorry, there was an error.';
        break;
}

?>

<script>
    window.addEventListener('inlineJSReady', function(e) {
        require(['wdn'], function(wdn) {
            wdn.initializePlugin('notice');
        });
    });
</script>
<div class="dcf-mt-8 dcf-mb-8 dcf-notice dcf-notice-warning" hidden data-no-close-button>
    <h2><?php echo $title; ?></h2>
    <div><?php echo UNL_MediaHub::escape($context->getMessage()); ?></div>
</div>
