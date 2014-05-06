<?php
class PromoAdmin extends DataObjectAsPageAdmin {
    public static $managed_models = array('ProductFamilyPromoItem', 'ProductPromoItem');
    // Can manage multiple models
    static $url_segment = 'PromoAdmin';
    // Linked as /icks/products/
    static $menu_title = 'Promo Admin';
}
 