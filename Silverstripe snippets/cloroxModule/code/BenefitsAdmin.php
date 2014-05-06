<?php
class BenefitsAdmin extends ModelAdmin {
    public static $managed_models = array('ProductBenefit');
    // Can manage multiple models
    static $url_segment = 'ProductBenefits';
    // Linked as /admin/products/
    static $menu_title = 'Benefits Admin';
}
?>