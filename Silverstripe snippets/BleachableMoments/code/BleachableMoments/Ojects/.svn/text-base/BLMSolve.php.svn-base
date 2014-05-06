<?php
/**
 * BLMSolve Object
 * @author Luc at Clorox.com
 * @version $ID
 */
class BLMSolve extends DataObject {
    public static $db = array(
        'Name' => 'HTMLText',
        'Content' => 'HTMLText',
        'PublicationDate' => 'Date',
        'Author' => 'Text',
        'MomentNumber' => 'Int',
        'SolveSortOrder' => 'Int'
    );
    public static $has_one = array(
        'Image' => 'Image','BLMLandingPage'=>'BLMLandingPage'
    );
    public static $has_many = array('BLMoments'=>'BLMoment');
    public static $many_many = array();
    public static $belongs_many_many = array('BLMLaughLearnPage'=>'BLMLaughLearnPage');

    // Fields used for the display of info in the CSV and The grids
    static $summary_fields = array(
        'Name'
    );

    // Searchable fields
    static $searchable_fields = array('Name');
    //the cms fields
    public function getCMSFields() {

        $fields = parent::getCMSFields();

        $fields ->removeFieldFromTab('Root.Main','SolveSortOrder');
        $fields ->removeFieldFromTab('Root.Main','BLMLandingPageID');

        $fields -> addFieldToTab('Root.Main', new TextField('Name', 'Name'));

        //*****************  PublicationDate DATES
        $dateField = new DateField('PublicationDate');
        $dateField -> setConfig('showcalendar', true);
        $fields -> addFieldToTab('Root.Main', $dateField);

        $fields -> addFieldToTab('Root.Main', new TextField('Author', 'Author'));

        //***************** MOMENTS
        $fields -> addFieldToTab('Root.Main', new TextField('MomentNumber', 'MomentNumber'));

        $fields -> addFieldToTab('Root.Main', new HeaderField('BLMoments', 'BLMoments Associated with this solve'));

        $BLMField = new GridField('BLMoments', 'BLMoments', $this -> BLMoments(), GridFieldConfig_RelationEditor::create() -> addComponents(new GridFieldDeleteAction('unlinkrelation')));
        $fields -> addFieldToTab('Root.Main', $BLMField);


        $fields -> addFieldToTab('Root.Main', new HtmlEditorField('Content', 'Content'));

        $fields -> addFieldToTab('Root.Main', new UploadField('Image', 'Image'));

        return $fields;
    }


}
