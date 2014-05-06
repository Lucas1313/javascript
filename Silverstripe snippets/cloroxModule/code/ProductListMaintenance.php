<?php
/*
 * Product
 *
 * Describes the Model for a Product
 *
 * @author Luc Martin lmartin -at- clorox.com
 * @version $Id: ProductListMaintenance.php 18878 2013-02-23 09:21:31Z jware $
 *
 * Relationships:
 *
 * hasOne = Image, ProductPage, SingleProductPage, ProductCategoryPage, AlsoLikeItem
 * many-one = ProductSubCategories
 * many-many = FAQs, ProductBenefits, TagFeatures, TagNeed, TagType
 *
 */
class ProductListMaintenance extends DataObject {
    
    static $db = array('Code_Name' => 'Text', 'Product' => 'Text', 'From_Api'=>'Boolean');
     static $summary_fields = array('Code_Name','Product', 'From_Api');
    //the cms fields
    public function getCMSFields() {

        $fields = parent::getCMSFields();
        
        return $fields;
    }
}