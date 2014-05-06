<?php
class UniversalSlideShowsAdmin extends ModelAdmin {
    public static $managed_models = array('UniversalSlideShow', 'UniversalSlide');
    // Can manage multiple models
    static $url_segment = 'universalslideshow';
    // Linked as /admin/products/
    static $menu_title = 'Universal show Admin';
}
?>