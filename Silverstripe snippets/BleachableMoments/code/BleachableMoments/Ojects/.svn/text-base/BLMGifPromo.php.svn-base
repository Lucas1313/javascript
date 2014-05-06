<?php
/**
 * BLMGifPromo Object
 *
 * Purpose:Animated Gif's
 * Animated GIF promo box. Entire box should be clickable,
 * depending on format. Will link to the animated GIF associated
 * with the current phase.
 *
 * @author Luc at Clorox.com
 * @version $ID
 */
class BLMGifPromo extends DataObject {
    public static $db = array(
        'Name' => 'HTMLText',
        'Content' => 'HTMLText',
        'PublicationDate' => 'Date',
        'Author' => 'Text',
        'CTALink'=>'Text',
        'CTATitle'=>'Text',
        'CTATitleLine2'=>'Text'
    );
    public static $has_one = array(
        'LargeImage' => 'Image',
        'SmallImage' => 'Image'
    );
//    public static $has_many = array('BLMoments'=>'BLMoment');
    public static $many_many = array();
    public static $belongs_many_many = array(
//	'BLMoments'=>'BLMoments'
	);

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
		$fields -> removeFieldFromTab('Root','SmallImage');
		$fields -> removeFieldFromTab('Root','LargeImage');
        $fields -> addFieldToTab('Root.Main', new TextField('Name', 'Name'));

        //*****************  PublicationDate DATES
        $dateField = new DateField('PublicationDate');
        $dateField -> setConfig('showcalendar', true);
        $fields -> addFieldToTab('Root.Main', $dateField);

        //***************** Author
        $fields -> addFieldToTab('Root.Main', new TextField('Author', 'Author'));

        //***************** Content
        $fields -> addFieldToTab('Root.Main', new HtmlEditorField('Content', 'Content'));

        //***************** Image
        $fields -> addFieldToTab('Root.Main', new UploadField('SmallImage', 'Small Panel Image'));

        //***************** Image
        $fields -> addFieldToTab('Root.Main', new UploadField('LargeImage', 'Large Panel Image'));

        //***************** CTA
        $fields -> addFieldToTab('Root.Main', new TextField('CTATitle', 'CTATitle'));
		$fields -> addFieldToTab('Root.Main', new TextField('CTATitleLine2', 'CTATitleLine2'));
		
        $fields -> addFieldToTab('Root.Main', new TextField('CTALink', 'CTALink'));

         //***************** SOCIAL MEDIAS
        $fields -> addFieldToTab('Root.Main', new TextareaField('TwitterCopy'));
        $fields -> addFieldToTab('Root.Main', new TextareaField('PinterestCopy'));


        return $fields;
    }


}
