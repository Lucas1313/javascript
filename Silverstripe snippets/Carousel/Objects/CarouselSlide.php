<?php
/**
 * class CarousselSlide
 * Purpose: A flexible slide that can contain most a diversity of DataObjects
 * To be used in a Cycle slide show
 * @author Luc Martin at Clorox
 * @version $ID
 *
 */
class CarouselSlide extends DataObject {

    public static $db = array(
        'Name' => 'Text',
        'Display_Name' => 'HTMLText',
        'Subtitle' => 'HTMLText',
        'FlexibleContent' => 'HTMLText',
        'User_Click_Counter' => 'Int',
        'CTA_Class' => 'Varchar',
        'CTA_Text' => 'HtmlText',
        'CTA_Link' => 'HtmlText',
        'CTA_Title' => 'HtmlText',
        'Navigation_Text' => 'HTMLText',
        'Render_With' => 'Text',
        'Slide_Type' => 'Text',
    );

    public static $has_one = array(
        'FeaturePanel' => 'FeaturePanel',
        'IcktionaryItem' => 'IcktionaryItem',
        'Product' => 'Product',
        'PressItem' => 'PressItem',
        'VideoItem' => 'VideoItem',
        'BLMMasterPage' => 'BLMMasterPage',
        'BLMVotingPage' => 'BLMVotingPage',
        'IcktionaryItemPage' => 'IcktionaryItemPage',
        'BLMDetailPage' => 'BLMDetailPage',
        'CTA_Image' => 'Image',
        'Navigation_Image' => 'Image',
    );

    public $belong_many_many = array('Carousel' => 'Carousel');

    public function getCMSFields() {

        $fields = parent::getCMSFields();

        //$path = "Carousel/templates/includes";
        //$files = scandir($path);
        /**
        foreach ($files as &$value) {
            error_log ("<a href='http://localhost/".$value."' target='_black' >".$value."</a><br/>");
        }
        **/
        // Name
        $fields -> addFieldToTab('Root.Main', new HeaderField('NameHeader', 'Searchable Name</h3><p>For internal use only, please no Special Character</p><h3>'));
        $fields -> addFieldToTab('Root.Main', new TextField('Name'));

        // Display_Name
        $fields -> addFieldToTab('Root.Main', new HeaderField('Display_NameHeader', 'Display Name</h3><p>Will be rendered as h1 Tag<h3>'));
        $fields -> addFieldToTab('Root.Main', new TextField('Display_Name'));

        // Subtitle
        $fields -> addFieldToTab('Root.Main', new HeaderField('Subtitleheader', 'Subtitle'));
        $fields -> addFieldToTab('Root.Main', new TextField('Subtitle'));
        $fields -> addFieldToTab('Root.Main', new HeaderField('Render_WithHeader', '</h3><p>Please define the template to use to render that slide</p><h3>'));

        $fields -> addFieldToTab('Root.Main', new TextField('Render_With'));

        $fields -> addFieldToTab('Root.Main', new HeaderField('ObjectHeader', 'Slide type</h3><p>Please Select only one type!</p><h3>'));

        foreach ($this -> has_one() as $key => $slideObject) {
            if ($slideObject == 'Image') {
                continue;
            }
            $obj = $key::get() -> first();

            if (!empty($obj -> Name)) {
                $mapObj = 'Name';
            }
            elseif (!empty($obj -> DisplayName)) {
                $mapObj = 'DisplayName';
            }
            else {
                $mapObj = 'Title';
            }

            $asText = '' . $key;
            $field = new DropdownField($key . 'ID', $key, $asText::get() -> map('ID', $mapObj));
            $field -> setEmptyString('(Select a ' . $asText . ')');
            $fields -> addFieldToTab('Root.Main', $field);
        }

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
        $fields -> addFieldToTab('Root.Main', new ReadonlyField('User_Click_Counter'));
        return $fields;
    }

    private function setSlideType() {

        $temp = $this -> Slide_Type;

        foreach ($this -> has_one() as $key => $slideObject) {

            if ($slideObject == 'Image') {
                continue;
            }

            $obj = $key.'ID';

            //error_log('$slideObject -> ID '.$key.'ID'.$this->$obj);

            if (!empty($this->$obj) && $temp !== $key) {
                $this -> Slide_Type = $key;
                $this->write();
            }
        }

    }

    public function onAfterWrite() {
        parent::onAfterWrite();
        $this -> setSlideType();
    }

    public function renderSlide($templateName = null){

        if (!empty($templateName)) {
            $ret= $this->renderWith($templateName);
        }elseif(!empty($this->Render_With)){
            $ret = $this->renderWith($this -> Render_With);
        }else{
            $ret = $this->renderWith($this -> Slide_Type);
        }
        return $ret;
    }
    public function codename(){

        $str = preg_replace('/([^A-Za-z0-9])/', "", $this->Name);
        $str = strtolower($str);

        return $str;

    }
}
