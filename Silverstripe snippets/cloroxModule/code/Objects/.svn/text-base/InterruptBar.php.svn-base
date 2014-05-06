<?php
/*
 * CLTPanel
 *
 * Describes the Model for a InterruptBar
 *
 * @author Kody.Smith -at- clorox.com
 * @version $Id: InterruptBar.php 21419 2014-01-24 23:01:24Z ksmith $
 *
 *
 */
class InterruptBar extends DataObject {

    static $db = array(
        'Name' => 'HTMLText',
        'Release_Date' => 'Date',
        'Override_Class' => 'Text',
        'CTA_Link' => 'Text',
        'CTA_Text' => 'HTMLText',
        //'InterruptBar_Class' => 'Text'

    );

    static $has_one = array(
        'CTA_Image' => 'Image',

    );
    static $belong_many_many = array('LaughPage'=>'LaughPage');
    static $searchable_fields = array(
        'Name',
        'CTA_Text',
    );
    public static $summary_fields = array(
        'ID' => 'ID',
        'Release_Date' => 'Release_Date',
       // 'InterruptBar_Class' => 'InterruptBar_Class',
        'Override_Class' => 'Override_Class',
        'CTA_Link' => 'CTA_Link',
        'CTA_Text' => 'CTA_Text',
    );
	
    public function getCMSFields() {


        $cssClasses_Controller = new CssClasses_Controller();

        $fields = parent::getCMSFields();



        //***************** Release DATE
        $fields -> addFieldToTab('Root.Main', new HeaderField('releaseDateHeader', 'Release date of the Tip / Article'));

        $dateField = new DateField('Release_Date');
        $dateField -> setConfig('showcalendar', true);
        $fields -> addFieldToTab('Root.Main', $dateField);

        //***************** STYLE AND CLASSES
        $fields -> addFieldToTab(
        	'Root.Main',
        	new HeaderField(
        		'stylesHeader',
        		'Styles and Classes'
			)
		);

        $fields -> addFieldToTab('Root.Main', new HeaderField('nameHeader', 'Override_Class allows you to add additional classes to a bar'));
		$fields -> addFieldToTab('Root.Main', new TextField('Override_Class','Override_Class'));

        //***************** NAME
        $fields -> addFieldToTab('Root.Main', new HeaderField('nameHeader', 'Searchable Name'));

        $fields -> addFieldToTab('Root.Main', new TextField('Name'));


        //***************** IMAGES
        $fields -> addFieldToTab('Root.Main', new HeaderField('Images', 'Main Image and the half width Image'));
        $fields -> addFieldToTab('Root.Main', new UploadField('CTA_Image'));

        //***************** CTA Fields
        $fields -> addFieldToTab('Root.Main', new HeaderField('CTAHeader','Call To Action'));
        $fields -> addFieldToTab('Root.Main', new TextField('CTA_Text','CTA Text'));
        $fields -> addFieldToTab('Root.Main', new TextField('CTA_Link','CTA Link'));


        return $fields;
    }



}
