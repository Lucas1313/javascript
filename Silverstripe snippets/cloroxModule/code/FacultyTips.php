<?php
/*
 * Product
 *
 * Describes the Model for a Product
 *
 * @author Luc Martin lmartin -at- clorox.com
 * @version $Id: Product.php 22794 2013-06-05 20:23:55Z lmartin $
 *
 * Relationships:
 *
 * hasOne = Image, ProductPage, SingleProductPage, ProductCategoryPage, AlsoLikeItem
 * many-one = ProductSubCategories
 * many-many = FAQs, ProductBenefits, TagFeatures, TagNeed, TagType
 *
 */
class FacultyTips extends DataObject {
    public function init() {
        //$this -> updateTagFields();
    }

    static $db = array(
        'Title' => 'Text',
        'Display_Name' => 'Text', //The name cleaned up includes special characters
        'Code_Name' => 'Text', //The Code_Name, from Clorox API naming convention
        'ActiveStatus' => 'Boolean', // Is this Profile to be shown or not true / false
        'Description' => 'HTMLText', //A more detailled description
        
    );

    static $many_many = array(
        
        'FacultyMember' => 'Faculty', // Page where all the Classroom Faculty should be displayed
    );

    

    // Fields used for the display of info in the CSV and The grids
    static $summary_fields = array(
        'ID',
        'Title',
        'Code_Name',
        'Display_Name',
		'ActiveStatus',
		'Description'
    );

    // Searchable fields
    static $searchable_fields = array(
        'Display_Name',
        'Code_Name',
        'Title',
        'Description',
        'FacultyMember'
    );




    //the cms fields
    public function getCMSFields() {

        $fields = parent::getCMSFields();
        $this -> init();
		$fields -> removeFieldFromTab('Root.Main', 'Code_Name');
		
		
		//***************** DISPLAY NAME
        $fields -> addFieldToTab('Root.Main', new TextField('Display_Name'));
		       
        //***************** DISPLAY TITLE
        $fields -> addFieldToTab('Root.Main', new TextField('Title'));

        //***************** Faculty BIO Description
        $fields -> addFieldToTab('Root.Main', new TextareaField('Description'));
        return $fields;
    }

    /**
     * Method called before an Object is saved
     * Will synchronise the "specific uses for the product with the selected checkboxes"
     * @param none
     * @return void
     */
    public function onBeforeWrite() {

        // Manipulate data before editing
        //String cleanup and manipulation
        $strManipulator = new StringManipulator_Controller();

        // Clean up special characters
        $this -> Display_Name = $strManipulator -> cleanupSpecialChar($this -> Display_Name);
        $this -> Display_Name = htmlentities($this -> Display_Name);
        $this -> Display_Name = html_entity_decode($this -> Display_Name);

        // generate the Code_Name from the ProductName
        $this -> Code_Name = $strManipulator -> generatecodeName($this -> Name);

        // Make sure that the Name field is filled up (necessary for searches)
        if (empty($this -> name)) {
            $this -> name = $this -> Display_Name;
        }
        

        parent::onBeforeWrite();
    }


    public function Code_Name() {
        $strManipulator = new StringManipulator_Controller();
        $Code_Name = $strManipulator -> generateCodeName($this -> Title);
        if ($this -> Code_Name != $Code_Name) {
            $this -> Code_Name = $Code_Name;
            $this -> write();
        }
        return $this -> Code_Name;
    }

}
