<?php
class IckSinglePage extends Page{

     static $has_many = array(

        'IcktionaryItem' => 'IcktionaryItem'

    );
    public function getCMSFields() {

        $fields = parent::getCMSFields();

        $fields -> removeFieldFromTab('Root.Main', 'Content');

        //************************* ICK

        $ItemsField = new GridField('IcktionaryItem', 'IcktionaryItem', $this ->IcktionaryItem(), GridFieldConfig_RelationEditor::create() -> addComponents(new GridFieldDeleteAction('unlinkrelation')));
        $fields -> addFieldToTab('Root.Main', $ItemsField);

        return $fields;
    }

}
class IckSinglePage_Controller extends Page_Controller{


    //Set the sort for the items (defaults to Created DESC)
    static $item_sort = 'SortOrder';

    function init(){

        Requirements::javascript("js/pages/icktionary.js");

        parent::init();

    }
    public function getVar($varName){
	    $ick = $this->IcktionaryItem()->first();
	    return $ick->$varName;
    }

    public function getSingleIckUrl($title){
	    $alreadyExistingPage = IckSinglePage::get()->filter('Title',$title);
        foreach ($alreadyExistingPage as $key => $page) {
            if($page->Title == $title){
               return $page->Link();
            }
        }

    }
}
