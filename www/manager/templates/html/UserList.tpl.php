<?php
    $feedID = (int)$_GET['feed_id'];
    $authUser = UNL_MediaHub_AuthService::getInstance()->getUser();
    if (!$context->options['feed']->userCanEdit($authUser)) {
	    throw new UNL_MediaHub_RuntimeException('You do not have permission to manage this channel.', 403);
    }
?>
<div class="dcf-pt-6 dcf-pb-6">
    <h1 class="dcf-txt-h2"><?php if (isset($context->options['feed'])) { echo UNL_MediaHub::escape($context->options['feed']->title); } ?> Channel - User Manager</h1>

    <ul class="dcf-p-1 dcf-list-bare dcf-list-inline dcf-txt-xs dcf-bg-overlay-dark">
        <li class="dcf-m-0">
            <a class="dcf-btn dcf-btn-inverse-tertiary" href="<?php echo UNL_MediaHub_Controller::getURL(); ?>channels/<?php echo $feedID; ?>">View Channel</a>
        </li>
        <li class="dcf-m-0">
            <a class="dcf-btn dcf-btn-inverse-tertiary" href="<?php echo UNL_MediaHub_Manager::getURL(); ?>?view=feedmetadata&amp;id=<?php echo $feedID; ?>">Edit Channel</a>
        </li>
        <li class="dcf-m-0">
            <a class="dcf-btn dcf-btn-inverse-tertiary" href="<?php echo UNL_MediaHub_Manager::getURL(); ?>?view=feedstats&amp;feed_id=<?php echo $feedID; ?>">View Channel Stats</a>
        </li>
    </ul>

    <div>
        <ul role="list" class="dcf-list-inline dcf-mb-6 dcf-d-flex dcf-flex-wrap dcf-flex-row dcf-gap-6" id="userList">
            <?php foreach ($context->items as $user):?>
            <li class="dcf-rounded dcf-relative unl-bg-lightest-gray dcf-m-0 dcf-p-0 dcf-mt-1">
                <?php $fullName = @UNL_Services_Peoplefinder::getFullName($user->uid); ?>
                <?php if($fullName): ?>
                    <img class="userList_profile_image dcf-p-2 dcf-w-max-100% dcf-h-max-100% dcf-w-auto dcf-h-auto" src="https://directory.unl.edu/avatar/<?php echo $user->uid; ?>/?s=800" />
                    <p class="userList_name dcf-txt-xs dcf-txt-center dcf-p-2 dcf-pt-0 dcf-m-0 dcf-lh-1">
                        <?php echo UNL_MediaHub::escape($fullName); ?>
                        <a class="uid dcf-d-block dcf-txt-xs dcf-pt-1" href="https://directory.unl.edu/people/<?php echo $user->uid; ?>" target="_blank">
                            <?php echo UNL_MediaHub::escape($user->uid) ?>
                        </a>
                    </p>
                <?php else: ?>
                    <img class="userList_profile_image dcf-p-2 dcf-w-max-100% dcf-h-max-100% dcf-w-auto dcf-h-auto" src="https://directory.unl.edu/images/default-avatar-800.jpg" />
                    <p class="userList_name dcf-txt-xs dcf-txt-center dcf-p-2 dcf-pt-0 dcf-m-0 dcf-lh-1">
                        <span class="dcf-d-inline-block unl-bg-scarlet unl-cream dcf-rounded dcf-p-1">Unknown User!</span>
                        <a class="uid dcf-d-block dcf-txt-xs dcf-pt-1" href="https://directory.unl.edu/people/<?php echo $user->uid; ?>" target="_blank">
                            <?php echo UNL_MediaHub::escape($user->uid) ?>
                        </a>
                    </p>
                <?php endif; ?>
                <div class="userList_delete_form dcf-absolute dcf-top-0 dcf-right-0">
                    <?php echo $savvy->render($user, 'DeleteUserForm.tpl.php'); ?>
                </div>
            </li>
            <?php endforeach ?>
        </ul>
        <form action="?view=newsroom" method="post" id="addUser" class="dcf-form">
            <input type="hidden" id="feed_id" name="feed_id" value="<?php echo $feedID; ?>" />
            <input type="hidden" id="__unlmy_posttarget" name="__unlmy_posttarget" value="feed_users" />
            <input type="hidden" name="<?php echo $controller->getCSRFHelper()->getTokenNameKey() ?>" value="<?php echo $controller->getCSRFHelper()->getTokenName() ?>" />
            <input type="hidden" name="<?php echo $controller->getCSRFHelper()->getTokenValueKey() ?>" value="<?php echo $controller->getCSRFHelper()->getTokenValue() ?>">
            <fieldset style="width: fit-content;">
                <legend>Add A New User</legend>
                <div class="dcf-form-group">
                    <label for="uid">My.UNL Username</label>
                    <input id="uid" name="uid" type="text" placeholder="hhusker1">
                </div>
                <div id="uid_error" class="dcf-d-none">
                    <p id="uid_error_text" class="unl-bg-scarlet unl-cream dcf-rounded dcf-p-3"></p>
                </div>
                <input class="dcf-btn dcf-btn-primary" type="submit" value="Add User">
            </fieldset>
        </form>
    </div>
    
    <script>
        const uid_element = document.getElementById('uid');
        const uid_error = document.getElementById('uid_error');
        const uid_error_text = document.getElementById('uid_error_text');

        uid_element.addEventListener('input', () => {
            if (uid_element.value.includes('@')) {
                uid_error.classList.remove('dcf-d-none');
                uid_error_text.innerHTML = 'My.UNL Username should not be an email.';
            } else {
                uid_error.classList.add('dcf-d-none');
                uid_error_text.innerHTML = '';
            }
        });
    </script>
</div>
