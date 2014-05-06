<?php
/*
 * Class FlashGamePage
 * Describes the Model for a KidsCornerPage
 *
 * @author Luc Martin lmartin -at- clorox.com
 * @version $Id
 */
class FlashGamePage extends Page {
    static $db = array(
        'gameFiles'=>'Text',
        'gameWidth'=>'Int',
        'gameHeight'=>'Int'
        );

    public static $has_one = array(
        'headerImage'=>'Image'
        );

    public function getCMSFields() {

        $fields = parent::getCMSFields();

        $fields -> addFieldToTab('Root.Main', new HeaderField('pageHeader', 'Add a header image'));
        $fields -> addFieldToTab('Root.Main', new UploadField('headerImage', 'URL of image'));

        $fields -> addFieldToTab('Root.Main', new HeaderField('gameHeader', 'Add the location of the Flash Game like so: /directory/directory.swf'));
        $fields -> addFieldToTab('Root.Main', new TextField('gameFiles', 'URL of game'));

        $fields -> addFieldToTab('Root.Main', new TextField('gameWidth', 'Width in px'));
        $fields -> addFieldToTab('Root.Main', new TextField('gameHeight', 'Height in px'));

        $fields -> removeFieldFromTab('Root', 'Content');

        return $fields;
    }

}

class FlashGamePage_Controller extends Page_Controller {

    public function init() {
        Requirements::javascript("js/plugins/swfobject.js");
        parent::init();
    }

}