<?php
class RatingsReviews extends DataObject {

    static $db = array(
        'Publication_Date' => 'Date',
        'Removal_Date' => 'Date',
        'Name' => 'Varchar',
        'Title' => 'Varchar',
        'Slogan' => 'Varchar',
        'Reviews_qty' => 'Varchar',
        'CTA' => 'Varchar',
        'CTA_URL' => 'Varchar',
    );
    static $belong_many_many = array("Welcome" => "Welcome");
    static $has_one = array('RatingsReviews_Image' => 'Image');

    public static $summary_fields = array(
        'Name' => 'Name',
        'Publication_Date' => 'Publication_Date',
        'Title' => 'Title',
        'Slogan' => 'Slogan',
        'RatingsReviews_Image' => 'RatingsReviews_Image'
    );

    public function getCMSFields() {
        $fields = parent::getCMSFields();
        $fields -> removeFieldFromTab('Root.Content.Main', 'Content');
        $Publication_Date = new DateField('Publication_Date');
        $Publication_Date -> setConfig('showcalendar', true);

        $Removal_Date = new DateField('Removal_Date');
        $Removal_Date -> setConfig('showcalendar', true);
        $fields -> addFieldToTab('Root.Main', $Publication_Date, 'Title');
        $fields -> addFieldToTab('Root.Main', $Removal_Date, 'Title');
        $fields -> addFieldToTab('Root.Main', new TextField('Name'), 'Title');
        $fields -> addFieldToTab('Root.Main', new TextField('Title'), 'Slogan');
        $fields -> addFieldToTab('Root.Main', new TextareaField('Slogan'), 'Reviews_qty');
        $fields -> addFieldToTab('Root.Main', new TextField('Reviews_qty'), 'CTA');
        $fields -> addFieldToTab('Root.Main', new TextareaField('CTA'));
        $fields -> addFieldToTab('Root.Main', new TextField('CTA_URL'));
        $fields -> addFieldToTab('Root.Main', new UploadField($name = 'RatingsReviews_Image', $title = 'Upload the Product Image'), 'Title');

        return $fields;
    }

    function getRatingsReviews() {
        return $this -> renderWith('RatingsReviews');
    }

}
