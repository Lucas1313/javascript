<?php
/**
 * BLMEcard Object
 *
 * Purpose: Ecard
 *
 * @author Luc at Clorox.com
 * @version $ID
 */
class BLMEcard extends DataObject {
    public static $db = array(
        'Name' => 'HTMLText',
        'Content' => 'HTMLText',
        'PublicationDate' => 'Date',
        'SortOrderEcardsGallery' => 'Int',
        'TwitterCopy'=>'Text',
        'PinterestCopy'=>'Text'
    );
    public static $has_one = array(
        'Image' => 'Image',
        'BLMEcardsGalleryPage'=>'BLMEcardsGalleryPage'
    );
//    public static $has_many = array('BLMoments'=>'BLMoment');
    public static $many_many = array();
    public static $belongs_many_many = array();

    // Fields used for the display of info in the CSV and The grids
    static $summary_fields = array(
        'ID',
        'SortOrderEcardsGallery',
        'Name',
        'Content'
    );
    public static $default_sort = 'SortOrderEcardsGallery';
    // Searchable fields
    static $searchable_fields = array('Name');
    //the cms fields
    public function getCMSFields() {

        $fields = parent::getCMSFields();

        $fields -> addFieldToTab('Root.Main', new TextField('Name', 'Name'));

        //*****************  PublicationDate DATES

        $dateField = new DateField('PublicationDate');
        $dateField -> setConfig('showcalendar', true);
        $fields -> addFieldToTab('Root.Main', $dateField);


        $fields -> addFieldToTab('Root.Main', new HtmlEditorField('Content', 'Content'));

        //***************** Image
        $fields -> addFieldToTab('Root.Main', new UploadField('Image', 'Image'));


        //***************** SOCIAL MEDIAS
        $fields -> addFieldToTab('Root.Main', new HeaderField('SocialHeader', 'Social Medias customisation'));
        $fields -> addFieldToTab('Root.Main', new TextareaField('TwitterCopy'));
        $fields -> addFieldToTab('Root.Main', new TextareaField('PinterestCopy'));


        return $fields;

    }


}
