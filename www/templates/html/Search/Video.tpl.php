<?php $itemList = $context->getItems(); ?>

<h2>Found <?php echo count($itemList); ?> video results for search "<?php echo UNL_MediaHub::escape($context->getQuery()); ?>"</h2>

<hr class="dcf-m-1">
<div class="dcf-d-flex dcf-flex-row dcf-flex-wrap dcf-jc-between dcf-ai-center">
    <form class="dcf-form" style="width: fit-content;">
        <div class="dcf-d-flex dcf-flex-row dcf-flex-wrap dcf-jc-center dcf-ai-center dcf-col-gap-3">
            <label for="sort-video-results">Sort</label>
            <select name="sort-video-results" id="sort-video-results" style="width: fit-content;">
                <option value="recent">Recent</option>
                <option value="alpha">A-Z</option>
                <option value="rev-alpha">Z-A</option>
                <option value="popular">Popular</option>
            </select>
        </div>
    </form>
    <div>
        <button class="dcf-btn dcf-btn-tertiary">
            <svg
                xmlns="http://www.w3.org/2000/svg"
                width="30"
                height="30"
                viewBox="0 0 24 24"
                class="dcf-w-6 dcf-h-6 dcf-d-block dcf-fill-current"
            >
                <path d="M11 11V0H1.5C.673 0 0 .673 0 1.5V11H11zM12 11h11V1.5
                    C23 .673 22.327 0 21.5 0H12V11zM11 12H0v9.5C0 22.327.673
                    23 1.5 23H11V12zM12 12v11h9.5c.827 0 1.5-.673 1.5-1.5V12H12z"></path>
                <g><path fill="none" d="M0 0H24V24H0z"></path></g>
            </svg>
        </button>
    </div>
</div>

<hr class="dcf-m-1 dcf-mb-6">

<ul role="list" class="dcf-grid-halves@sm dcf-grid-thirds@md dcf-grid-fourths@xl dcf-col-gap-vw dcf-row-gap-7 mh-media-list<?php echo $mediaListClass ?>">
    <?php foreach ($itemList->items as $media): ?>
        <li>
            <?php echo $savvy->render($media, 'Media/teaser.tpl.php'); ?>
        </li>
    <?php endforeach; ?>
</ul>