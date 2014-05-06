<?php
/**
 * RegistrationPage Class used to render unique
 * Registration page type in template.
 *
 * @author Jason Ware jason.ware -at- clorox.com
 * @package cloroxModule.code
 * @version $Id: RegistrationPage.php 22643 2013-05-29 23:14:50Z jware $
 */
class RegistrationPage extends Page {
    public static $many_many = array (
        "FeaturePanel" => "FeaturePanel"
        );

    public function getCMSFields() {
        $fields = parent::getCMSFields();

        $FeaturePanelFieldConfig = GridFieldConfig_RelationEditor::create();
        $FeaturePanelFieldConfig->addComponents(new GridFieldDeleteAction('unlinkrelation'));

        $FeaturePanelField = new GridField('FeaturePanel',  'FeaturePanel',  $this -> FeaturePanel(), $FeaturePanelFieldConfig);

        $fields -> addFieldToTab('Root.Main', $FeaturePanelField);


        return $fields;
    }
}

class RegistrationPage_Controller extends Page_Controller {

    // adding a method to retrieve the BackURL
    // for redirect hidden fields in forms   
    public function BackURL() { 
        if( $BackURL = $this->request->requestVar('BackURL') ) { 
            return $BackURL; 
        } else { 
            return Session::get('BackURL'); 
        } 
    }
}