<?php
class ProductPromoItem extends DataObject {
    static $db = array(
        'Name'=>'Varchar',
        'CTA_Title' => 'HTMLText',
        'CTA_Link'=>'Text',
        'Coupon_Link'=>'Text',
        'PromoType'=>'Text',
        
    );
    public static $default_sort='SortOrder';

    static $has_one = array(
        'CouponImage' => 'Image',
        'DefaultImage' => 'Image',
        'AssociatedProductPage'=>'SingleProductPage'
    );
    static $many_many = array(
        'IcktionaryItem' => 'IcktionaryItem',
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
		
		/** Promo Type defines which CSS to use for the given promo **/
		$fields -> addFieldToTab('Root.Main', new DropdownField('PromoType','Promo Type',
			array(
				'None',
				'Upper_Right_Value_Messaging',
				'Upper_Left_Value_Messaging',
			)		
		));
		
        $fields -> addFieldToTab('Root.Main', new TextField('CTA_Link'));

        return $fields;
    }
}