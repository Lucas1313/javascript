<?php
/*
 * Class GenericPage
 * Describes the Model for a the page at /products/smart-tube-technology
 *
 * @author Luc Martin lmartin -at- clorox.com
 * @version $Id
 */
class SmartTubeTechnologyPage extends Page {
    static $db = array(
        'Publication Date' => 'Date',
        'Title' => 'Varchar',
        'Description' => 'HtmlText'
    );

    static $many_many = array('FeaturePanel' => 'FeaturePanel');
    static $has_many = array('SmartTubeProducts' => 'Product');

    public function getCMSFields() {

        $fields = parent::getCMSFields();

        $fields -> addFieldToTab('Root.Main', new TextField('Title'));

        $fields -> removeFieldFromTab('Root', 'Content');

        $dateField = new DateField('Publication Date');

        //***************** Description
        $fields -> addFieldToTab('Root.Main', new TextareaField('Description'));

        //***************** SmartTubeProducts
        $SmartTubeProductsField = new GridField('SmartTubeProducts', 'SmartTubeProducts', $this -> SmartTubeProducts(), GridFieldConfig_RelationEditor::create() -> addComponents(new GridFieldSortableRows('SortOrderSmarttube'), new GridFieldDeleteAction('unlinkrelation')));
        $fields -> addFieldToTab('Root.Main', $SmartTubeProductsField);
        return $fields;

        //***************** Feature Panels
        $FeaturePanelField = new GridField('FeaturePanel', 'FeaturePanel', $this -> FeaturePanel(), GridFieldConfig_RelationEditor::create() -> addComponents(new GridFieldDeleteAction('unlinkrelation')));
        $fields -> addFieldToTab('Root.Main', $FeaturePanelField);
        return $fields;

    }

}

class SmartTubeTechnologyPage_Controller extends Page_Controller {

    public function init() {
        // facetube
        Requirements::javascript('js/plugins/jquery.youtubewrapper.js');
        Requirements::javascript('js/pages/smart-tube-technology.js');

        parent::init();
    }

}
