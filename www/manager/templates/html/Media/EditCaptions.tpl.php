<?php
// TODO: disable breadcrumbs since currently not supported in 5.0 App templates
//$controller->setReplacementData('breadcrumbs', '<ol> <li><a href="http://www.unl.edu/">UNL</a></li> <li><a href="'.UNL_MediaHub_Controller::getURL().'">MediaHub</a></li> <li><a href="'.UNL_MediaHub_Controller::getURL().'manager/">Manage Media</a></li> <li><a href="' . $context->media->getURL() .'">'.UNL_MediaHub::escape($context->media->title).'</a></li> <li>Edit Captions</li></ol>');
?>
<?php
    $revOrders = $context->getRevOrderHistory()->items;
    $hasRevOrders = count($revOrders) > 0;
?>

<div class="dcf-bleed dcf-pt-6 dcf-pb-6">
    <div class="dcf-wrapper">
        <h1>Manage Captions for: <?php echo UNL_MediaHub::escape($context->media->title) ?></h1>
        <a href="<?php echo UNL_MediaHub_Controller::getURL() . 'manager/?view=addmedia&id=' . (int)$context->media->id?>" class="dcf-btn dcf-btn-primary">Edit Media</a>
        <a href="<?php echo $context->media->getURL()?>" class="dcf-btn dcf-btn-primary">View Media</a>
    </div>
</div>

<?php $transcoding_job = $context->media->getMostRecentTranscodingJob(); ?>
<?php if ($transcoding_job && $transcoding_job->isPending()): ?>
    <?php echo $savvy->render($context, 'Feed/Media/transcoding_notice.tpl.php'); ?>
<?php endif; ?>

<div class="dcf-bleed unl-bg-lightest-gray dcf-pt-6 dcf-pb-6">
    <div class="dcf-wrapper">
        <h2>Order Captions With Your Cost Object Number</h2>
        <div class="dcf-grid dcf-col-gap-vw">
            <div class="dcf-col-100% dcf-col-67%-start@sm">
                <div class="important-notice">
                    <p>
                        We will manually caption this media for you. Some things to keep in mind when ordering captions:
                    </p>
                    <ul>
                        <li>
                            <strong>Important</strong>: captions cost $1.50 per video minute, rounded up.  Example: A 3:31 minute video would cost $5.28.
                        </li>
                        <li>
                            Orders are usually completed within 24 hours.
                        </li>
                        <li>
                            Orders can not be canceled.
                        </li>
                        <li>
                            If you need to edit captions that you ordered, please upload them to amara and customize them there. Once the customized captions are published on amara.org, use this page to pull them down to mediahub.
                        </li>
                        <li>
                            You will be billed the month after the order is completed. We estimate that to be <?php echo date('F j, Y', UNL_MediaHub_RevAPI::getEstimatedBillingDate()) ?>.
                        </li>
                    </ul>
                </div>
            </div>
            <div class="dcf-col-100% dcf-col-33%-end@sm">
                <div class="mh-caption-sidebar">
                    <?php if (!$context->hasPendingOrder()): ?>
                    <form id="caption_order" method="post" class="dcf-form">
                        <?php if ($duration = $context->media->findDuration()): ?>
                            <?php $estimate = sprintf("%01.2f", round($duration->getTotalSeconds()/60 * 1.50, 2)); ?>
                            <input type="hidden" name="media_duration" value="<?php echo UNL_MediaHub::escape($duration->getString()); ?>" />
                            <input type="hidden" name="estimate" value="<?php echo UNL_MediaHub::escape($estimate) ?>" />
                            <h3 class="clear-top">Caption your video for <strong>$<?php echo UNL_MediaHub::escape($estimate) ?>.</strong></h3>
                        <?php else: ?>
                            <p>
                                We were unable to find the duration of the video, and can not estimate the cost.
                            </p>
                        <?php endif; ?>
                        <ul class="dcf-list-bare">
                            <li>
                                <label>
                                    Cost Object Number
                                    <input type="text" name="cost_object" required />
                                </label>
                            </li>
                        </ul>
                        <input type="hidden" name="__unlmy_posttarget" value="order_rev" />
                        <input type="hidden" name="media_id" value="<?php echo (int)$context->media->id ?>" />
                        <input type="hidden" name="<?php echo $controller->getCSRFHelper()->getTokenNameKey() ?>" value="<?php echo $controller->getCSRFHelper()->getTokenName() ?>" />
                        <input type="hidden" name="<?php echo $controller->getCSRFHelper()->getTokenValueKey() ?>" value="<?php echo $controller->getCSRFHelper()->getTokenValue() ?>">
                        <?php
                            if ($hasRevOrders === TRUE) {
                                $confirmMessage = 'Captions have already been ordered for this video. Are you sure you want to submit another caption order?';
                            } else {
                                $confirmMessage = 'Orders can not be canceled. Are you sure you want to order captions?';
                            }
                        ?>
                        <input class="dcf-mb-4 dcf-btn dcf-btn-primary" type="submit" id="caption_submit_button" value="Order captions" onclick="return confirm('<?php echo $confirmMessage; ?>');">
                        <?php if ($hasRevOrders === TRUE): ?>
                        <p class="unl-font-sans"><?php echo \UNL\Templates\Icons::get(\UNL\Templates\Icons::ICON_ALERT, '{"size": 4}'); ?> Captions have already been ordered for this video.</p>
                        <?php endif; ?>
                        <p class="unl-font-sans"><?php echo \UNL\Templates\Icons::get(\UNL\Templates\Icons::ICON_ALERT, '{"size": 4}'); ?> Orders can not be canceled.</p>
                    </form>
                    <?php else: ?>
                    <p>Great news! There is an order already in the works.</p>
                    <?php endif; ?>
                </div>

            </div>
        </div>
    </div>
</div>

<div class="dcf-bleed dcf-pt-6 dcf-pb-6">
    <div class="dcf-wrapper">
    <h2>Self-Manage Captions With Amara</h2>
        <div class="dcf-grid dcf-col-gap-vw">
            <div class="dcf-col-100% dcf-col-67%-start@sm">
                <p>
                    <a href="http://amara.org">amara.org</a> is a free service which helps you caption videos. To caption your video you will need to do the following.
                </p>
                <ol>
                    <li>Go to amara.org and create/edit captions for the video.</li>
                    <li>Follow the instructions on amara.org to publish the new captions</li>
                    <li>Come back here, and click the button to 'pull captions from amara.org'</li>
                </ol>
            </div>
            <div class="dcf-col-100% dcf-col-33%-end@sm">
                <?php if($context->isTranscodingFinished()): ?>
                    <?php $edit_captions_url = $context->getEditCaptionsURL(); ?>
                    <?php if (!$edit_captions_url): ?>
                        <p>
                            An error has occurred trying to add media to Amara.
                            Please try again later or contact an administrator for help.
                        </p>
                    <?php else: ?>
                        <a class="dcf-btn dcf-btn-primary" href="<?php echo $context->getEditCaptionsURL(); ?>">Edit Captions on amara</a><br><br>
                        <form class="dcf-form" method="post">
                            <input type="hidden" name="__unlmy_posttarget" value="pull_amara" />
                            <input type="hidden" name="media_id" value="<?php echo (int)$context->media->id ?>" />
                            <input type="hidden" name="<?php echo $controller->getCSRFHelper()->getTokenNameKey() ?>" value="<?php echo $controller->getCSRFHelper()->getTokenName() ?>" />
                            <input type="hidden" name="<?php echo $controller->getCSRFHelper()->getTokenValueKey() ?>" value="<?php echo $controller->getCSRFHelper()->getTokenValue() ?>">
                            <input class="dcf-btn dcf-btn-primary" type="submit" value="Pull Captions from amara.org">
                        </form>
                    <?php endif ?>
                <?php else: ?>
                    <p>Please wait for your video to be optimized before captioning on Amara.</p>
                <?php endif; ?>

            </div>
        </div>
    </div>
</div>

<div class="dcf-bleed unl-bg-lightest-gray dcf-pt-6 dcf-pb-6">
    <div class="dcf-wrapper">
        <h2>Upload your own .vtt or .srt file</h2>
        <div class="dcf-grid dcf-col-gap-vw">
            <div class="dcf-col-100% dcf-col-50%-start@sm">
                <p>
                    When crafting captions for your video, it's crucial to ensure clarity,
                    accuracy, and accessibility. Begin by transcribing spoken content
                    faithfully, including relevant sounds and music cues.
                    Use concise and easily readable sentences to maintain viewer
                    engagement. Incorporate punctuation thoughtfully to convey
                    tone and pacing accurately. Avoid overcrowding the screen with
                    text and ensure proper timing to sync captions with audio.
                    Common mistakes to avoid include inaccurate transcriptions
                    and insufficient proofreading.
                </p>
            </div>
            <div class="dcf-col-100% dcf-col-50%-end@sm">
                <form class="dcf-form dcf-d-inline" method="post" enctype="multipart/form-data">
                    <input type="hidden" name="__unlmy_posttarget" value="upload_caption_file" />
                    <input type="hidden" name="media_id" value="<?php echo (int)$context->media->id ?>" />
                    <input
                        type="hidden"
                        name="<?php echo $controller->getCSRFHelper()->getTokenNameKey() ?>"
                        value="<?php echo $controller->getCSRFHelper()->getTokenName() ?>"
                    >
                    <input
                        type="hidden"
                        name="<?php echo $controller->getCSRFHelper()->getTokenValueKey() ?>"
                        value="<?php echo $controller->getCSRFHelper()->getTokenValue() ?>"
                    >
                    <fieldset>
                        <legend>Caption File Upload</legend>
                        <div class="dcf-form-group">
                            <label for="captions-language-select">
                                Language <small class="dcf-required">Required</small>
                            </label>
                            <select id="captions-language-select" name="language" required>
                                <?php $languages = array("Abkhazian","Afar","Afrikaans","Akan","Albanian","Amharic","Arabic","Aragonese","Armenian","Assamese","Avaric","Avestan","Aymara","Azerbaijani","Bambara","Bashkir","Basque","Belarusian","Bengali","Bislama","Bosnian","Breton","Bulgarian","Burmese","Catalan, Valencian","Chamorro","Chechen","Chichewa, Chewa, Nyanja","Chinese","Church Slavonic, Old Slavonic, Old Church Slavonic","Chuvash","Cornish","Corsican","Cree","Croatian","Czech","Danish","Divehi, Dhivehi, Maldivian","Dutch, Flemish","Dzongkha","English","Esperanto","Estonian","Ewe","Faroese","Fijian","Finnish","French","Western Frisian","Fulah","Gaelic, Scottish Gaelic","Galician","Ganda","Georgian","German","Greek, Modern (1453–)","Kalaallisut, Greenlandic","Guarani","Gujarati","Haitian, Haitian Creole","Hausa","Hebrew","Herero","Hindi","Hiri Motu","Hungarian","Icelandic","Ido","Igbo","Indonesian","Interlingua (International Auxiliary Language Association)","Interlingue, Occidental","Inuktitut","Inupiaq","Irish","Italian","Japanese","Javanese","Kannada","Kanuri","Kashmiri","Kazakh","Central Khmer","Kikuyu, Gikuyu","Kinyarwanda","Kirghiz, Kyrgyz","Komi","Kongo","Korean","Kuanyama, Kwanyama","Kurdish","Lao","Latin","Latvian","Limburgan, Limburger, Limburgish","Lingala","Lithuanian","Luba-Katanga","Luxembourgish, Letzeburgesch","Macedonian","Malagasy","Malay","Malayalam","Maltese","Manx","Maori","Marathi","Marshallese","Mongolian","Nauru","Navajo, Navaho","North Ndebele","South Ndebele","Ndonga","Nepali","Norwegian","Norwegian Bokmål","Norwegian Nynorsk","Sichuan Yi, Nuosu","Occitan","Ojibwa","Oriya","Oromo","Ossetian, Ossetic","Pali","Pashto, Pushto","Persian","Polish","Portuguese","Punjabi, Panjabi","Quechua","Romanian, Moldavian, Moldovan","Romansh","Rundi","Russian","Northern Sami","Samoan","Sango","Sanskrit","Sardinian","Serbian","Shona","Sindhi","Sinhala, Sinhalese","Slovak","Slovenian","Somali","Southern Sotho","Spanish, Castilian","Sundanese","Swahili","Swati","Swedish","Tagalog","Tahitian","Tajik","Tamil","Tatar","Telugu","Thai","Tibetan","Tigrinya","Tonga (Tonga Islands)","Tsonga","Tswana","Turkish","Turkmen","Twi","Uighur, Uyghur","Ukrainian","Urdu","Uzbek","Venda","Vietnamese","Volapük","Walloon","Welsh","Wolof","Xhosa","Yiddish","Yoruba","Zhuang, Chuang","Zulu"); ?>
                                <?php $languages_codes = array("ab","aa","af","ak","sq","am","ar","an","hy","as","av","ae","ay","az","bm","ba","eu","be","bn","bi","bs","br","bg","my","ca","ch","ce","ny","zh","cu","cv","kw","co","cr","hr","cs","da","dv","nl","dz","en","eo","et","ee","fo","fj","fi","fr","fy","ff","gd","gl","lg","ka","de","el","kl","gn","gu","ht","ha","he","hz","hi","ho","hu","is","io","ig","id","ia","ie","iu","ik","ga","it","ja","jv","kn","kr","ks","kk","km","ki","rw","ky","kv","kg","ko","kj","ku","lo","la","lv","li","ln","lt","lu","lb","mk","mg","ms","ml","mt","gv","mi","mr","mh","mn","na","nv","nd","nr","ng","ne","no","nb","nn","ii","oc","oj","or","om","os","pi","ps","fa","pl","pt","pa","qu","ro","rm","rn","ru","se","sm","sg","sa","sc","sr","sn","sd","si","sk","sl","so","st","es","su","sw","ss","sv","tl","ty","tg","ta","tt","te","th","bo","ti","to","ts","tn","tr","tk","tw","ug","uk","ur","uz","ve","vi","vo","wa","cy","wo","xh","yi","yo","za","zu"); ?>
                                <?php foreach ($languages as $index => $language): ?>
                                    <?php $selected = $language === 'English' ? 'selected="selected"' : ''; ?>
                                    <option value="<?php echo $languages_codes[$index]; ?>" <?php echo $selected; ?>>
                                        <?php echo $language; ?>
                                    </option>
                                <?php endforeach;?>
                            </select>
                        </div>
                        <div class="dcf-form-group">
                            <label for="captions-country-select"> Country (Optional)</label>
                            <select id="captions-country-select" name="country">
                                <option value="" selected="selected">N/A</option>
                                <?php $countries = array("Afghanistan","Åland Islands","Albania","Algeria","American Samoa","Andorra","Angola","Anguilla","Antarctica","Antigua and Barbuda","Argentina","Armenia","Aruba","Australia","Austria","Azerbaijan","Bahamas","Bahrain","Bangladesh","Barbados","Belarus","Belgium","Belize","Benin","Bermuda","Bhutan","Bolivia (Plurinational State of)","Bonaire, Sint Eustatius and Saba","Bosnia and Herzegovina","Botswana","Bouvet Island","Brazil","British Indian Ocean Territory","Brunei Darussalam","Bulgaria","Burkina Faso","Burundi","Cabo Verde","Cambodia","Cameroon","Canada","Cayman Islands","Central African Republic","Chad","Chile","China","Christmas Island","Cocos (Keeling) Islands","Colombia","Comoros","Congo","Congo, Democratic Republic of the","Cook Islands","Costa Rica","Côte d'Ivoire","Croatia","Cuba","Curaçao","Cyprus","Czechia","Denmark","Djibouti","Dominica","Dominican Republic","Ecuador","Egypt","El Salvador","Equatorial Guinea","Eritrea","Estonia","Eswatini","Ethiopia","Falkland Islands (Malvinas)","Faroe Islands","Fiji","Finland","France","French Guiana","French Polynesia","French Southern Territories","Gabon","Gambia","Georgia","Germany","Ghana","Gibraltar","Greece","Greenland","Grenada","Guadeloupe","Guam","Guatemala","Guernsey","Guinea","Guinea-Bissau","Guyana","Haiti","Heard Island and McDonald Islands","Holy See","Honduras","Hong Kong","Hungary","Iceland","India","Indonesia","Iran (Islamic Republic of)","Iraq","Ireland","Isle of Man","Israel","Italy","Jamaica","Japan","Jersey","Jordan","Kazakhstan","Kenya","Kiribati","Korea (Democratic People's Republic of)","Korea, Republic of","Kuwait","Kyrgyzstan","Lao People's Democratic Republic","Latvia","Lebanon","Lesotho","Liberia","Libya","Liechtenstein","Lithuania","Luxembourg","Macao","Madagascar","Malawi","Malaysia","Maldives","Mali","Malta","Marshall Islands","Martinique","Mauritania","Mauritius","Mayotte","Mexico","Micronesia (Federated States of)","Moldova, Republic of","Monaco","Mongolia","Montenegro","Montserrat","Morocco","Mozambique","Myanmar","Namibia","Nauru","Nepal","Netherlands, Kingdom of the","New Caledonia","New Zealand","Nicaragua","Niger","Nigeria","Niue","Norfolk Island","North Macedonia","Northern Mariana Islands","Norway","Oman","Pakistan","Palau","Palestine, State of","Panama","Papua New Guinea","Paraguay","Peru","Philippines","Pitcairn","Poland","Portugal","Puerto Rico","Qatar","Réunion","Romania","Russian Federation","Rwanda","Saint Barthélemy","Saint Helena, Ascension and Tristan da Cunha","Saint Kitts and Nevis","Saint Lucia","Saint Martin (French part)","Saint Pierre and Miquelon","Saint Vincent and the Grenadines","Samoa","San Marino","Sao Tome and Principe","Saudi Arabia","Senegal","Serbia","Seychelles","Sierra Leone","Singapore","Sint Maarten (Dutch part)","Slovakia","Slovenia","Solomon Islands","Somalia","South Africa","South Georgia and the South Sandwich Islands","South Sudan","Spain","Sri Lanka","Sudan","Suriname","Svalbard and Jan Mayen","Sweden","Switzerland","Syrian Arab Republic","Taiwan, Province of China","Tajikistan","Tanzania, United Republic of","Thailand","Timor-Leste","Togo","Tokelau","Tonga","Trinidad and Tobago","Tunisia","Türkiye","Turkmenistan","Turks and Caicos Islands","Tuvalu","Uganda","Ukraine","United Arab Emirates","United Kingdom of Great Britain and Northern Ireland","United States Minor Outlying Islands","United States of America","Uruguay","Uzbekistan","Vanuatu","Venezuela (Bolivarian Republic of)","Viet Nam","Virgin Islands (British)","Virgin Islands (U.S.)","Wallis and Futuna","Western Sahara","Yemen","Zambia","Zimbabwe"); ?>
                                <?php $country_codes = array("AF","AX","AL","DZ","AS","AD","AO","AI","AQ","AG","AR","AM","AW","AU","AT","AZ","BS","BH","BD","BB","BY","BE","BZ","BJ","BM","BT","BO","BQ","BA","BW","BV","BR","IO","BN","BG","BF","BI","CV","KH","CM","CA","KY","CF","TD","CL","CN","CX","CC","CO","KM","CG","CD","CK","CR","CI","HR","CU","CW","CY","CZ","DK","DJ","DM","DO","EC","EG","SV","GQ","ER","EE","SZ","ET","FK","FO","FJ","FI","FR","GF","PF","TF","GA","GM","GE","DE","GH","GI","GR","GL","GD","GP","GU","GT","GG","GN","GW","GY","HT","HM","VA","HN","HK","HU","IS","IN","ID","IR","IQ","IE","IM","IL","IT","JM","JP","JE","JO","KZ","KE","KI","KP","KR","KW","KG","LA","LV","LB","LS","LR","LY","LI","LT","LU","MO","MG","MW","MY","MV","ML","MT","MH","MQ","MR","MU","YT","MX","FM","MD","MC","MN","ME","MS","MA","MZ","MM","NA","NR","NP","NL","NC","NZ","NI","NE","NG","NU","NF","MK","MP","NO","OM","PK","PW","PS","PA","PG","PY","PE","PH","PN","PL","PT","PR","QA","RE","RO","RU","RW","BL","SH","KN","LC","MF","PM","VC","WS","SM","ST","SA","SN","RS","SC","SL","SG","SX","SK","SI","SB","SO","ZA","GS","SS","ES","LK","SD","SR","SJ","SE","CH","SY","TW","TJ","TZ","TH","TL","TG","TK","TO","TT","TN","TR","TM","TC","TV","UG","UA","AE","GB","UM","US","UY","UZ","VU","VE","VN","VG","VI","WF","EH","YE","ZM","ZW"); ?>
                                <?php foreach ($countries as $index => $country): ?>
                                    <option value="<?php echo $country_codes[$index]; ?>">
                                        <?php echo $country; ?>
                                    </option>
                                <?php endforeach;?>
                            </select>
                        </div>
                        <div class="dcf-form-group">
                            <label for="caption-file">Caption File <small class="dcf-required">Required</small></label>
                            <input
                                type="file"
                                id="caption-file"
                                name="caption_file"
                                accept=".vtt,.srt"
                                aria-describedby="caption-file-help"
                                required
                            >
                            <p class="dcf-form-help dcf-mb-0" id="caption-file-help">Must be of type .vtt or .srt</p>
                        </div>
                        <div class="dcf-form-group">
                            <label for="caption-comment">Comment</label>
                            <input type="text" id="caption-comment" name="caption_comment">
                        </div>
                        <div class="dcf-form-group">
                            <input class="dcf-btn dcf-btn-primary dcf-mt-1" type="submit" value="Upload">
                        </div>
                    </fieldset>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="dcf-bleed dcf-pt-6 dcf-pb-6">
    <div class="dcf-wrapper">
        <h2>Order history and status</h2>
        <p>View the current status of your orders</p>
        <table class="dcf-table dcf-table-bordered dcf-table-responsive">
            <thead>
                <tr>
                    <th scope="col">Order Number</th>
                    <th scope="col">Date of order</th>
                    <th scope="col">Requester</th>
                    <th scope="col">Status of order</th>
                    <th scope="col">Cost</th>
                    <th scope="col">Actions</th>
                </tr>
            </thead>
            <tbody>
            <?php foreach ($revOrders as $order): ?>
                <tr>
                    <td data-label="Order Number">
                        <?php echo (int)$order->id ?>
                    </td>
                    <td data-label="Date of order">
                        <?php echo UNL_MediaHub::escape($order->datecreated) ?>
                    </td>
                    <td data-label="Requester">
                        <?php echo UNL_MediaHub::escape($order->uid) ?>
                    </td>
                    <td data-label="Status of order">
                        <?php echo $order->status ?>
                        <?php if (UNL_MediaHub_RevOrder::STATUS_ERROR == $order->status): ?>
                            -- <?php echo UNL_MediaHub::escape($order->error_text) ?>
                        <?php endif; ?>
                    </td>
                    <td data-label="Cost">
                        $<?php echo UNL_MediaHub::escape($order->estimate) ?>
                    </td>
                    <td data-label="Actions">
                        <a href="<?php echo $order->getDetailsURL() ?>">view details</a>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>

        <h2 class="dcf-pt-4">Caption track history</h2>
        <p>
            You may copy any track, edit any copied track, and
            delete non-active copied/uploaded tracks. Editing tracks is limited
            to fixing typos, for more intensive edits you may need to download
            the caption file and edit in your caption editor of choice.
        </p>
        <!--  This form is for all the is active radio buttons in the table -->
        <form class="dcf-form dcf-d-inline" method="post" id="caption_active_form">
            <input type="hidden" name="__unlmy_posttarget" value="set_active_text_track" />
            <input type="hidden" name="media_id" value="<?php echo (int)$context->media->id ?>" />
            <input
                type="hidden"
                name="<?php echo $controller->getCSRFHelper()->getTokenNameKey() ?>"
                value="<?php echo $controller->getCSRFHelper()->getTokenName() ?>"
            >
            <input
                type="hidden"
                name="<?php echo $controller->getCSRFHelper()->getTokenValueKey() ?>"
                value="<?php echo $controller->getCSRFHelper()->getTokenValue() ?>"
            >
        </form>
        <table class="dcf-table dcf-table-bordered dcf-table-responsive">
            <thead>
            <tr>
                <th scope="col">ID</th>
                <th scope="col">Date of caption track</th>
                <th scope="col">Source</th>
                <th scope="col">Comments</th>
                <th scope="col">Files</th>
                <th scope="col">Actions</th>
            </tr>
            </thead>
            <tbody>
            <?php $text_tracks = $context->getTrackHistory()->items; ?>
                <?php foreach ($text_tracks as $track): ?>
                    <?php $is_active = $context->media->media_text_tracks_id === $track->id; ?>
                    <?php $is_copy = !empty($track->media_text_tracks_source_id); ?>
                    <?php $is_upload = $track->source === 'upload'; ?>
                    <tr>
                        <td data-label="ID of caption track">
                            <?php echo UNL_MediaHub::escape($track->id); ?>
                        </td>
                        <td data-label="Date of caption track">
                            <?php echo UNL_MediaHub::escape($track->datecreated); ?>
                        </td>
                        <td data-label="Source">
                            <?php if($is_copy): ?>
                                Copied from <?php echo ucwords(UNL_MediaHub::escape($track->source)); ?>
                            <?php else: ?>
                                <?php echo ucwords(UNL_MediaHub::escape($track->source)); ?>
                            <?php endif; ?>
                        </td>
                        <td data-label="Comments">
                            <?php echo UNL_MediaHub::escape($track->revision_comment) ?>
                        </td>
                        <td data-label="Files">
                            <ul>
                                <?php foreach ($track->getFiles()->items as $file): ?>
                                    <li>
                                        <a href="<?php echo $file->getURL() ?>&amp;download=1" rel="noopener" target="_blank"><?php echo UNL_MediaHub::escape($file->language) ?>.<?php echo $file->format ?></a>,
                                        <a href="<?php echo $file->getSrtURL() ?>&amp;download=1" rel="noopener" target="_blank"><?php echo UNL_MediaHub::escape($file->language) ?>.srt</a>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        </td>
                        <td data-label="Actions" class="dcf-txt-sm">
                            <div class="dcf-grid dcf-row-gap-3 dcf-ai-center">
                                <div class="dcf-col-100% dcf-col-75%-start@md">
                                    <form class="dcf-form dcf-d-inline" method="post">
                                        <input type="hidden" name="__unlmy_posttarget" value="copy_text_track_file" />
                                        <input
                                            type="hidden"
                                            name="media_id"
                                            value="<?php echo (int)$context->media->id ?>"
                                        >
                                        <input
                                            type="hidden"
                                            name="text_track_id"
                                            value="<?php echo (int)$track->id ?>"
                                        >
                                        <input
                                            type="hidden"
                                            name="<?php echo $controller->getCSRFHelper()->getTokenNameKey() ?>"
                                            value="<?php echo $controller->getCSRFHelper()->getTokenName() ?>"
                                        >
                                        <input
                                            type="hidden"
                                            name="<?php echo $controller->getCSRFHelper()->getTokenValueKey() ?>"
                                            value="<?php echo $controller->getCSRFHelper()->getTokenValue() ?>"
                                        >
                                        <input class="dcf-btn dcf-btn-primary dcf-mt-1" type="submit" value="Copy">
                                    </form>
                                    <?php if ($is_copy): ?>
                                        <a
                                            href="<?php
                                                echo UNL_MediaHub_Manager::getURL()
                                                    . '?view=editcaptiontrack&media_id='
                                                    . (int)$context->media->id
                                                    . '&track_id='
                                                    . (int)$track->id;
                                                ?>" class="dcf-btn dcf-btn-primary dcf-mt-1">Edit</a>
                                    <?php endif; ?>
                                    <?php if (($is_copy || $is_upload) && !$is_active): ?>
                                        <form class="dcf-form dcf-d-inline" method="post">
                                            <input
                                                type="hidden"
                                                name="__unlmy_posttarget"
                                                value="delete_text_track_file"
                                            >
                                            <input
                                                type="hidden"
                                                name="media_id"
                                                value="<?php echo (int)$context->media->id ?>"
                                            >
                                            <input
                                                type="hidden"
                                                name="text_track_id"
                                                value="<?php echo (int)$track->id ?>"
                                            >
                                            <input
                                                type="hidden"
                                                name="<?php echo $controller->getCSRFHelper()->getTokenNameKey() ?>"
                                                value="<?php echo $controller->getCSRFHelper()->getTokenName() ?>"
                                            >
                                            <input
                                                type="hidden"
                                                name="<?php echo $controller->getCSRFHelper()->getTokenValueKey() ?>"
                                                value="<?php echo $controller->getCSRFHelper()->getTokenValue() ?>"
                                            >
                                            <input
                                                class="dcf-btn dcf-btn-secondary dcf-mt-1"
                                                type="submit"
                                                value="Delete"
                                                onclick="return confirm('Are you sure you want to delete this track?');"
                                            >
                                        </form>
                                    <?php endif; ?>
                                </div>
                                <div class="dcf-input-radio dcf-col-100% dcf-col-25%-end@md">
                                    <?php $active_check = $is_active ? 'checked="checked"' : ''; ?>
                                    <input
                                        id="caption-active-radio-<?php echo (int)$track->id; ?>"
                                        form="caption_active_form"
                                        name="text_track_id"
                                        type="radio"
                                        value="<?php echo (int)$track->id; ?>"
                                        <?php echo $active_check; ?>
                                    >
                                    <label for="caption-active-radio-<?php echo (int)$track->id; ?>">Active</label>
                                </div>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <script defer>
            document.querySelectorAll('input[type="radio"][form="caption_active_form"]').forEach((radio_button) => {
                radio_button.addEventListener('input', () => {
                    document.getElementById('caption_active_form')?.submit();
                });
            });
        </script>
    </div>
</div>
<div class="dcf-bleed dcf-pt-6 dcf-pb-6">
    <div class="dcf-wrapper">
        <div>
            <h2>Get Help</h2>
            <p>
                If you have questions or comments, please use the 'Email Us' tab on this page or email <a href="mailto:mysupport@unl.edu">MySupport</a>.
            </p>
        </div>
    </div>
</div>
