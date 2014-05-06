<?php
class ProductFamily extends DataObject {
        static $db = array(
            'Name' => 'Varchar'
        );

        static $has_one = array(
            'Icon' => 'Image',
            'ProductFamilyPromoItem' => 'ProductFamilyPromoItem'
        );

        static $many_many = array(
            'Products' => 'Product'
        );
        public function poductFamilyName(){
            return str_replace(" ", "",$this->Name);
        }
}
