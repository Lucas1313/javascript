<?php
/*
 * ProductBenefit
 *
 * Describes the Model for a ProductBenefit
 *
 * @author Luc Martin lmartin -at- clorox.com
 * @version $Id: ProductBenefit.php 17488 2013-01-06 01:52:15Z jware $
 *
 * Relationships:
 * one-many =
 * many-one =
 * many-many =
 * belong-many-many = Product ProductSubCategory
 *
 */
class ProductBenefit extends DataObject {

    static $db = array(
        'Name' => 'Text',
        'Title' => 'Text',
        'Description' => 'Text'
    );
    static $has_one = array('Image' => 'Image');
    static $belong_many_many = array(
        'Products' => 'Product',
        'ProductSubCategories',
        'ProductSubCategory'
    );

    public function getCMSFields() {

        $fields = parent::getCMSFields();
        $fields -> removeFieldFromTab('Root.Main', 'Content');
        $fields -> addFieldToTab('Root.Main', new TextField('Name'));
        $fields -> addFieldToTab('Root.Main', new TextField('Title'));
        $fields -> addFieldToTab('Root.Main', new TextAreaField('Description'));
        $fields -> addFieldToTab('Root.Main', new UploadField($name = 'ProductImage', $title = 'Upload the Product Image'));
        return $fields;
    }

}
