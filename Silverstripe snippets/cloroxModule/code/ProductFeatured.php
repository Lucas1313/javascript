<?php
/*
 * ProductFatured
 *
 * Describes the Model for a ProductFeatured
 *
 * @author Luc Martin lmartin -at- clorox.com
 * @version $Id: ProductFeatured.php 18878 2013-02-23 09:21:31Z jware $
 *
 * Relationships:
 * hasOne = Product
 *              "Product" => "Product", // Associated Product
                'FullImage' => 'Image', // Full bleed image
                'ProductImage' => 'Image', // the Product Image
                'RoundImage_1' => 'Image', // the image in round mask #1 
                'RoundImage_2' => 'Image', // the image in round mask #2 
                'RoundImage_3' => 'Image', // the image in round mask #3
                'VideoImage' => 'Image', // the image for the Video Preview
 * many-one =
 * many-many =
 * belong-many-many =
 *
 */
class ProductFeatured extends DataObject {
 
    static $db = array(
        'Name' => 'Text', // The name of the Featured Product (used internally for search)
        'Publication_Date' => 'Date', // Optional publication date
        'Removal_Date' => 'Date', // Optional removal date
        'Template_Class' => 'Varchar', // The template to use for this featured product
        'Title_1' => 'Text', // The Main title for that featured product
        'Title_2' => 'Text', // The secondary title 
        'Subtitle_1' => 'Text', // The secondary title #1 for the Featured Product
        'Subtitle_2' => 'Text', // The secondary title #2 for the Featured Product
        'Subtitle_3' => 'Text', // The secondary title #3 for the Featured Product
        'Slogan_1' => 'HtmlText', // The slogan for the featured product Title or Image 1
        'Slogan_2' => 'HtmlText', // The slogan for the featured product Title or Image 2
        'Slogan_3' => 'HtmlText', // The slogan for the featured product Title or Image 3
        'Video_Url' => 'Text', // The URL for the Youtube video         
        'Video_Text' => 'Text', // The text that describes  the Youtube video 
        'Description' => 'HtmlText', // The description for the Featured Product
    );
    static $has_one = array(
        "Product" => "Product", // Associated Product
        'FullImage' => 'Image', // Full bleed image
        'ProductImage' => 'Image', // the Product Image
        'RoundImage_1' => 'Image', // the image in round mask #1 
        'RoundImage_2' => 'Image', // the image in round mask #2 
        'RoundImage_3' => 'Image', // the image in round mask #3
        'VideoImage' => 'Image', // the image for the Video Preview
    );

    public static $summary_fields = array(
        'Name' => 'Name',
        'Publication_Date' => 'Publication_Date',
        'Removal_Date' => 'Removal_Date',
        'Template_Class' => 'Template_Class',
        'Title_1' => 'Title_1',
        'Title_2' => 'Title_2',
        'Subtitle_1' => 'Subtitle_1',
        'Subtitle_2' => 'Subtitle_2',
        'Subtitle_3' => 'Subtitle_3',
        'Slogan_1' => 'Slogan_1',
        'Slogan_2' => 'Slogan_2',
        'Slogan_3' => 'Slogan_3',
        'Video_Url' => 'VideoUrl',
        'Video_Text' => 'VideoText',
        'Slogan_Secondary' => 'Slogan_Secondary',
        'Description' => 'Description',
    );
    public function getCMSFields() {
       
       $AllClasses = array( 
            'Full_Image'=>'Full_Image',
            'Basic_1'=>'Basic_1',
            'Basic_2'=>'Basic_2',
            'Three_Features'=>'Three_Features',
            'Video'=>'Video');
        
        $fields = parent::getCMSFields();
        $Publication_Date = new DateField('Publication_Date');
        $Publication_Date -> setConfig('showcalendar', true);
        
        $Removal_Date = new DateField('Removal_Date');
        $Removal_Date -> setConfig('showcalendar', true);
        
        $fields -> addFieldToTab('Root.Main', new ListboxField($name = 'Template_Class',$title = "Select Template",$source =$AllClasses));
        $fields -> addFieldToTab('Root.Main', new TextField('Name'));
        $fields -> addFieldToTab('Root.Main', new TextField('Title_1'));
        $fields -> addFieldToTab('Root.Main', new TextField('Title_2'));
        $fields -> addFieldToTab('Root.Main', new TextField('Title_3'));
        $fields -> addFieldToTab('Root.Main', new TextField('Subtitle_1'));
        $fields -> addFieldToTab('Root.Main', new TextField('Subtitle_2'));
        $fields -> addFieldToTab('Root.Main', new TextField('Subtitle_3'));
        $fields -> addFieldToTab('Root.Main', new TextField('Slogan_1'));
        $fields -> addFieldToTab('Root.Main', new TextField('Slogan_2'));
        $fields -> addFieldToTab('Root.Main', new TextField('Slogan_3'));
        

        $fields -> addFieldToTab('Root.Main', $Publication_Date);
        $fields -> addFieldToTab('Root.Main', $Removal_Date);
        
        $fields -> addFieldToTab('Root.Main', new TextField('Video_Url'));
        $fields -> addFieldToTab('Root.Main', new TextField('Video_Text'));
        
        $fields -> addFieldToTab('Root.Main', new HTMLEditorField('Description'));

        $fields -> addFieldToTab('Root.Main', new UploadField($name = 'FullImage', $title = 'Upload the Background Image'));

        $fields -> addFieldToTab('Root.Main', new UploadField($name = 'Image', $title = 'Upload the Product Image'));

        $fields -> addFieldToTab('Root.Main', new UploadField($name = 'VideoImage', $title = 'Upload the Image for the Video'));

        $fields -> addFieldToTab('Root.Main', new UploadField($name = 'RoundImage_1', $title = 'Upload the Round Image 1'));
        
        $fields -> addFieldToTab('Root.Main', new UploadField($name = 'RoundImage_2', $title = 'Upload the Round Image 2'));
        
        $fields -> addFieldToTab('Root.Main', new UploadField($name = 'RoundImage_3', $title = 'Upload the Round Image 3'));
        
        return $fields;
    }

}
