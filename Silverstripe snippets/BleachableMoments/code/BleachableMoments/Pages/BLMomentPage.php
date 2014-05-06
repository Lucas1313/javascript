<?php
/**
 * Class BLMAnimatedGIFPage
 * Describes the Model for a BLMAnimatedGIFPage
 *
 *  Purpose: Animated GIF Page
 * @author James Billings james.billings -at- clorox.com
 * @version $Id
 */
class BLMomentPage extends BLMMasterPage {
    static $db = array(
        'Description' => 'HtmlText',
        'TwitterCopy'=>'Text',
        'PinterestCopy'=>'Text'
    );
    public function getCMSFields() {
        $fields = parent::getCMSFields();
        $fields->addFieldToTab('Root.Main', new TextareaField('Description','Description'));
        return $fields;
    }
}

class BLMomentPage_Controller extends BLMMasterPage_Controller {

    public function init() {
        //Requirements::javascript("js/pages/blm-moment-detail-page.js");
        parent::init();
    }

}