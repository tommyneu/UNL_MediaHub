<div class="three_col left">
<?php
if (isset($this->label) && !empty($this->label)) {
    UNL_MediaYak_Controller::setReplacementData('title', 'UNL | Media | '.$this->label);
    UNL_MediaYak_Controller::setReplacementData('breadcrumbs', '<ul> <li><a href="http://www.unl.edu/">UNL</a></li> <li><a href="'.UNL_MediaYak_Controller::getURL().'">Media Hub</a></li> <li>'.$this->label.'</li></ul>');
    echo '<h3>'.$this->label.'</h3>';
}

if (count($this->items)) {
    $pager_layout = new Doctrine_Pager_Layout($this->pager,
        new Doctrine_Pager_Range_Sliding(array('chunk'=>5)),
        UNL_MediaYak_Controller::getURL(null, array_merge($this->options, array('page'=>'{%page_number}'))));
    $pager_layout->setTemplate(' <a href="{%url}">{%page}</a> ');
    $pager_layout->setSelectedTemplate('{%page}');
    $pager_links = $pager_layout->display(null, true);
    ?>
        <ul class="medialist">
    
        <?php
        foreach ($this->items as $media) { ?>
            <li>
                <div><a href="<?php echo UNL_MediaYak_Controller::getURL($media); ?>"><img class="thumbnail" src="<?php echo UNL_MediaYak_Controller::$thumbnail_generator.urlencode($media->url); ?>" alt="Thumbnail preview for <?php echo $media->title; ?>" width="50" height="38" /></a></div>
                <h4><a href="<?php echo UNL_MediaYak_Controller::getURL($media); ?>"><?php echo htmlspecialchars($media->title); ?></a></h4>
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
        <em>Displaying <?php echo $this->first; ?> through <?php echo $this->last; ?> out of <?php echo $this->total; ?></em>
    <div class="pager_links"><?php echo $pager_links; ?></div>
<?php
} else {
    echo '<p>Sorry, no media could be found</p>';
}
?>
</div>