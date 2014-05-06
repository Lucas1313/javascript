<?php
/*
 * Class PressItemPage
 *
 * Describes the Model for a PressItemPage 
 *
 * @author Luc Martin lmartin -at- clorox.com
 * @version $Id
 */
class PressItemPage extends Page {
    static $db = array(
        'Name'=>'text',
        'Publication Date' => 'Date',       
        'Title_1' => 'Varchar',
        'Title_2' => 'Varchar',
        'Slogan' => 'Text',
        'Title1_Class'=>'Text',
        'Description' => 'HtmlText'
    );
    
    static $has_many = array('AlsoLikeItems'=>'AlsoLikeItem', "PressItem" => "PressItem");

    public function getCMSFields() {

        $fields = parent::getCMSFields();
        
        
        $fields -> removeFieldFromTab('Root', 'Content');

        $dateField = new DateField('Publication Date');
        
        //***************** Title CLASSES
        $cssClassController = new CssClasses_Controller();
        $fields -> addFieldToTab('Root.Main', $cssClassController -> titleClasses('Title1_Class'));
        
        //***************** Titles
        $fields -> addFieldToTab('Root.Main', new TextField('Title_1'));
        $fields -> addFieldToTab('Root.Main', new TextField('Title_2'));
        
        //***************** Description
        $fields -> addFieldToTab('Root.Main', new TextareaField('Description'));        
        
        
        //***************** Feature Panels
        $PressItemField = new GridField('PressItem', 'PressItem', $this -> PressItem(), GridFieldConfig_RelationEditor::create() -> addComponents(new GridFieldDeleteAction('unlinkrelation')));
        $fields -> addFieldToTab('Root.Main', $PressItemField);
        
        $AlsoLikeItemsField = new GridField('AlsoLikeItems', 'AlsoLikeItems', $this -> AlsoLikeItems(), GridFieldConfig_RelationEditor::create() -> addComponents(new GridFieldDeleteAction('unlinkrelation')));
        $fields -> addFieldToTab('Root.Main', $AlsoLikeItemsField);
        
        
        return $fields;

    }

}

class PressItemPage_Controller extends Page_Controller {

    public function init() {
        parent::init();
    }

}
