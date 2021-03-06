<?php
/*
 * Tag extends DataObject
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
class TagGeneral extends DataObject {
    static $db = array(
        'Title'=>'Text',
        'Name' => 'Text',
        'Description' => 'Text',
        'Link_Title' => 'Text',
        'Link_Url' => 'Text',
        'Tag_Type' => 'Varchar'
    );

    static $belong_many_many = array(
        'IcktionaryItems' => 'IcktionaryItem',
        'Product' => 'Product'
    );

    static $summary_fields = array(
        'Name',
        'Description',
        'Tag_Type',
        'Link_Title',
        'Link_Url',
        'ID'
    );

    public function getCMSFields() {
        $fields = parent::getCMSFields();
        $fields -> addFieldToTab('Root.Main', new TextField('Title'));
        $fields -> addFieldToTab('Root.Main', new TextField('Name'));
        $fields -> addFieldToTab('Root.Main', new TextAreaField('Description'));
        $AllTypes = array(
        
            'General'=>'General',
            'IckSubstance' => 'IckSubstance',
            'IckArea' => 'IckArea',
            
        );
        $fields -> addFieldToTab('Root.Main', new LiteralField('Script', '<script>var Template_Class = "Form_ItemEditForm_Template_Class_' . $this -> Template_Class . '"; jQuery(document).ready(function(){ featurePanelAdmin.initListeners(); });</script>'));
        $fields -> addFieldToTab('Root.Main', new OptionsetField($name = 'Tag_Type', $title = "Select Tag Type", $source = $AllTypes));
        
        $fields -> addFieldToTab('Root.Main', new TextField('Link_Title'));
        $fields -> addFieldToTab('Root.Main', new TextField('Link_Url'));
        return $fields;
    }


}
