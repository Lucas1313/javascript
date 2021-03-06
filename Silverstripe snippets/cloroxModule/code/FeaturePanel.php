<?php
/*
 * FeaturePanel
 *
 * Describes the Model for a FeaturePanel
 *
 * @author Luc Martin lmartin -at- clorox.com
 * @version $Id: FeaturePanel.php 29240 2014-02-18 22:54:47Z lmartin $
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
class FeaturePanel extends DataObject {

    static $db = array(
        'Name' => 'Text', // The name of the Featured Product (used internally for search)
        'Bubble_Text' => 'HTMLText',
        'Bubble_Id' => 'Text',
        'Bubble_Class' => 'Text',
        'Publication_Date' => 'Date', // Optional publication date
        'Removal_Date' => 'Date', // Optional removal date
        'Title' => 'HTMLText', // The Main title for that featured product
        'Headline' => 'HTMLText', // The Main title for that featured product
        'Subtitle' => 'HTMLText',

        'Title_1' => 'HTMLText', // The Main title for that featured product
        'Title_2' => 'HTMLText', // The secondary title
        'Title_3' => 'HTMLText', // The secondary title
        'Title_4' => 'HTMLText', // The secondary title

        'Subtitle' => 'HTMLText', // The secondary title #1 for the Featured Product
        'Subtitle_1' => 'HTMLText', // The secondary title #1 for the Featured Product
        'Subtitle_2' => 'HTMLText', // The secondary title #2 for the Featured Product
        'Subtitle_3' => 'HTMLText', // The secondary title #3 for the Featured Product
        'Subtitle_4' => 'HTMLText', // The secondary title #3 for the Featured Product

        'Slogan_1' => 'HTMLText', // The slogan for the featured product Title or Image 1
        'Slogan_2' => 'HTMLText', // The slogan for the featured product Title or Image 2
        'Slogan_3' => 'HTMLText', // The slogan for the featured product Title or Image 3
		'Slogan_4' => 'HTMLText', // The slogan for the featured product Title or Image 3

        'Video_Url' => 'Text', // The URL for the Youtube video
        'Video_Text' => 'Text', // The text that describes  the Youtube video

        'Description' => 'HTMLText', // The description for the Featured Product
        'Description_1' => 'HTMLText', // The description for the Featured Product
        'Description_2' => 'HTMLText', // The description for the Featured Product
        'Description_3' => 'HTMLText', // The description for the Featured Product
        'Description_4' => 'HTMLText', // The description for the Featured Product

        'Featured_List' => 'HTMLText',
        'Feature_1' => 'HTMLText', // The  title #1 for the Feature 1
        'Feature_2' => 'HTMLText', // The  title #2 for the Feature 2
        'Feature_3' => 'HTMLText', // The  title #2 for the Feature 3
		'Feature_1_link' => 'HTMLText', // The link for the title #1 ofr the Feature 1
		'Feature_2_link' => 'HTMLText', // The link for the title #1 ofr the Feature 2
		'Feature_3_link' => 'HTMLText', // The link for the title #1 ofr the Feature 3
		'Feature_4_link' => 'HTMLText', // The link for the title #1 ofr the Feature 4

        'Feature_Description_1' => 'HTMLText', // The  Description #1 for the Feature 1
        'Feature_Description_2' => 'HTMLText', // The  Description #2 for the Feature 2
        'Feature_Description_3' => 'HTMLText', // The  Description #2 for the Feature 3
		'Feature_Description_4' => 'HTMLText', // The  Description #2 for the Feature 4

        'Social_Media_Class' => 'Text',
        'Social_Title' => 'Text',
        'Social_Media_Text' => 'HTMLText',
        'Social_Media_Link' => 'HTMLText',
        'Social_Media_Subtitle' => 'HTMLText',
        'Social_Media_Hashtag' => 'HTMLText',

        'SocialSharingDestination_URL' => 'HTMLText',
        'SocialSharingDestination_Title' => 'HTMLText',
        'SocialSharingDestination_Description' => 'HTMLText',

        'CTA_Class' => 'Text',
        'CTA_Text' => 'HTMLText',
        'CTA_Link' => 'HTMLText',
        'CTA_Title' => 'HTMLText',

        'CTA_Text_2' => 'HTMLText',
        'CTA_Link_2' => 'HTMLText',
        'CTA_Title_2' => 'HTMLText',

        'CTA_Text_3' => 'HTMLText',
        'CTA_Link_3' => 'HTMLText',
        'CTA_Title_3' => 'HTMLText',

		'CTA_Text_4' => 'HTMLText',
        'CTA_Link_4' => 'HTMLText',
        'CTA_Title_4' => 'HTMLText',

        'Title_Class' => 'Text',
		'Title_Knockout_Color' => 'Text',
        'Title1_Class' => 'Text',
        'Title2_Class' => 'Text',
        'Title3_Class' => 'Text',
        'Title4_Class' => 'Text',


        // CLASSES
        'Template_Class' => 'Text', // The template to use for this featured product
        'Background_Class' => 'Text',
        'Square_Buttons_Color_Class' => 'Text',
        'Circle_Button_Bg_Classes' => 'Text',
        'SortOrder' => 'Int',
        'SortOrderHistoricalSlideshow' => 'Int',
        'SortOrderOneHundredYearPage' => 'Int',
        'Entire_Panel_Clickable' => 'Boolean'
    );
    static $has_one = array(
        'ErrorPage' => 'ErrorPage',
        'CommitmentPage' => 'CommitmentPage',
        'HeadlineImage' => 'Image', // Full bleed image
        'FullImage' => 'Image', // Full bleed image
        'FeatureImage' => 'Image', // the Product Image
        'RoundImage_1' => 'Image', // the image in round mask #1
        'RoundImage_2' => 'Image', // the image in round mask #2
        'RoundImage_3' => 'Image', // the image in round mask #3
        'RoundImage_4' => 'Image', // the image in round mask #4
        'VideoImage' => 'Image', // the image for the Video Preview
        'CroppedImage' => 'Image',
        'BleachTruthPage' => 'BleachTruthPage',
        'OneHundredYearsPage' => 'OneHundredYearsPage'
    );
    static $has_many = array('SlideTopItem' => 'SlideTopItem');

    static $belong_many_many = array(
        'CausesPage' => 'CausesPage',
        'RegistrationPage' => 'RegistrationPage',
        'LaughPage' => 'LaughPage',
        'PanelPage' => 'PanelPage'
        );

    static $many_many = array(
        'Product' => 'Product',
        'OurHistoryPage' => 'OurHistoryPage',
        'ContactCloroxPage' => 'ContactCloroxPage',
        'Welcome' => 'Welcome',
        'ProductsNewPage' => 'ProductsNewPage',
        'OurStoryPage' => 'OurStoryPage',
        'GenericPage' => 'GenericPage',
        'SmartTubeTechnologyPage' => 'SmartTubeTechnologyPage',
        'ClassroomsPledgePage' => 'ClassroomsPledgePage'
    );

    static $searchable_fields = array('Name');

    public static $summary_fields = array(
        'ID' => 'ID',
        'SortOrderLaughPagePanel' => 'SortOrderLaughPagePanel',
        'SortOrder100YearPage'=>'SortOrder100YearPage',
        'SortOrderOurStoryPage'=>'SortOrderOurStoryPage',
        'SortOrderHomePageFeaturePanels'=>'SortOrderHomePageFeaturePanels',
        'Title' => 'Title',
        'Name' => 'Name', // The name of the Featured Product (used internally for search)
        'Publication_Date' => 'Publication_Date', // Optional publication date
        'Removal_Date' => 'Removal_Date', // Optional removal date
        'Template_Class' => 'Template_Class', // The template to use for this featured product
        'Bubble_Id' => 'Bubble_Id',
        'Bubble_Text' => 'Bubble_Text',
        'Title_1' => 'Title_1', // The Main title for that featured product
        'Title_2' => 'Title_2', // The secondary title
        'Subtitle_1' => 'Subtitle_1', // The secondary title #1 for the Featured Product
        'Subtitle_2' => 'Subtitle_2', // The secondary title #2 for the Featured Product
        'Subtitle_3' => 'Subtitle_3', // The secondary title #3 for the Featured Product
        'Slogan_1' => 'Slogan_1', // The slogan for the featured product Title or Image 1
        'Slogan_2' => 'Slogan_2', // The slogan for the featured product Title or Image 2
        'Slogan_3' => 'Slogan_3', // The slogan for the featured product Title or Image 3
        'Video_Url' => 'Video_Url', // The URL for the Youtube video
        'Video_Text' => 'Video_Text', // The text that describes  the Youtube video
        'Description' => 'Description', // The description for the Featured Product
        'Description_1' => 'Description_1', // The Description1
        'Description_2' => 'Description_1', // The Description2
        'Description_3' => 'Description_1', // The Description3
        'Featured_List' => 'Featured_List',
        'Feature_1' => 'Feature_1', // The  title #1 for the Feature 1
        'Feature_2' => 'Feature_2', // The  title #2 for the Feature 2
        'Feature_3' => 'Feature_3', // The  title #2 for the Feature 3
        'Feature_Description_1' => 'Feature_Description_1', // The  Description #1 for the Feature 1
        'Feature_Description_2' => 'Feature_Description_2', // The  Description #2 for the Feature 2
        'Feature_Description_3' => 'Feature_Description_3', // The  Description #2 for the Feature 3

        'CTA_Text' => 'CTA_Text',
        'CTA_Link' => 'CTA_Link',
        'CTA_Title' => 'CTA_Title',

        'CTA_Text_2' => 'CTA_Text_2',
        'CTA_Link_2' => 'CTA_Link_2',
        'CTA_Title_2' => 'CTA_Title_2',

        'CTA_Text_3' => 'CTA_Text_3',
        'CTA_Link_3' => 'CTA_Link_3',
        'CTA_Title_3' => 'CTA_Title_3',
    );
    public function getCMSFields() {

        Requirements::javascript('js/cms/feature-panel.js');

        $fields = parent::getCMSFields();

        $fields -> removeFieldsFromTab('Root', array(
            'Product',
            'CommitementPage',
            'OurHistoryPage',
            'ContactCloroxPage',
            'Welcome',
            'ProductsNewPage',
            'LaughPage',
            'OurStoryPage',
            'SlideTopItem'
        ));

        $Publication_Date = new DateField('Publication_Date');
        $Publication_Date -> setConfig('showcalendar', true);

        $Removal_Date = new DateField('Removal_Date');
        $Removal_Date -> setConfig('showcalendar', true);

        $AllClasses = array(
            'Full_Image' => 'Full Image',
            'Basic_Two_Column' => 'Two Col (image on right)',
            'PanelTwoColImageLeft' => 'Two Col (image on left)',
            'Bottom_Promo' => 'Bottom Promo',
            'Three_Features' => 'Three Features',
            'Video' => 'Video',
            'Video_Full_Screen' => 'Video Full Screen Panel (Size)',
            'Three_Round_Three_Columns' => 'Three Round Three Columns',
            'Four_Round_Four_Columns' => 'Four Round Four Columns',
            'Image_Left_Scroll_Box' => 'Image (left) with scroll box',
            'Custom_Class' => 'Custom Class'
        );
        //***************** Entire_Panel_Clickable
        $fields -> addFieldToTab('Root.Main', new CheckboxField('Entire_Panel_Clickable'));

        $fields -> addFieldToTab('Root.Main', new HeaderField('HeaderCoderOnly', 'Custom Class and RenderWith are for developper use only.'));
        $fields -> addFieldToTab('Root.Main', new TextField('CustomClassName', 'Custom Class'));
        $fields -> addFieldToTab('Root.Main', new TextField('RenderWith', 'Render With (Defines a template to render this panel with):'));

        //***************** Javascript to remove fields depending on the class
        $fields -> addFieldToTab('Root.Main', new LiteralField('Script', '<script>var Template_Class = "Form_ItemEditForm_Template_Class_' . $this -> Template_Class . '"; jQuery(document).ready(function(){ featurePanelAdmin.initListeners(); });</script>'));

        //***************** Template CLASSES
        $fields -> addFieldToTab('Root.Main', new DropdownField($name = 'Template_Class', $title = "Select Template Type", $source = $AllClasses));

        //***************** BACKGROUND CLASSES
        $cssClassController = new CssClasses_Controller();
        $fields -> addFieldToTab('Root.Main', $cssClassController -> backgroundClasses());

		//***************** BUBBLE CLASSES
        $fields -> addFieldToTab('Root.Main', $cssClassController -> bubbleClasses());

        //***************** Title
        $fields -> addFieldToTab('Root.Main', $cssClassController -> titleClasses('Title_Class'));
        //***************** TITLE KNOCKOUT TEXT CUSTOM COLOR
		$fields -> addFieldToTab('Root.Main', new TextField('Title_Knockout_Color'));

        $fields -> addFieldToTab('Root.Main', new TextField('Title'));
        $fields -> addFieldToTab('Root.Main', new TextField('Subtitle'));

        //***************** HEADLINE IMAGE
        $fields -> addFieldToTab('Root.Main', new TextField('Headline'));
        $fields -> addFieldToTab('Root.Main', new UploadField($name = 'HeadlineImage', $title = 'Upload the Headline Image'));

        //***************** NAME
        $fields -> addFieldToTab('Root.Main', new TextField('Name'));

        //***************** PUBLICATION/REMOVAL DATE
        $fields -> addFieldToTab('Root.Main', $Publication_Date);
        $fields -> addFieldToTab('Root.Main', $Removal_Date);

        //***************** TOP BUBBLE
        $fields -> addFieldToTab('Root.Main', new TextField('Bubble_Text'));
        $fields -> addFieldToTab('Root.Main', new TextField('Bubble_Id'));

        //***************** TITLES
        $fields -> addFieldToTab('Root.Main', new TextField('Title_1'));

        //***************** Title CLASSES

        $fields -> addFieldToTab('Root.Main', $cssClassController -> titleClasses('Title1_Class'));

        $fields -> addFieldToTab('Root.Main', new TextField('Title_2'));

        //***************** Title CLASSES
        $cssClassController = new CssClasses_Controller();
        $fields -> addFieldToTab('Root.Main', $cssClassController -> titleClasses('Title2_Class'));

        $fields -> addFieldToTab('Root.Main', new TextField('Title_3'));
        //***************** Title CLASSES
        $cssClassController = new CssClasses_Controller();
        $fields -> addFieldToTab('Root.Main', $cssClassController -> titleClasses('Title3_Class'));

        $fields -> addFieldToTab('Root.Main', new TextField('Title_4'));
        //***************** Title CLASSES
        $cssClassController = new CssClasses_Controller();
        $fields -> addFieldToTab('Root.Main', $cssClassController -> titleClasses('Title4_Class'));

        //***************** SUBTITLES
        $fields -> addFieldToTab('Root.Main', new TextField('Subtitle_1'));
        $fields -> addFieldToTab('Root.Main', new TextField('Subtitle_2'));
        $fields -> addFieldToTab('Root.Main', new TextField('Subtitle_3'));
		$fields -> addFieldToTab('Root.Main', new TextField('Subtitle_4'));

        //***************** SLOGANS
        $fields -> addFieldToTab('Root.Main', new TextField('Slogan_1'));
        $fields -> addFieldToTab('Root.Main', new TextField('Slogan_2'));
        $fields -> addFieldToTab('Root.Main', new TextField('Slogan_3'));
		$fields -> addFieldToTab('Root.Main', new TextField('Slogan_4'));

        //***************** Descriptions
        $fields -> addFieldToTab('Root.Main', new TextareaField('Description_1'));
        $fields -> addFieldToTab('Root.Main', new TextareaField('Description_2'));
        $fields -> addFieldToTab('Root.Main', new TextareaField('Description_3'));
		$fields -> addFieldToTab('Root.Main', new TextareaField('Description_4'));

        //***************** CTA CLASSES
        $cssClassController = new CssClasses_Controller();
        $fields -> addFieldToTab('Root.Main', $cssClassController -> ctaClasses());

        //***************** Social Medias
        $fields -> addFieldToTab('Root.Main', $cssClassController -> Social_Media_Classes('Social_Media_Class'));
        $fields -> addFieldToTab('Root.Main', new TextField('Social_Title'));
        $fields -> addFieldToTab('Root.Main', new TextField('Social_Media_Subtitle'));
        $fields -> addFieldToTab('Root.Main', new TextField('Social_Media_Hashtag'));
        $fields -> addFieldToTab('Root.Main', new TextField('Social_Media_Text'));
        $fields -> addFieldToTab('Root.Main', new TextField('Social_Media_Link'));

        $fields -> addFieldToTab('Root.Main', new TextField('SocialSharingDestination_Title'));
        $fields -> addFieldToTab('Root.Main', new TextField('SocialSharingDestination_Description'));
        $fields -> addFieldToTab('Root.Main', new TextField('SocialSharingDestination_URL'));

        //***************** CTA
        $fields -> addFieldToTab('Root.Main', $cssClassController -> circleButtonBgClasses());

        $fields -> addFieldToTab('Root.Main', new TextField('CTA_Title'));
        $fields -> addFieldToTab('Root.Main', new TextField('CTA_Text'));
        $fields -> addFieldToTab('Root.Main', new TextField('CTA_Link'));

        //***************** CTA CLASSES
        $cssClassController = new CssClasses_Controller();
        $fields -> addFieldToTab('Root.Main', $cssClassController -> ctaClasses('CTA_Class'));

        $fields -> addFieldToTab('Root.Main', new TextField('CTA_Title_2'));
        $fields -> addFieldToTab('Root.Main', new TextField('CTA_Text_2'));
        $fields -> addFieldToTab('Root.Main', new TextField('CTA_Link_2'));

        $fields -> addFieldToTab('Root.Main', new TextField('CTA_Title_3'));
        $fields -> addFieldToTab('Root.Main', new TextField('CTA_Text_3'));
        $fields -> addFieldToTab('Root.Main', new TextField('CTA_Link_3'));

		$fields -> addFieldToTab('Root.Main', new TextField('CTA_Title_4'));
        $fields -> addFieldToTab('Root.Main', new TextField('CTA_Text_4'));
		$fields -> addFieldToTab('Root.Main', new TextField('CTA_Link_4'));

        //***************** RELATED PRODUCT IMAGE
        $fields -> addFieldToTab('Root.Main', new UploadField($name = 'ProductImage', $title = 'Upload the Product Image'));

        //***************** RELATED VIDEO
        $fields -> addFieldToTab('Root.Main', new TextField('Video_Url'));
        $fields -> addFieldToTab('Root.Main', new TextField('Video_Text'));

        //***************** DESCRIPTION
        $fields -> addFieldToTab('Root.Main', new TextareaField('Description'));

        //***************** FEATURED LIST
        $fields -> addFieldToTab('Root.Main', new TextareaField('Featured_List'));

        //***************** SQUARE BUTTON CLASS
        $fields -> addFieldToTab('Root.Main', $cssClassController -> Square_Buttons_Colors_Class('Square_Buttons_Color_Class'));

        //***************** 3 FEATURES

        //***************** FEATURE 1
        $fields -> addFieldToTab('Root.Main', new TextField('Feature_1'));
		$fields -> addFieldToTab('Root.Main', new TextField('Feature_1_link'));
        $fields -> addFieldToTab('Root.Main', new TextField('Feature_Description_1'));
        $fields -> addFieldToTab('Root.Main', new UploadField($name = 'RoundImage_1', $title = 'Upload the Round Image 1'));

        //***************** FEATURE 2
        $fields -> addFieldToTab('Root.Main', new TextField('Feature_2'));
		$fields -> addFieldToTab('Root.Main', new TextField('Feature_2_link'));
        $fields -> addFieldToTab('Root.Main', new TextField('Feature_Description_2'));
        $fields -> addFieldToTab('Root.Main', new UploadField($name = 'RoundImage_2', $title = 'Upload the Round Image 2'));

        //***************** FEATURE 3
        $fields -> addFieldToTab('Root.Main', new TextField('Feature_3'));
		$fields -> addFieldToTab('Root.Main', new TextField('Feature_3_link'));
        $fields -> addFieldToTab('Root.Main', new TextField('Feature_Description_3'));
        $fields -> addFieldToTab('Root.Main', new UploadField($name = 'RoundImage_3', $title = 'Upload the Round Image 3'));

		//***************** FEATURE 4
        $fields -> addFieldToTab('Root.Main', new TextField('Feature_4'));
		$fields -> addFieldToTab('Root.Main', new TextField('Feature_4_link'));
        $fields -> addFieldToTab('Root.Main', new TextField('Feature_Description_4'));
        $fields -> addFieldToTab('Root.Main', new UploadField($name = 'RoundImage_4', $title = 'Upload the Round Image 4'));

        //***************** IMAGES
        $fields -> addFieldToTab('Root.Main', new UploadField($name = 'FullImage', $title = 'Upload the Background Image'));

        $fields -> addFieldToTab('Root.Main', new UploadField($name = 'VideoImage', $title = 'Upload the Image for the Video'));

        return $fields;
    }

    /**
     * @method cropAndResizeImage
     * to resize then crop an image
     *
     * @author Luc Martinaat Clorox.com
     * @param $image Image
     * @param $h the required height
     * @param $w the required width
     * @return the resized-cropped image
     */
    function cropAndResizeImage($image, $w, $h) {

        // get the original image sizes
        $width = $image -> getWidth();
        $height = $image -> getHeight();

        if ($width && $height) {

            // get the portrait vs landscape ratio
            $actualRatio = $height / $width;

            // we have landscape image
            if ($actualRatio >= 1) {

                //get proportional width
                $newWidth = $w * $height / $width;

                // set the height to the required measurements
                $newImage = $image -> setHeight($h);

            }
            else
            // its a portrait image
            {
                // set the height
                $newImage = $image -> setWidth($w);

            }
            if ($newImage -> getHeight() < $h) {
                // if the portrait image is not tall enough for the requirement
                $newImage = $image -> setHeight($h);

            }
            // grab the new proportions
            $newWidth = $newImage -> getWidth();
            $newHeight = $newImage -> getHeight();

            // crop the extraneous width and height
            if ($newWidth > $w) {
                $newImage = $newImage -> croppedImage($w, $newHeight);
            }
            elseif ($newHeight > $h) {
                $newImage = $newImage -> croppedImage($newWidth, $h);
            }

            //$newImage = $newImage->croppedImage($newWidth,$newHeight);
            return $newImage -> getAbsoluteURL();

        }
        else {
            return false;
        }
    }

    /**
     * @method SocialSharingDestination_UR
     * Purpose: generate a default social sharing destination on request
     *
     * @author James Billing and Luc Martin
     * @version $ID
     */
    public function SocialSharingDestination_URL() {
        if (empty($this -> SocialSharingDestination_URL)) {
            return $_SERVER["SERVER_NAME"] . $_SERVER["REQUEST_URI"];
        }
        return $this -> SocialSharingDestination_URI;
    }

    /**
     * function ResizedRoundImage_1 /2 /3/video
     * Called from the template will dynamicly return a new image with the height and width set
     * priority is to the height of the image
     *
     * @author Luc Martin at Clorox.com
     * @version $ID
     */
    function ResizedRoundImage_1($w, $h) {
        $image = $this -> RoundImage_1();
        return $this -> cropAndResizeImage($image, $w, $h);
    }

    function ResizedRoundImage_2($w, $h, $retType = null) {
        $image = $this -> RoundImage_2();
        return $this -> cropAndResizeImage($image, $w, $h);
    }

    function ResizedRoundImage_3($w, $h, $retType = null) {
        $image = $this -> RoundImage_3();
        return $this -> cropAndResizeImage($image, $w, $h);
    }

    function ResizedVideoImage($w, $h, $retType = null) {
        $image = $this -> VideoImage();
        return $this -> cropAndResizeImage($image, $w, $h);
    }

    function ResizedFeatureImageURL($w, $h, $retType = null) {
        $image = $this -> FeatureImage();
        return $this -> cropAndResizeImage($image, $w, $h);
    }

    function ResizedFullImage($w, $h, $retType = null) {
        $image = $this -> FullImage();
        return $this -> cropAndResizeImage($image, $w, $h);
    }

    /**
     * @method combineClass
     * Purpose Method to combine all the selected classes for that panel the method will remove the commas in the cms field
     *
     * @return String All classes for that Panel
     * @author Luc Martin at Clorox.com
     * @param $className The class to combine
     * @param $addCustomClass strue will add the customeClassName field to the result
     * @version $ID
     */
    function combineClass($className, $addCustomClass = null) {
        $class = $this -> $className;
        $class = str_replace(',', ' ', $class);

        // adds the custom Class if required
        $class .= (!empty($addCustomClass) && !empty($this->CustomClassName)) ? ' '.$this->CustomClassName.' ' : ' ';
        return $class;
    }

    /**
     * @method renderPanel
     * Purpose: Will render a Panel using a custom template.
     * @param $renderer String the name of the SS file to render the object
     * @author Luc Martin at Clorox.com
     * @version $ID
     */
    public function renderPanel($renderer = null){
        if(!empty($renderer)){
            return $this->renderWith($renderer);
        }
        elseif (!empty($this->RenderWith)) {
            return $this->renderWith(($this->RenderWith));
        }
        return $this;
    }

}
