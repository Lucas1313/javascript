<?php
class ProductFamilyPromoItem extends DataObject {
    static $db = array(
        'Name' => 'Varchar',
        'CTA_Link' => 'Text',
        'CTA_Text' => 'Text'
    );

    static $has_one = array(
        'HeaderImage' => 'Image',
        'ProductFamilyImage' => 'Image'
    );

    static $belongs_to = array(
    	'ProductFamily' => 'ProductFamily'
    );
}