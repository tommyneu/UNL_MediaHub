<?php
if (isset($context->label) && !empty($context->label)) {
    UNL_MediaYak_Controller::setReplacementData('title', 'UNL | Media | '.$context->label);
    UNL_MediaYak_Controller::setReplacementData('breadcrumbs', '<ul> <li><a href="http://www.unl.edu/">UNL</a></li> <li><a href="'.UNL_MediaYak_Controller::getURL().'">Media Hub</a></li> <li>'.$context->label.'</li></ul>');
    echo '<h3>'.$context->label.'</h3>';
}

if (count($context->items)) {
    $pager_layout = new UNL_MediaYak_List_PagerLayout($context->pager,
        new Doctrine_Pager_Range_Sliding(array('chunk'=>5)),
        UNL_MediaYak_Controller::getURL(null, array_merge($context->options, array('page'=>'{%page_number}'))));
    $pager_links = $pager_layout->display(null, true);
    ?>
    <h3 class="sec_header" style="margin-top:10px;">Media in this Channel</h3>
        <ul class="medialist">
    
        <?php
        foreach ($context->items as $media) { ?>
            <li>
                <a href="<?php echo UNL_MediaYak_Controller::getURL($media); ?>"><img class="thumbnail" src="<?php echo UNL_MediaYak_Controller::$thumbnail_generator.urlencode($media->url); ?>" alt="Thumbnail preview for <?php echo $media->title; ?>" width="100" height="76" /></a>
                <h3><a href="<?php echo UNL_MediaYak_Controller::getURL($media); ?>"><?php echo htmlspecialchars($media->title); ?></a></h3>
                <?php
                if ($element = UNL_MediaYak_Feed_Media_NamespacedElements_itunes::mediaHasElement($media->id, 'subtitle')) {
                    echo '<h5 class="itunes_subtitle">'.$element->value.'</h5>';
                }
                $summary = $media->description;
                if ($element = UNL_MediaYak_Feed_Media_NamespacedElements_itunes::mediaHasElement($media->id, 'summary')) {
                    $summary .= '<span class="itunes_summary">'.$element->value.'</span>';
                }
                if (strlen($summary) >= 250) {
                    $summary = substr($summary, 0, 250).'&hellip;';
                }
                $summary = strip_tags($summary, '<a><img>');
                $summary = str_replace('Related Links', '', $summary);
                ?>
                <p><?php echo $summary; ?></p>
            </li>
        <?php  
        } ?>
        </ul>
        <em>Displaying <?php echo $context->first; ?> through <?php echo $context->last; ?> out of <?php echo $context->total; ?></em>
        <?php echo $pager_links; ?>
<?php
} else {
    echo '<p>Sorry, no media could be found</p>';
}
?>