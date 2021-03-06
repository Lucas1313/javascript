<?php
/*
 * Ingredient
 *
 * Describes the Model for an Ingredient
 *
 * @author Luc Martin lmartin -at- clorox.com
 * @version $Id: Ingredient.php 19949 2013-03-15 22:19:08Z lmartin $
 *
 * Relationships:
 *
 * hasOne =
 * many-one =
 * many-many =
 * belong_many_many = ProductSubCategory;
 */
class Ingredient extends DataObject {
    static $db = array(
        'Title' => 'HtmlText',
        'Name' => 'HtmlText',
        'Description' => 'HtmlText',
        'Percentage' => 'Varchar',
        'Code_Name' => 'Varchar'
    );

    static $belong_many_many = array('ProductSubCategory' => 'ProductSubCategory');

    public static $summary_fields = array(
        'ID' => 'ID',
        'Name' => 'Name',
        'Description' => 'Description',
        'Code_Name' => 'Code_Name'
    );

    public function getCMSFields() {
        $fields = parent::getCMSFields();
        $fields -> addFieldToTab('Root.Main', new TextField('Title'));
        $fields -> addFieldToTab('Root.Main', new TextField('Name'));
        $fields -> addFieldToTab('Root.Main', new TextareaField('Description'));
        $fields -> addFieldToTab('Root.Main', new UploadField($name = 'IngredientImage', $title = 'Upload the Image for that Ingredient'));

        return $fields;
    }
    public function Description(){
        $ret = str_replace('<a href="fragrances">', '', $this->Description);
        return str_replace('</a>', '', $ret);
        
    }
}
?>