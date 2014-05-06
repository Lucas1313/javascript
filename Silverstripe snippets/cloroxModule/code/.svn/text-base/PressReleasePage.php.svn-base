<?php
/*
 * Class PressReleasePage
 *
 * Describes the Model for a PressReleasePage
 *
 * @author Luc Martin lmartin -at- clorox.com
 * @version $Id
 */
class PressReleasePage extends Page {
    static $db = array(
        'Publication Date' => 'Date',
        'Title' => 'Varchar',
        'Title_1' => 'Varchar',
        'Title_2' => 'Varchar',
        'Slogan' => 'Text',
    );

    static $has_many = array("PressItem" => "PressItem", 'AlsoLikeItems'=>'AlsoLikeItem');

    public function getCMSFields() {

        $fields = parent::getCMSFields();

        $fields -> addFieldToTab('Root.Main', new TextField('Title'));

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

    /**
     * Function addUrlToPressItems
     * generate the URL for the item page.
     */
    public function addUrlToPressItems(){

        $allPressItems = $this->PressItem();
        foreach ($allPressItems as $key => $pressItem) {
            //String cleanup and manipulation
            $strManipulator = new StringManipulator_Controller();
            $pressItemUrl = $strManipulator-> generateUrlCompatibleName($pressItem->Name);
            $pressItem->CTA_Link = $this->Link().$pressItemUrl;
            $pressItem->write();
        }
    }

    public function getPressItem(){
        return $this->PressItem()->sort('Release_Date','DESC');
    }
     /**
     * Method called before an Object is saved
     * Will synchronise the "specific uses for the product with the selected checkboxes"
     * @param none
     * @return void
     */
    public function onBeforeWrite() {
        $this->addUrlToPressItems();
        parent::onBeforeWrite();
    }

}

class PressReleasePage_Controller extends Page_Controller {

    public function init() {
          //Add all our files to combine into an array
        Requirements::javascript("js/pages/press-release-page.js");
        parent::init();
    }

}
