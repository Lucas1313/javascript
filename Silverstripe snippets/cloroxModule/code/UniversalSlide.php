<?php
/**
 * class UniversalSlide
 * Purpose: A flexible slide that can contain most a diversity of DataObjects
 * To be used in a Cycle slide show
 * @author Luc Martin at Clorox
 * @version $ID
 *
 */
class UniversalSlide extends DataObject {

    public static $db = array(
        'Name' => 'Text',
        'Display_Name' => 'HTMLText',
        'Subtitle' => 'HTMLText',
        'FlexibleContent' => 'HTMLText',
        'User_Click_Counter' =>'Int',
        'CTA_Class' => 'Varchar',
        'CTA_Text' => 'HtmlText',
        'CTA_Link' => 'HtmlText',
        'CTA_Title' => 'HtmlText',
        'Navigation_Text'=>'HTMLText'

    );

    public static $has_one = array(
        'FeaturePanel' => 'FeaturePanel',
        'IcktionaryItem' => 'IcktionaryItem',
        'Product' => 'Product',
        'PressItem' => 'PressItem',
        'VideoItem'=>'VideoItem',
        'CTA_Image' => 'Image',
        'Navigation_Image'=>'Image'
    );

    public $belong_many_many = array('UniversalSlideShow' => 'UniversalSlideShow');

    public function getCMSFields() {
        $fields = parent::getCMSFields();

        // Name
        $fields -> addFieldToTab('Root.Main', new HeaderField('NameHeader', 'Searchable Name</h3><p>For internal use only, please no Special Character</p><h3>'));
        $fields -> addFieldToTab('Root.Main', new TextField('Name'));

        // Display_Name
        $fields -> addFieldToTab('Root.Main', new HeaderField('Display_NameHeader', 'Display Name</h3><p>Will be rendered as h1 Tag<h3>'));
        $fields -> addFieldToTab('Root.Main', new TextField('Display_Name'));

        // Subtitle
        $fields -> addFieldToTab('Root.Main', new HeaderField('Subtitleheader', 'Subtitle'));
        $fields -> addFieldToTab('Root.Main', new TextField('Subtitle'));


        // FlexibleContent
        $fields -> addFieldToTab('Root.Main', new HeaderField('AssociatedObjectHeader', 'Associated dataObject </h3><p>(Use one of these drop down field to use a dataObject on that slide.)</p><h3>'));

        $field = new DropdownField('FeaturePanelID', 'FeaturePanel',FeaturePanel::get()->map('ID', 'Name'));
        $field->setEmptyString('(Select a Feature Panel)');
        $fields -> addFieldToTab('Root.Main',$field);

        $field = new DropdownField('IcktionaryItemID', 'IcktionaryItem',IcktionaryItem::get()->map('ID', 'Name'));
        $field->setEmptyString('(Select a IcktionaryItem)');
        $fields -> addFieldToTab('Root.Main',$field);

        $field =  new DropdownField('ProductID', 'Product', Product::get()->map('ID', 'Display_Name'));
        $field->setEmptyString('(Select a Product)');
        $fields -> addFieldToTab('Root.Main',$field);

        $field =  new DropdownField('PressItemID', 'PressItem', PressItem::get()->map('ID', 'Name'));
        $field->setEmptyString('(Select a PressItem)');
        $fields -> addFieldToTab('Root.Main',$field);

        $field =  new DropdownField('VideoItemID', 'VideoItem', VideoItem::get()->map('ID', 'Name'));
        $field->setEmptyString('(Select a Video Item)');
        $fields -> addFieldToTab('Root.Main',$field);

        // FlexibleContent
        $fields -> addFieldToTab('Root.Main', new HeaderField('FlexibleContentHeader', 'Flexible Content </h3><p>(Use this field to add some custom HTML content to the slide, <strong>please note that it is preferable to use one of the Data Object instead</strong>)</p><h3>'));
        $fields -> addFieldToTab('Root.Main', new HTMLEditorField('FlexibleContent'));

        //************************** CTA FAMILY
        // Display_Name
        $fields -> addFieldToTab('Root.Main', new HeaderField('CTA_Header', 'Add CTA to the slide</h3>'));
        $fields -> addFieldToTab('Root.Main', new TextField('CTA_Class'));
        $fields -> addFieldToTab('Root.Main', new TextField('CTA_Title'));
        $fields -> addFieldToTab('Root.Main', new TextField('CTA_Text'));
        $fields -> addFieldToTab('Root.Main', new TextField('CTA_Link'));
        $fields -> addFieldToTab('Root.Main', new UploadField('CTA_Image'));

        //************************** NAVIGATION
        $fields -> addFieldToTab('Root.Main', new TextField('Navigation_Text'));
        $fields -> addFieldToTab('Root.Main', new UploadField('Navigation_Image'));

        return $fields;
    }

}
