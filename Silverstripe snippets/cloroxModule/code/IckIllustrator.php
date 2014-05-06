<?php
/*
 * IckIllustrator
 *
 * Describes the Model for a IcktionaryItem
 *
 * @author Luc Martin lmartin -at- clorox.com
 * @version $Id: IckIllustrator.php 20330 2013-03-22 19:23:21Z lmartin $
 *
 * Relationships:
 *
 * hasOne = SingleIckPage, Image, AlsoLikesItem
 * many-one = ProductSubCategories
 * many-many = TagGeneral
 * Belong Many Many = Welcome
 */
class IckIllustrator extends DataObject {

    static $db = array(
        'Name' => 'Text', // The name of the ick
        'Display_Name' => 'Text', // the display name of the ick
        'Slogan' => 'Text', // the spelling
        'Definition' => 'Text', // the definition of the ick
        'See_Artist_Link' => 'Text', // the text for the artist link
        'SortOrder'=>'Int',
        'Exclude_From_Menu'=>'Boolean'
    );
    
   
    static $belong_many_many = array();

    static $has_one = array(
        'Image' => 'Image',     
    );
        
    static $many_many = array('IcktionaryItem' => 'IcktionaryItem');
    
    static $summary_fields = array(
        'Name' , // The name of the ick
        'Display_Name' , // the display name of the ick
        'Slogan' , // the spelling
        'Definition', // the definition of the ick
        'See_Artist_Link' , // the text for the artist link
        'SortOrder',
        'Exclude_From_Menu'
       );
    
    public function getCMSFields() {
               
        $fields = parent::getCMSFields();
        
        $fields -> removeFieldsFromTab('Root', array('TagNeed'));
        
        $fields -> removeFieldFromTab('Root.Main', 'Content');
        // remove the content field

        /** General info **/
        $fields -> addFieldToTab('Root.Main', new TextField('Name'));
        
        // the name of the Ick (Title)
        $fields -> addFieldToTab('Root.Main', new TextField('Display_Name'));
        
        // The name to display on the page
        $fields -> addFieldToTab('Root.Main', new TextField('Slogan'));
        
        // the spelling
        $fields -> addFieldToTab('Root.Main', new TextareaField('Definition'));
       
        /** Artist Credentials **/
        
         // Link to artist page
        $fields -> addFieldToTab('Root.Main', new TextField('See_Artist_Link'));
        
        /** The image for the Ick **/
        $fields -> addFieldToTab('Root.Main', new UploadField($name = 'Image', $title = 'Upload the Author Image'));
       
        return $fields;
    }

    public function contributorId(){
        return $this->ID;
    }
 
}
