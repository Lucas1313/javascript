<?php
/*
 * TagProductSelector extends TagGeneral
 *
 * Describes the Model for a Tag
 *
 * @author Luc Martin lmartin -at- clorox.com
 * @version $Id: TagGeneral.php 18615 2013-02-18 19:56:12Z lmartin $
 *
 * Relationships:
 * HasOne =
 * HasMany =
 * many-many =
 * belong-many-many = Product
 *
 */
class TagProductSelector extends DataObject {
    static $db = array(
        'Name'=>'Text',
        'Codename' => 'Text',
        'Tag_Type' => 'Varchar',
        'MostCommon' => 'Boolean',
        'SortOrderProductSelectorSubstance' => 'Int',
        'SortOrderProductSelectorSurface' => 'Int',
        'Aliases' => 'Text',
        'ProductsId' => 'Text',
        'ReplacementText'=>'Text'
    );

    static $belong_many_many = array(
        'Tag' => 'TagProductSelector',
        'Product' => 'Product'
    );

    static $many_many = array('relatedSubstances'=>'TagProductSelector');

    static $summary_fields = array(
        'ID',
        'Name',
        'Tag_Type',
        'Codename',
        'MostCommon',
        'ProductsId'
    );

    static $default_sort = "Name";

    public function getCMSFields() {
        $fields = parent::getCMSFields();
        $fields -> addFieldToTab('Root.Main', new TextField('Name'));
        if(empty($this->Codename)){
            $this->Codename = $this->GenerateCodename();
        }
        $fields -> addFieldToTab('Root.Main', new TextField('Codename'));
        $fields -> addFieldToTab('Root.Main', new TextField('Aliases'));
        $fields -> addFieldToTab('Root.Main', new CheckboxField('MostCommon', 'Most Common'));
        $AllTypes = array(
            'Surface'=>'Surface',
            'Substance' => 'Substance',
        );
          // get all existing features
        $tags_Substances= DataObject::get('TagProductSelector')->filter(array('Tag_Type'=>'Substance'));

        if (!empty($tags_Substances) && $this->Tag_Type !== 'Substance') {

            // create an array('ID'=>'Name')
            $map = $tags_Substances->map('ID', 'Name');

            // create a Checkbox group based on the array
            $fields->addFieldToTab('Root.relatedSubstances',
                new CheckboxSetField(
                    $name = "relatedSubstances",
                    $title = "Select Substance",
                    $source = $map
            ));
            $fields -> addFieldToTab('Root.Main', new HeaderField('replacementTextHeader','Replace the text of the OMG my ___ <strong>has/have</strong> ___ on it'));
            $fields -> addFieldToTab('Root.Main', new TextField('ReplacementText'));
        }else{
            $fields -> removeFieldsFromTab('Root', array(
            'relatedSubstances'));
        }
        $fields -> addFieldToTab('Root.Main', new OptionsetField($name = 'Tag_Type', $title = "Select Tag Type", $source = $AllTypes));
        return $fields;
    }

    public function GenerateCodename(){
        $stringmanipulator = new StringManipulator_Controller();
        return $stringmanipulator->generateCodeName($this->Name);
    }

    public function generateAliases(){

    }
    public function onBeforeWrite(){
        if(empty($this->Codename)){
            $this->Codename = $this->GenerateCodename();
        }
        parent::onBeforeWrite();
    }
    public function onAfterWrite(){
         parent::onAfterWrite();
        if(empty($this->Name) || empty($this->Tag_Type)){
            $this->delete();
        }

    }

}
