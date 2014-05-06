<?php

class CarousselTopItem extends DataObject {

    static $db = array(
        'Title' => 'Varchar',
        'Slogan' => 'Text',
        'Twitter_Name' => 'Text',
        'Twitter_Slogan' => 'Text',
        'Twitter_Link' => 'Text',
        'Product_Name' => 'Text',
        'Product_Description' => 'text'
    );
    static $belong_many_many = array("Welcome" => "Welcome");
    static $has_one = array(
        'productImage' => 'Image',
        'backgroundImage' => 'Image'
    );

    public static $summary_fields = array(

        'Title' => 'Title',
        'Slogan' => 'Slogan',
        'Product_Name' => 'Product_Name',
        'Product_Description' => 'Product_Description',
       
    );

    public function getCMSFields() {

        $fields = parent::getCMSFields();

        $fields -> addFieldToTab('Root.Main', new TextField('Product_Name'));
        $fields -> addFieldToTab('Root.Main', new TextField('Twitter_Name'));
        $fields -> addFieldToTab('Root.Main', new TextField('Twitter_Slogan'));
        $fields -> addFieldToTab('Root.Main', new TextField('Twitter_Link'));
        $fields -> addFieldToTab('Root.Main', new TextField('Product_Description'));

        $fields -> addFieldToTab('Root.Main', new UploadField($name = 'productImage', $title = 'Upload the Product Image'));

        return $fields;
    }

    function getPanelA() {
        return $this -> renderWith('PanelA');
    }

}
