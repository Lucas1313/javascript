<?php
/*
 * Class PressItem
 *
 * Describes the Model for a PressItem
 *
 * @author Luc Martin lmartin -at- clorox.com
 * @version $Id
 */

class PressItem extends DataObject {
    static $db = array(
        'Release_Date' => 'Date',
        'Expiration_Date' => 'Date',
        'Expiration_Status_Class' => 'Text',
        'Title' => 'HtmlText',
        'Name' => 'HtmlText',
        'Subtitle' => 'HtmlText',
        'PressReleaseType_Class' => 'Text',
        'Description' => 'HtmlText',
        'Content' =>'HtmlText',
        'CTA_Text' => 'HtmlText',
        'CTA_Link' => 'Text',
        'CTA_Title' => 'Text',
        'Sources'=>'HtmlText'
    );

    static $has_one = array('PressReleasePage' => 'PressReleasePage', 'Image'=>'Image', 'PressItemPage'=>'PressItemPage');

    static $summary_fields = array(
        'Release_Date' ,
        'Expiration_Date',
        'Expiration_Status_Class',
        'Title',
        'Name',
        'Subtitle',
        'PressReleaseType_Class',
        'Description',
        'Content',
        'CTA_Text',
        'CTA_Link',
        'CTA_Title',
        'Sources'
    );

    public function getCMSFields() {


        $fields = parent::getCMSFields();

         if(!empty($this->Name)){
           $this->generatePage();
        }

        $fields -> removeFieldFromTab('Root', 'Expiration_Status_Class');
        $fields -> removeFieldFromTab('Root', 'PressReleasePageID');

        //***************** Release / Expiration DATES
        $dateField = new DateField('Release_Date');
        $dateField -> setConfig('showcalendar', true);
        $fields -> addFieldToTab('Root.Main', $dateField);

        $dateField = new DateField('Expiration_Date');
        $dateField -> setConfig('showcalendar', true);
        $fields -> addFieldToTab('Root.Main', $dateField);

        //***************** Press Release Class
        $cssClassController = new CssClasses_Controller();
        $fields -> addFieldToTab('Root.Main', $cssClassController -> PressReleaseTitleClasses('PressReleaseType_Class'));

        //***************** Title CLASSES
        $fields -> addFieldToTab('Root.Main', new TextField('Name'));
        $fields -> addFieldToTab('Root.Main', new TextField('Title'));
        $fields -> addFieldToTab('Root.Main', new TextField('Subtitle'));
        $fields -> addFieldToTab('Root.Main', new HTMLEditorField('Content'));


        //***************** Description
        $fields -> addFieldToTab('Root.Main', new TextField('Description'));

        //***************** Legal
        $fields -> addFieldToTab('Root.Main', new TextField('Legal_Text'));

        //***************** Sources
        $fields -> addFieldToTab('Root.Main', new TextField('Sources'));

        //***************** IMAGE
        $fields -> addFieldToTab('Root.Main', new UploadField($name = 'Image', $title = 'Upload the Image'));

        //***************** CTA
        $fields -> addFieldToTab('Root.Main', new TextField('CTA_Title'));
        $fields -> addFieldToTab('Root.Main', new TextField('CTA_Text'));
        $fields -> addFieldToTab('Root.Main', new TextField('CTA_Link'));

        return $fields;

    }

    /**
     * function GeneratePage
     * Method that generates a Ick page if it doesn't exists.
     */
    public  function generatePage(){

        $alreadyExistingPage = PressItemPage::get()->filter('Title',$this->Name);

        foreach ($alreadyExistingPage as $key => $value) {
            if($value->Title == $this->Name){
                return;
            }
        }
        $page = new PressItemPage();
        $page->Title = $this->Name;

        $parentPage = PressReleasePage::get()->first();


        $parentId = $parentPage->ID;
        $page->setParent( $parentId);

        $page->write();
        $page->doPublish( );

        $page->PressItem()->add($this);
        $this->PressItemPage()->add($page);
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
    /*
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

     /*
     * Function americanDate
     * To be used to convert date to "American" format
     *
     * */
     public function americanDate($date) {
        $return = Date('F d, Y',strtotime($date));
        return $return;
     }


}
