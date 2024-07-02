<div class="dcf-bleed">
    <div class="dcf-wrapper dcf-pt-8 dcf-pb-8">
        <div class="dcf-tabs" id="search-results" hidden>
            <h1 class="dcf-sr-only">Tab Group No UL</h1>
            <ul>
                <li><a href="#video-results">Videos</a></li>
                <li><a href="#audio-results">Audio</a></li>
                <li><a href="#channel-results">Channels</a></li>
            </ul>
            <section id="video-results">
                <?php echo $savvy->render($context->getSearchVideo(), 'Search/Video.tpl.php'); ?>
            </section>
            <section id="audio-results">
                <?php echo $savvy->render($context->getSearchAudio(), 'Search/Audio.tpl.php'); ?>
            </section>
            <section id="channel-results">
                <?php echo $savvy->render($context->getSearchChannel(), 'Search/Channel.tpl.php'); ?>
            </section>
        </div>
    </div>
</div>


<script>
    window.addEventListener('inlineJSReady', function() {
        let search_results = document.getElementById('search-results')
        search_results.addEventListener('ready', () => {
            search_results.removeAttribute('hidden');
        });
        WDN.initializePlugin('tabs');
    }, false);
</script>
