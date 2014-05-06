<?php
class TipsAndTricks extends DataObject {

    static $db = array(
        'Publication_Date' => 'Date',
        'Removal_Date' => 'Date',
        'Name' => 'Varchar',
        'Title' => 'Varchar',
        'Slogan' => 'Varchar',
        'Title2' => 'Varchar',
        'Slogan2' => 'Varchar',
        'Trick1' => 'Varchar',
        'Trick1_visible_text' => 'Varchar',
        'Trick1Class' => 'Varchar',
        'Trick2' => 'Varchar',
        'Trick2_visible_text' => 'Varchar',
        'Trick2Class' => 'Varchar',
        'Trick3' => 'Varchar',
        'Trick3_visible_text' => 'Varchar',
        'Trick3Class' => 'Varchar',
        'CTA_Title' => 'Varchar',
        'CTA' => 'Varchar',
        'CTA_URL' => 'Varchar'
    );

    static $belong_many_many = array("Welcome" => "Welcome");

    static $has_one = array('TipTricks_Image' => 'Image');

    public static $summary_fields = array(
        'Name' => 'Name',
        'Publication_Date' => 'Publication_Date',
        'Title' => 'Title',
        'Slogan' => 'Slogan'
    );

    public function getCMSFields() {
        $fields = parent::getCMSFields();
        $Publication_Date = new DateField('Publication_Date');
        $Publication_Date -> setConfig('showcalendar', true);

        $Removal_Date = new DateField('Removal_Date');
        $Removal_Date -> setConfig('showcalendar', true);

        $fields -> addFieldToTab('Root.Main', new UploadField($name = 'TipTricks_Image', $title = 'TipTricks_Image'));
        $fields -> addFieldToTab('Root.Main', $Publication_Date, 'Title');
        $fields -> addFieldToTab('Root.Main', $Removal_Date, 'Title');
        $fields -> addFieldToTab('Root.Main', new TextField('Name'), 'Title');
        $fields -> addFieldToTab('Root.Main', new TextField('Title'), 'Slogan');
        $fields -> addFieldToTab('Root.Main', new TextareaField('Slogan'), 'Title2');
        $fields -> addFieldToTab('Root.Main', new TextField('Title2'), 'Slogan2');
        $fields -> addFieldToTab('Root.Main', new TextareaField('Slogan2'), 'Trick1');
        $fields -> addFieldToTab('Root.Main', new TextareaField('Trick1'), 'Trick1_visible_text');
        $fields -> addFieldToTab('Root.Main', new TextareaField('Trick1_visible_text'), 'Trick1Class');
        $fields -> addFieldToTab('Root.Main', new TextField('Trick1Class'), 'Trick2_visible_text');
        $fields -> addFieldToTab('Root.Main', new TextareaField('Trick2_visible_text'), 'Trick2Class');
        $fields -> addFieldToTab('Root.Main', new TextareaField('Trick2'), 'Trick2Class');
        $fields -> addFieldToTab('Root.Main', new TextField('Trick2Class'), 'Trick3_visible_text');
        $fields -> addFieldToTab('Root.Main', new TextareaField('Trick3_visible_text'), 'Trick3Class');
        $fields -> addFieldToTab('Root.Main', new TextareaField('Trick3'), 'Trick3Class');
        $fields -> addFieldToTab('Root.Main', new TextField('Trick3Class'), 'CTA_Title');
        $fields -> addFieldToTab('Root.Main', new TextField('CTA_Title'), 'CTA');
        $fields -> addFieldToTab('Root.Main', new TextField('CTA'), 'CTA_URL');
        $fields -> addFieldToTab('Root.Main', new TextField('CTA_URL'));

        return $fields;
    }

    function getTipsAndTricks() {
        return $this -> renderWith('TipsAndTricks');
    }

}
