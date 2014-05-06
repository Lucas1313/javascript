<?php
class FaqAdmin extends ModelAdmin {
    public static $managed_models = array('FaqCategory','Faq');
    // Can manage multiple models
    static $url_segment = 'FAQs';
    // Linked as /admin/products/
    static $menu_title = 'FAQs Admin';
}
?>