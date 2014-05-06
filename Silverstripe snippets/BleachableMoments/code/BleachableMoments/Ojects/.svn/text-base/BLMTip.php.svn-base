<?php
/**
 * BLMTip Object
 * @author Luc at Clorox.com
 * @version $ID
 */
class BLMTip extends DataObject {
    public static $db = array(
        'Name' => 'HTMLText',
        'Content' => 'HTMLText',
        'PublicationDate' => 'Date',
        'Author' => 'Text',
        'TipSortOrder' => 'Int'
    );
    public static $has_one = array(
        'Image' => 'Image',
        'BLMLandingPage' => 'BLMLandingPage'
    );
   // public static $has_many = array('BLMoments'=>'BLMoment');

    public static $many_many = array();
    public static $belongs_many_many = array();

    // Fields used for the display of info in the CSV and The grids
    static $summary_fields = array(
        'ID',
        'Name'
    );

    public static $default_sort='TipSortOrder';

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

        $fields -> addFieldToTab('Root.Main', new TextField('Author', 'Author'));

       // $BLMField = new GridField('BLMoments', 'BLMoments', $this -> BLMoments(), GridFieldConfig_RelationEditor::create() -> addComponents(new GridFieldDeleteAction('unlinkrelation')));
       // $fields -> addFieldToTab('Root.Main', $BLMField);

       // $fields -> addFieldToTab('Root.Main', new HTMLText('Content', 'Content'));

        $fields -> addFieldToTab('Root.Main', new UploadField('Image', 'Image'));

        return $fields;
    }


}
