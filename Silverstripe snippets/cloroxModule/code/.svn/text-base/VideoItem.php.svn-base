<?php
/**
 * Class VideoItem DataObject
 * Definition: Video to be added to a page
 * @author Luc Martin lmartinatclorox.com
 * @version $Id
 */
Class VideoItem extends DataObject {

    public static $db = array(
        'Name' => 'Text',
        'Release_Date' => 'Date',
        'Removal_Date' => 'Date',
        'Headline' => 'HtmlText',
        'Subtitle' => 'HtmlText',
        'Youtube_Id' => 'Text'
    );

    public static $has_one = array(
        'Pre_Image' => 'Image',
        'Post_Image' => 'Image',
        'Thumbnail' => 'Image'

    );
    public static $belong_many_many = array('IcktionaryVideoPage'=>'IcktionaryVideoPage','BLMLaughLearnPage'=>'BLMLaughLearnPage','BLMHolidayPage'=>'BLMHolidayPage');

    public function getCMSFields() {

        $fields = parent::getCMSFields();

        //***************** Release / Expiration DATES
        $fields -> addFieldToTab('Root.Main', new HeaderField('DatesHeader', 'Release and Removal Dates:'));
        $dateField = new DateField('Release_Date');
        $dateField -> setConfig('showcalendar', true);
        $fields -> addFieldToTab('Root.Main', $dateField);

        $dateField = new DateField('Removal_Date');
        $dateField -> setConfig('showcalendar', true);
        $fields -> addFieldToTab('Root.Main', $dateField);

        //***************** Subtitle
        $fields -> addFieldToTab('Root.Main', new HeaderField('HeadlineSubtitleHeader', 'Headline, (if different than the Title) And Subtitle'));
        $fields -> addFieldToTab('Root.Main', new TextField('Headline'));
        $fields -> addFieldToTab('Root.Main', new TextAreaField('Subtitle'));

        //***************** Youtube
        $fields -> addFieldToTab('Root.Main', new HeaderField('youtubeHeader', 'Youtube Id (Usually the last part of a youtube link)'));

        $fields -> addFieldToTab('Root.Main', new TextField('Youtube_Id'));

        //***************** IMAGEs
        $fields -> addFieldToTab('Root.Main', new HeaderField('ImagesHeader', 'Images related to the video'));
        $fields -> addFieldToTab('Root.Main', new UploadField($name = 'Pre_Image', $title = 'Upload the Preview Image'));
        $fields -> addFieldToTab('Root.Main', new UploadField($name = 'Post_Image', $title = 'Upload the Image to show After the video view'));
        $fields -> addFieldToTab('Root.Main', new UploadField($name = 'Thumbnail', $title = 'Upload the Thumbnail Image'));

        return $fields;
    }
    /**
     * function relatedVideoPage
     * Description: Will generate a query using the name of the item
     * so if the item is videoname_full it will become videoname?play=full
     */
    public function relatedVideoPage(){
        return strtolower(str_replace('_', '?play=', $this->Name));
    }

    /**
     * function isActive
     * Definition: Will return true if the Today's date is within video release date and removal date
     */
    public function isActive()
    {
        // today
        $today = Date('U',strtotime('today'));
        // release date
        $releaseDate  = Date('U', strtotime($this -> Release_Date));
        // removal date
        $removalDate  = Date('U', strtotime($this -> Removal_Date));

        // test if today is in the range
        if($today >= $releaseDate && $today <= $removalDate){
            return true;
        }
        return false;
    }
}
