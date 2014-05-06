<?php
/*
 * ThreeRoundThreeColumnsPanel
 *
 * Describes the Model for a ThreeRoundThreeColumnsPanel
 *
 * @author Luc Martin lmartin -at- clorox.com
 * @version $Id: 
 *
 *
 */
class ThreeRoundThreeColumnsPanel extends DataObject {

    static $db = array(
        'Name' => 'Text', // The name of the Featured Product (used internally for search)
        'Bubble_Text'=>'Text',
        'Bubble_Id' => 'Varchar',
       // 'Bubble_Class' => 'Varchar',
        'Publication_Date' => 'Date', // Optional publication date
        'Removal_Date' => 'Date', // Optional removal date
        'Title' => 'Text',
        'Title_Class'=>'Varchar',
        'Subtitle_1' => 'Text', // The Main title for that featured product
        'Subtitle_2' => 'Text', // The secondary title
        'Subtitle_3' => 'Text', // The secondary title
        'Description_1' => 'Text', // The secondary title #1 for the Featured Product
        'Description_2' => 'Text', // The secondary title #2 for the Featured Product
        'Description_3' => 'Text', // The secondary title #3 for the Featured Product
       
        'CTA_Text_1' => 'Text',
        'CTA_Link_1' => 'Text',
        'CTA_Title_1' => 'Text',
        
        'CTA_Text_2' => 'Text',
        'CTA_Link_2' => 'Text',
        'CTA_Title_2' => 'Text',
        
        'CTA_Text_3' => 'Text',
        'CTA_Link_3' => 'Text',
        'CTA_Title_3' => 'Text',

    );
    static $has_one = array(

        'RoundImage_1' => 'Image', // the image in round mask #1
        'RoundImage_2' => 'Image', // the image in round mask #2
        'RoundImage_3' => 'Image', // the image in round mask #3
       
    );

    public static $summary_fields = array(
        'ID' => 'ID',
        'Name' => 'Name', // The name of the Featured Product (used internally for search)
        'Publication_Date' => 'Publication_Date', // Optional publication date
        'Removal_Date' => 'Removal_Date', // Optional removal date
        'Bubble_Id' => 'Bubble_Id',
        'Bubble_Text' => 'Bubble_Text',
        'Title' => 'Title_1', // The Main title for that featured product
       
        'Subtitle_1' => 'Subtitle_1', // The secondary title #1 for the Featured Product
        'Subtitle_2' => 'Subtitle_2', // The secondary title #2 for the Featured Product
        'Subtitle_3' => 'Text', // The secondary title #3 for the Featured Product
       
    
        'Description_1' => 'Description_1', // The  Description #1 
        'Description_2' => 'Description_2', // The  Description #2 
        'Description_3' => 'Description_3', // The  Description #2 
        'CTA_Text_1' => 'CTA_Text_1',
        'CTA_Link_1' => 'CTA_Link_1',
        'CTA_Title_1' => 'CTA_Title_1',
        
        'CTA_Text_2' => 'CTA_Text_2',
        'CTA_Link_2' => 'CTA_Link_2',
        'CTA_Title_2' => 'CTA_Title_2',
        
        'CTA_Text_3' => 'CTA_Text_3',
        'CTA_Link_3' => 'CTA_Link_3',
        'CTA_Title_3' => 'CTA_Title_3'
    );
    public function getCMSFields() {

        Requirements::javascript('js/cms/feature-panel.js');

        $fields = parent::getCMSFields();

        $Publication_Date = new DateField('Publication_Date');
        $Publication_Date -> setConfig('showcalendar', true);

        $Removal_Date = new DateField('Removal_Date');
        $Removal_Date -> setConfig('showcalendar', true);

        $AllClasses = array(
            'Full_Image' => 'Full Image',
            'Basic_Two_Column' => 'Basic 2col',
            'Bottom_Promo' => 'Bottom Promo',
            'Three_Features' => 'Three Features',
            'Video' => 'Video',
        );


        //***************** BUBBLE CLASSES
        $cssClassController = new CssClasses_Controller();
        $fields -> addFieldToTab('Root.Main', $cssClassController->bubbleClasses());

        //***************** NAME
        $fields -> addFieldToTab('Root.Main', new TextField('Name'));
        
        //***************** PUBLICATION/REMOVAL DATE
        $fields -> addFieldToTab('Root.Main', $Publication_Date);
        $fields -> addFieldToTab('Root.Main', $Removal_Date);
        
        //***************** TOP BUBBLE
        $fields -> addFieldToTab('Root.Main', new TextField('Bubble_Text'));
        $fields -> addFieldToTab('Root.Main', new TextField('Bubble_Id'));
        
        //***************** TITLE
        $fields -> addFieldToTab('Root.Main', new TextField('Title'));
        
         //***************** Title CLASSES
        $cssClassController = new CssClasses_Controller();
        $fields -> addFieldToTab('Root.Main', $cssClassController->titleClasses('Title_Class'));
        
        $fields -> addFieldToTab('Root.Main', new TextField('Subtitle_1'));
        $fields -> addFieldToTab('Root.Main', new TextField('Subtitle_2'));
        $fields -> addFieldToTab('Root.Main', new TextField('Subtitle_3'));
                 //***************** SLOGANS
     
        //***************** CTA
        $fields -> addFieldToTab('Root.Main', new TextField('CTA_Title_1'));
        $fields -> addFieldToTab('Root.Main', new TextField('CTA_Text_1'));
        $fields -> addFieldToTab('Root.Main', new TextField('CTA_Link_1'));
        
        $fields -> addFieldToTab('Root.Main', new TextField('CTA_Title_2'));
        $fields -> addFieldToTab('Root.Main', new TextField('CTA_Text_2'));
        $fields -> addFieldToTab('Root.Main', new TextField('CTA_Link_2'));
        
        $fields -> addFieldToTab('Root.Main', new TextField('CTA_Title_3'));
        $fields -> addFieldToTab('Root.Main', new TextField('CTA_Text_3'));
        $fields -> addFieldToTab('Root.Main', new TextField('CTA_Link_3'));

      
        //***************** Description 1
        $fields -> addFieldToTab('Root.Main', new TextField('Description_1'));
         $fields -> addFieldToTab('Root.Main', new UploadField($name = 'RoundImage_1', $title = 'Upload the Round Image 1'));

         //***************** Description 2
        $fields -> addFieldToTab('Root.Main', new TextField('Description_2'));
        $fields -> addFieldToTab('Root.Main', new UploadField($name = 'RoundImage_2', $title = 'Upload the Round Image 2'));

        //***************** Description 3
        $fields -> addFieldToTab('Root.Main', new TextField('Description_3'));
        $fields -> addFieldToTab('Root.Main', new UploadField($name = 'RoundImage_3', $title = 'Upload the Round Image 3'));
        
      
        
        return $fields;
    }

   

}
