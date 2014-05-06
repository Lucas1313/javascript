<?php
/*
 * Class BLMLaughLearnPage
 * Describes the Model for a BLMVotingPage
 *
 * @author Luc Martin lmartin -at- clorox.com
 * @version $Id
 */
class BLMLaughLearnPage extends BLMMasterPage {

    static $db = array('Description' => 'HtmlText', );

    public static $has_one = array('BLMPromo' => 'BLMPromo');

    public static $has_many = array();

    public static $many_many = array(
        'VideoItems' => 'VideoItem',
        'BLMSolves' => 'BLMSolve'
    );
    public static $belongs_many_many = array();

    public static $many_many_extraFields = array('VideoItems' => array('VideoItemsSortOrder' => 'Int'));

    public function getCMSFields() {

        $fields = parent::getCMSFields();

        $fields -> addFieldToTab('Root.Main', new TextField('Title'));

        $fields -> removeFieldFromTab('Root.Main', 'Content');

        //***************** Description
        $fields -> addFieldToTab('Root.Main', new TextareaField('Description'));

        //***************** BLMSolves
        $BLMSolvesField = new GridField('BLMSolves', 'Featured Solves', $this -> BLMSolves(), GridFieldConfig_RelationEditor::create() -> addComponents(new GridFieldSortableRows('BLMSolvesSortOrder'), new GridFieldDeleteAction('unlinkrelation')));
        $fields -> addFieldToTab('Root.Main', $BLMSolvesField);


        //***************** VideoItems
        $VideoItemsField = new GridField('VideoItems', 'VideoItems', $this -> VideoItems(), GridFieldConfig_RelationEditor::create() -> addComponents(new GridFieldSortableRows('VideoItemsSortOrder'), new GridFieldDeleteAction('unlinkrelation')));
        $fields -> addFieldToTab('Root.Main', $VideoItemsField);

        return $fields;

    }

    public function VideoItems() {
        return $this -> getManyManyComponents('VideoItems') -> sort('VideoItemsSortOrder');
    }

}

class BLMLaughLearnPage_Controller extends BLMMasterPage_Controller {

    public function init() {
        Requirements::javascript("js/BLM-BLMLaughLearnPage-page.js");
        parent::init();
    }

}
