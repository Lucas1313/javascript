<?php
/**
 * class UniversalSlide
 * Purpose: A flexible slide that can contain most a diversity of DataObjects
 * To be used in a Cycle slide show
 * @author Luc Martin at Clorox
 * @version $ID
 *
 */
class UniversalSlideShow extends DataObject {
	public  $_universalSlides;
    public static $db = array(
        'Name' => 'Text',
        'Display_Name' => 'HTMLText',
        'Subtitle' => 'HTMLText',
        'WelcomePageSortOrder' => 'Int'
    );

    public static $has_one = array('Welcome'=>'Welcome');

    public static $many_many = array(
        'UniversalSlides' => 'UniversalSlide',
        'OurHistoryPage' => 'OurHistoryPage'
    );

    public static $belong_many_many = array(
        'OurStoryPage' => 'OurStoryPage',
        'SingleProductPage' => 'SingleProductPage'
    );

    public static $many_many_extraFields = array(
        'UniversalSlides' => array('SortOrderUniversalSlides' => 'Int')
    );
    public function getCMSFields() {
        $fields = parent::getCMSFields();

        // Name
        $fields -> addFieldToTab('Root.Main', new HeaderField('NameHeader', 'Searchable Name </h3><p>For internal use only, no special character please</p><h3>'));
        $fields -> addFieldToTab('Root.Main', new TextField('Name'));

        // Display_Name
        $fields -> addFieldToTab('Root.Main', new HeaderField('Display_NameHeader', 'Display Name, </h3><p>The main Title</p><h3>'));
        $fields -> addFieldToTab('Root.Main', new TextField('Display_Name'));

        // Subtitle
        $fields -> addFieldToTab('Root.Main', new HeaderField('Subtitleheader', 'Subtitle </h3><p>The secondary title</p><h3>'));
        $fields -> addFieldToTab('Root.Main', new TextField('Subtitle'));

        //***************** Popular topics
        $fields -> addFieldToTab('Root.Main', new HeaderField('UniversalSlidesHeader', 'Universal Slides </h3><p>To Create a Slideshow, add Universal Slides to this panel</p><h3>'));
        $Field = new GridField('UniversalSlides', 'UniversalSlides', $this -> UniversalSlides(), GridFieldConfig_RelationEditor::create() -> addComponents(new GridFieldSortableRows('SortOrderUniversalSlides'), new GridFieldDeleteAction('unlinkrelation')));
        $fields -> addFieldToTab('Root.Main', $Field);

        return $fields;
    }

    public function UniversalSlides() {
    	if(is_object($this->_universalSlides)){
    		if(!empty($this->_univsalSlides)){
    			return $this->_universalSlides;
    		}
    	}
        return $this->_universalSlides = $this -> getManyManyComponents('UniversalSlides') -> sort('SortOrderUniversalSlides'); 
    }
    public function countUniversalSlides($n){
        $val = $this->UniversalSlides()->Count();
        if($val < $n){
            return '<';
        }
        elseif($val >= $n){
            return '>=';
        }else{
            return false;
        }
    }

}
