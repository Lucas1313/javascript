<?php
/*
 * Class KidsCornerPage
 * Describes the Model for a KidsCornerPage
 *
 * @author Luc Martin lmartin -at- clorox.com
 * @version $Id
 */
class KidsCornerPage extends Page {
    static $db = array();

    static $many_many = array(
        "KidsCornerPanels" => "KidsCornerPanel");

    public static $many_many_extraFields=array(
        'KidsCornerPanels'=>array('SortOrderKidsCornerPanels'=>'Int')
    );


    public function getCMSFields() {

        $fields = parent::getCMSFields();

        $fields -> removeFieldFromTab('Root', 'Content');

        //************** Kid Panels

        $FeaturePanelFieldConfig = GridFieldConfig_RelationEditor::create();
        $FeaturePanelFieldConfig->addComponents(new GridFieldSortableRows('SortOrderKidsCornerPanels'), new GridFieldDeleteAction('unlinkrelation'));

        $FeaturePanelField = new GridField('KidsCornerPanels',  'KidsCornerPanels',  $this -> KidsCornerPanels(), $FeaturePanelFieldConfig);

        $fields -> addFieldToTab('Root.Main', $FeaturePanelField);

        return $fields;
    }

    public function KidsCornerPanels() {
        
        return $this->getManyManyComponents('KidsCornerPanels')->sort('SortOrderKidsCornerPanels');
    }
}

class KidsCornerPage_Controller extends Page_Controller {

    // public function init() {
    //     Requirements::javascript("js/pages/our-story.js");
    //     parent::init();
    // }

}
