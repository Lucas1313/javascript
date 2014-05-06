<?php
class CausesAndEverythingWeLove extends DataObject {

    static $db = array(
        'Name' => 'HtmlText',
        'Title' => 'HtmlText',
        'Publication_Date' => 'Date',
        'Removal_Date' => 'Date',
        'Title_column_1' => 'HtmlText',
        'Slogan_column_1' => 'HtmlText',
        'CTA_column_1_Title' => 'HtmlText',
        'CTA_column_1' => 'HtmlText',
        'CTA_column_1_URL' => 'HtmlText',
        'Title_column_2' => 'HtmlText',
        'Slogan_column_2' => 'HtmlText',
        'CTA_column_2_Title' => 'HtmlText',
        'CTA_column_2' => 'HtmlText',
        'CTA_column_2_URL' => 'HtmlText',
        'Title_column_3' => 'HtmlText',
        'Slogan_column_3' => 'HtmlText',
        'CTA_column_3_Title' => 'HtmlText',
        'CTA_column_3' => 'HtmlText',
        'CTA_column_3_URL' => 'HtmlText'
    );

    static $belong_many_many = array("Welcome" => "Welcome");

    static $has_one = array(
        'Image_column_1' => 'Image',
        'Image_column_2' => 'Image',
        'Image_column_3' => 'Image'
    );

    public static $summary_fields = array(
        'Title' => 'Title',
        'Publication_Date' => 'Publication_Date',
        'Title_column_1' => 'Title_column_1',
        'Image_column_1' => 'Image_column_1',
        'Title_column_1' => 'Title_column_2',
        'Image_column_2' => 'Image_column_2',
        'Title_column_1' => 'Title_column_3',
        'Image_column_3' => 'Image_column_3'
    );

    public function getCMSFields() {
        $fields = parent::getCMSFields();

        $Publication_Date = new DateField('Publication_Date');
        $Publication_Date -> setConfig('showcalendar', true);

        $Removal_Date = new DateField('Removal_Date');
        $Removal_Date -> setConfig('showcalendar', true);
        $fields -> addFieldToTab('Root.Main', new HTMLEditorField('Title'));
        $fields -> addFieldToTab('Root.Main', $Publication_Date, 'Title');
        $fields -> addFieldToTab('Root.Main', $Removal_Date, 'Title');
        $fields -> addFieldToTab('Root.Main', new TextField('Title'), 'Title_column_1');

        //Column 1
        $fields -> addFieldToTab('Root.Main', new UploadField($name = 'Image_column_1', $title = 'Image_column_1'), 'Title_column_1');
        $fields -> addFieldToTab('Root.Main', new TextField('Title_column_1'), 'Slogan_column_1');
        $fields -> addFieldToTab('Root.Main', new TextareaField('Slogan_column_1'), 'CTA_column_1_Title');
        $fields -> addFieldToTab('Root.Main', new TextField('CTA_column_1_Title'), 'CTA_column_1');
        $fields -> addFieldToTab('Root.Main', new TextareaField('CTA_column_1'), 'CTA_column_1_URL');
        $fields -> addFieldToTab('Root.Main', new TextField('CTA_column_1_URL'), 'Title_column_2');

        //Column 2
        $fields -> addFieldToTab('Root.Main', new UploadField($name = 'Image_column_2', $title = 'Image_column_2'), 'Title_column_2');
        $fields -> addFieldToTab('Root.Main', new TextField('Title_column_2'), 'Slogan_column_2');
        $fields -> addFieldToTab('Root.Main', new TextareaField('Slogan_column_2'), 'CTA_column_2_Title');
        $fields -> addFieldToTab('Root.Main', new TextField('CTA_column_2_Title'), 'CTA_column_2');
        $fields -> addFieldToTab('Root.Main', new TextareaField('CTA_column_2'), 'CTA_column_2_URL');
        $fields -> addFieldToTab('Root.Main', new TextField('CTA_column_2_URL'), 'Title_column_3');

        //Column 3
        $fields -> addFieldToTab('Root.Main', new UploadField($name = 'Image_column_3', $title = 'Image_column_3'), 'Title_column_3');
        $fields -> addFieldToTab('Root.Main', new TextField('Title_column_3'), 'Slogan_column_3');
        $fields -> addFieldToTab('Root.Main', new TextareaField('Slogan_column_3'), 'CTA_column_3_Title');
        $fields -> addFieldToTab('Root.Main', new TextField('CTA_column_3_Title'), 'CTA_column_3');
        $fields -> addFieldToTab('Root.Main', new TextareaField('CTA_column_3'), 'CTA_column_3_URL');
        $fields -> addFieldToTab('Root.Main', new TextField('CTA_column_3_URL'));

        return $fields;
    }

    function getCausesAndEverythingWeLove() {
        return $this -> renderWith('CausesAndEverythingWeLove');
    }

}
