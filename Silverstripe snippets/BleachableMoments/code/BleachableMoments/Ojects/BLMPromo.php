<?php
/**
 * BLMPromo Object
 * @author Luc at Clorox.com
 * @version $ID
 */
class BLMPromo extends DataObject {
    public static $db = array(
        'Name' => 'HTMLText',
        'Content' => 'HTMLText',
        'PublicationDate' => 'Date',
        'YoutubeId'=>'Varchar(50)'
    );
    public static $has_one = array(
        'Image' => 'Image'
    );
    public static $has_many = array('BLMEcards'=>'BLMEcard');
    public static $many_many = array();
    public static $belongs_many_many = array('BLMoments'=>'BLMoment');

    // Fields used for the display of info in the CSV and The grids
    static $summary_fields = array(
        'ID',
        'Name'
    );

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

        $fields -> addFieldToTab('Root.Main', new TextField('YoutubeId', 'YoutubeId'));

        $fields -> addFieldToTab('Root.Main', new HtmlEditorField('Content', 'Content'));

        $fields -> addFieldToTab('Root.Main', new UploadField('Image', 'Image'));

        return $fields;
    }


}
