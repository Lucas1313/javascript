<?php
/**
 * Class BLEcardsGalleryPage
 * Describes the Model for a BLEcardsGalleryPage
 *
 *  Purpose: eCards Gallery
 *  eCards gallery, shows small view of each eCards complete
 *  with sharing options. Page will scroll to accommodate the
 *  number of eCards available.
 * @author Luc Martin lmartin -at- clorox.com
 * @version $Id
 */
class BLMEcardsGalleryPage extends BLMMasterPage {
    static $db = array(
        'Description' => 'HtmlText',
        'TwitterCopy'=>'Text',
        'PinterestCopy'=>'Text'
    );
    public static $has_one = array();
    public static $has_many = array('BLMEcards' => 'BLMEcard');
    public static $many_many = array();
    public static $belong_many_many = array();

    public function getCMSFields() {

        $fields = parent::getCMSFields();

        $fields -> addFieldToTab('Root.Main', new TextField('Title'));

        $fields -> removeFieldFromTab('Root', 'Content');



        //***************** Description

        $fields -> addFieldToTab('Root.Main', new TextareaField('Description'));

        //***************** SOCIAL MEDIAS
        $fields -> addFieldToTab('Root.Main', new HeaderField('TwitterPinterestCopy', 'Twitter And Pinterest Copy</h2><p>In that section you can set relevant marketing information to pass to the social medias</p><h2>'));
        $fields -> addFieldToTab('Root.Main', new TextField('TwitterCopy', 'Twitter Copy'));
        $fields -> addFieldToTab('Root.Main', new TextField('PinterestCopy', 'Pinterest Copy'));


        //***************** Feature Panels
        $BLMEcardsField = new GridField('BLMEcards', 'BLMEcards', $this -> BLMEcards(), GridFieldConfig_RelationEditor::create() -> addComponents(new GridFieldSortableRows('SortOrderEcardsGallery'),new GridFieldDeleteAction('unlinkrelation')));
        $fields -> addFieldToTab('Root.Main', $BLMEcardsField);

        return $fields;

    }
    function BLMEcardsSorted(){
        return $this->BLMEcards()->sort('SortOrderEcardsGallery','ASC');
    }

}

class BLMEcardsGalleryPage_Controller extends BLMMasterPage_Controller {

    public function init() {
        Requirements::javascript("js/blm-ecards-gallery-page.js");
        parent::init();
    }

}