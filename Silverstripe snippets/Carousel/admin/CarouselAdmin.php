<?php
class CarouselAdmin extends ModelAdmin {
    public static $managed_models = array('Carousel', 'CarouselSlide');
    // Can manage multiple models
    static $url_segment = 'carousels';
    // Linked as /admin/products/
    static $menu_title = 'Carousels Admin';
}
?>