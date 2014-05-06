<?php
class ClassroomsPromoProduct extends DataObject {
    static $db = array(
        'Name'=>'Varchar',
        'CTA_Title' => 'HtmlText',
        'CTA_Link'=>'Text',
        'Coupon_Link'=>'Text',
        'SortOrderClassroomsPageFeaturePanels'=> 'Int'
    );
    public static $default_sort='SortOrderClassroomsPageFeaturePanels';

    static $has_one = array(
        'CouponImage' => 'Image',
        'DefaultImage' => 'Image',
        'AssociatedProductPage'=>'SingleProductPage',
        'ClassroomsLandingPage'=>'ClassroomsLandingPage'
        
    );
	
	
    static $many_many = array(
        'ProductPromoItem' => 'ProductPromoItem'
    );
    public static $summary_fields = array(
        'Name'=>'Name',
        'CTA_Text' => 'CTA_Text',
        'CTA_Link' => 'CTA_Link',
        'CTA_Title' => 'CTA_Title',

    );

    public static $searchable_fields = array(
        'Name'=>'Name',

    );
	
    // The global link for the object

    public function getCMSFields() {

        $fields = parent::getCMSFields();

         /** coupon **/
        $fields -> addFieldToTab('Root.Main', new UploadField($name = 'CouponImage', $title = 'Upload a Coupon Image'));
        $fields -> addFieldToTab('Root.Main', new TextField('Coupon_Link'));
        /** Product **/
        $fields -> addFieldToTab('Root.Main', new UploadField($name = 'DefaultImage', $title = 'Upload a Default Image'));


        $fields -> addFieldToTab('Root.Main', new TextField('CTA_Link'));

        return $fields;
    }
}