<?php
class UNL_MediaHub_DBTests_BaseTestDataInstaller implements UNL_MediaHub_DBTests_MockTestDataInstallerInterface
{
    /**
     * This function should execute commands to install mock data to the test database.
     */
    public function install() {
        //Create users
        $user_a = new UNL_MediaHub_User();
        $user_a->uid = 'test_a';
        $user_a->save();

        $user_b = new UNL_MediaHub_User();
        $user_b->uid = 'test_b';
        $user_b->save();
        
        //Create some channels
        $feed_a = new UNL_MediaHub_Feed();
        $feed_a->title = 'test a';
        $feed_a->uidcreated = $user_a->uid;
        $feed_a->save();

        $element = new UNL_MediaHub_Feed_NamespacedElements_media();
        $element->feed_id = $feed_a->id;
        $element->element = 'title';
        $element->value   = $feed_a->title;
        $element->save();

        $feed_b = new UNL_MediaHub_Feed();
        $feed_b->title = 'test b';
        $feed_b->uidcreated = $user_b->uid;
        $feed_b->save();

        $element = new UNL_MediaHub_Feed_NamespacedElements_media();
        $element->feed_id = $feed_b->id;
        $element->element = 'title';
        $element->value   = $feed_b->title;
        $element->save();
        
        //Add some permissions
        $feed_a->addUser($user_a);
        $feed_b->addUser($user_b);
        
        //add some media
        $media_a = new UNL_MediaHub_Media();
        $media_a->url         = 'http://example.org/a.mov';
        $media_a->uidcreated  = $user_a->uid;
        $media_a->uidupdated  = $user_a->uid;
        $media_a->type        = 'video/mp4';
        $media_a->title       = 'Test Media A';
        $media_a->description = 'Test Media A Description';
        $media_a->save();

        $media_b = new UNL_MediaHub_Media();
        $media_b->url         = 'http://example.org/B.mov';
        $media_b->uidcreated  = $user_a->uid;
        $media_b->uidupdated  = $user_a->uid;
        $media_b->type        = 'audio/mp3';
        $media_b->title       = 'Test Media B';
        $media_b->description = 'Test Media B Description';
        $media_b->save();
        
        //Add media to channels
        $feed_a->addMedia($media_a);
        $feed_b->addMedia($media_b);
    }
}