<?php
/*
 * Class SpecialOffer
 *
 * Describes the Model for a CommitementPage
 *
 * @author Luc Martin lmartin -at- clorox.com
 * @version $Id
 */
class SpecialOffer extends DataObject {
    static $db = array(
        'Release_Date' => 'Date',
        'Expiration_Date' => 'Date',
        'Expiration_Status_Class' => 'Text',
        'Title' => 'HtmlText',
        'Subtitle' => 'HtmlText',
        'Subtitle_Class' => 'Text',
        'Description' => 'HtmlText',
        'Legal_Text' =>'HtmlText',
        'CTA_Text' => 'Text',
        'CTA_Link' => 'Text',
        'CTA_Title' => 'Text',
        'SortOrder' => 'Int'
    );
    public static $default_sort='SortOrder';
    static $has_one = array('SpecialOffersPage' => 'SpecialOffersPage', 'Image'=>'Image');

    public function getCMSFields() {

        $fields = parent::getCMSFields();

        $fields -> removeFieldFromTab('Root', 'Content');
        $fields -> removeFieldFromTab('Root', 'Expiration_Status_Class');
        $fields -> removeFieldFromTab('Root', 'SpecialOffersPageID');

        //***************** Release / Expiration DATES
        $dateField = new DateField('Release_Date');
        $dateField -> setConfig('showcalendar', true);
        $fields -> addFieldToTab('Root.Main', $dateField);

        $dateField = new DateField('Expiration_Date');
        $dateField -> setConfig('showcalendar', true);
        $fields -> addFieldToTab('Root.Main', $dateField);

        //***************** Title CLASSES
        $fields -> addFieldToTab('Root.Main', new TextField('Title'));
        $fields -> addFieldToTab('Root.Main', new TextField('Subtitle'));
        $cssClassController = new CssClasses_Controller();
        $fields -> addFieldToTab('Root.Main', $cssClassController -> SpecialOffersTitleClasses('Subtitle_Class'));

        //***************** Description
        $fields -> addFieldToTab('Root.Main', new TextField('Description'));

        //***************** Legal
        $fields -> addFieldToTab('Root.Main', new TextField('Legal_Text'));

        //***************** IMAGE
        $fields -> addFieldToTab('Root.Main', new UploadField($name = 'Image', $title = 'Upload the Image'));

        //***************** CTA
        $fields -> addFieldToTab('Root.Main', new TextField('CTA_Title'));
        $fields -> addFieldToTab('Root.Main', new TextField('CTA_Text'));
        $fields -> addFieldToTab('Root.Main', new TextField('CTA_Link'));

        return $fields;

    }
    /**
     * Function calculateIsNew
     * Calculates the "New state" of a special offer
     * Uses the release date and a parameter set in "Days"
     *
     * Return String
     */
    public function calculateIsNew($is_New_delay) {

        $now = Date('U', strtotime('Now'));

        $release = Date('U', strtotime($this -> Release_Date));

        $is_new = $is_New_delay * 86400;

        if ($now - $release > $is_new) {
            return '';
        }
        return 'newOffer';
    }



     /**
     * Function calculateExpirationStatus
     * To be used in templates
     * Calculates the "Expiration date" of a special offer
     * Uses the Expiration date and a parameter set in "Days"
     *
     * @param $expiring_Warning //first warning units:  days
     * @param $ending_Warning // ending warning in days
     *
     * Return String default to active
     */
    public function calculateExpirationStatus($is_New_delay, $expiring_Warning , $ending_Warning) {
        if($this->calculateIsNew($is_New_delay) == 'newOffer'){
            return'newOffer';
        }
        $ret = 'active';
        $now = Date('U', strtotime('Now'));

        // days in seconds
        $ending = $ending_Warning * 86400;
        $expiring = $expiring_Warning * 86400;

        $offerExpiration = Date('U', strtotime($this -> Expiration_Date));

        if ($offerExpiration - $now <= $expiring) {
            $ret = 'expiring';
        }
        if ($offerExpiration - $now <= $ending) {
            $ret = 'ending';
        }
        if ($offerExpiration - $now < 0) {
            $ret = 'ended';
        }
        return $ret;
    }

}
