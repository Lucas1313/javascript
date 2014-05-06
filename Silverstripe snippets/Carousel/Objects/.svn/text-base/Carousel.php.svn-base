<?php
/**
 * class Carousel
 * Purpose: A flexible slide that can contain most a diversity of DataObjects
 * To be used in a Cycle slide show
 * The carousel will take care of all the Javascript associated with the management of the carousel
 * @author Luc Martin at Clorox
 * @version $ID
 *
 */
class Carousel extends DataObject {

    public static $db = array(
        'Name' => 'Text',
        'Display_Name' => 'HTMLText',
        'Subtitle' => 'HTMLText',
        'pagerWidth' => 'Int',
        'pagerHeight' => 'Int'
    );

    public static $many_many = array(
        'CarouselSlides' => 'CarouselSlide',
    );

    public static $many_many_extraFields = array(
        'CarouselSlides' => array('SortOrderCarouselSlides' => 'Int')
    );

    public function getCMSFields() {
        $fields = parent::getCMSFields();

        // Name
        $fields -> addFieldToTab('Root.Main', new HeaderField('NameHeader', 'Searchable Name </h3><p>For internal use only, no special character please</p><h3>'));
        $fields -> addFieldToTab('Root.Main', new TextField('Name'));

        // Display_Name
        $fields -> addFieldToTab('Root.Main', new HeaderField('Display_NameHeader', 'Display Name, </h3><p>The main Title</p><h3>'));
        $fields -> addFieldToTab('Root.Main', new TextField('Display_Name'));

        $fields -> addFieldToTab('Root.Main', new HeaderField('Display_Pager', 'Pager image sizes, </h3><p>Please enter the format for the images of the pager. (Only numbers)</p><p>Will default to image original size<h3>'));

        $fields -> addFieldToTab('Root.Main', new TextField('pagerWidth'));
        $fields -> addFieldToTab('Root.Main', new TextField('pagerHeight'));



        // Subtitle
        $fields -> addFieldToTab('Root.Main', new HeaderField('Subtitleheader', 'Subtitle </h3><p>The secondary title</p><h3>'));
        $fields -> addFieldToTab('Root.Main', new TextField('Subtitle'));

        //***************** Popular topics
        $fields -> addFieldToTab('Root.Main', new HeaderField('CarouselSlidesHeader', 'Carousel Slides </h3><p>To Create a Slideshow, add Carousel Slides to this panel</p><h3>'));
        $Field = new GridField('CarouselSlides', 'CarouselSlides', $this -> CarouselSlides(), GridFieldConfig_RelationEditor::create() -> addComponents(new GridFieldDeleteAction('unlinkrelation')));
        $fields -> addFieldToTab('Root.Main', $Field);

        return $fields;
    }

    public function CarouselSlides() {
        return $this -> getManyManyComponents('CarouselSlides') -> sort('SortOrderCarouselSlides');
    }
    public function countCarosselSlides($n){
        $val = $this->CarousselSlides()->Count();
        if($val < $n){
            return '<';
        }
        elseif($val >= $n){
            return '>=';
        }else{
            return false;
        }
    }
    function renderCarousel(){
        return $this->renderWith('Carousel');
    }
    public function codename(){

        $str = preg_replace('/([^A-Za-z0-9])/', "", $this->Name);
        $str = strtolower($str);

        return $str;

    }
}
