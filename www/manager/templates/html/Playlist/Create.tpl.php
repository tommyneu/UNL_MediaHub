<?php
    $title = $context->feed->title;
?>
<div class="dcf-pt-6 dcf-pb-6">
    <h1 class="dcf-txt-h2">
        <?php  echo $title; ?> Channel - User Manager
    </h1>

    <ul class="dcf-p-1 dcf-list-bare dcf-list-inline dcf-txt-xs dcf-bg-overlay-dark">
        <li class="dcf-m-0">
            <a class="dcf-btn dcf-btn-inverse-tertiary" href="<?php echo UNL_MediaHub_Controller::getURL(); ?>channels/<?php echo (int)$context->feed->id ?>">View Channel</a>
        </li>
        <li class="dcf-m-0">
            <a class="dcf-btn dcf-btn-inverse-tertiary" href="<?php echo UNL_MediaHub_Manager::getURL(); ?>?view=feedmetadata&amp;id=<?php echo (int)$context->feed->id ?>">Edit Channel</a>
        </li>
        <li class="dcf-m-0">
            <a class="dcf-btn dcf-btn-inverse-tertiary" href="<?php echo UNL_MediaHub_Manager::getURL(); ?>?view=feedstats&amp;feed_id=<?php echo (int)$context->feed->id ?>">View Channel Stats</a>
        </li>
        <li class="dcf-m-0">
            <a class="dcf-btn dcf-btn-inverse-tertiary" href="<?php echo UNL_MediaHub_Manager::getURL(); ?>?view=permissions&amp;feed_id=<?php echo (int)$context->feed->id ?>">Edit Channel Users</a>
        </li>
        <li class="dcf-m-0">
            <a class="dcf-btn dcf-btn-inverse-tertiary" href="<?php echo UNL_MediaHub_Manager::getURL(); ?>?view=createPlaylist&amp;feed_id=<?php echo (int)$context->feed->id ?>">Add New Playlist</a>
        </li>
    </ul>
</div>
