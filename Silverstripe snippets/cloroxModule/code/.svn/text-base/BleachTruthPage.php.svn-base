<?php
/*
 * Class BleachTruth
 *
 * Describes the Model for a Truth About Bleach page
 *
 * @author Luc Martin lmartin -at- clorox.com
 * @version $Id
 */
class BleachTruthPage extends Page {

    static $has_many = array("FeaturePanel" => "FeaturePanel");

    public function getCMSFields() {

        $fields = parent::getCMSFields();

        $fields -> addFieldToTab('Root.Main', new TextField('Title'));

        $fields -> removeFieldFromTab('Root', 'Content');

        //***************** Feature Panels
        $FeaturePanelField = new GridField('FeaturePanel', 'FeaturePanel', $this -> FeaturePanel(), GridFieldConfig_RelationEditor::create() -> addComponents(new GridFieldDeleteAction('unlinkrelation')));
        $fields -> addFieldToTab('Root.Main', $FeaturePanelField);
        return $fields;

    }

}

class BleachTruthPage_Controller extends Page_Controller {

     public function init() {
        Requirements::javascript("js/pages/bleach-truth.js");
        parent::init();
    }

}
