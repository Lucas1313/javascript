<?php
class BLMAdmin extends ModelAdmin {
    public static $managed_models = array('BLMPromo','BLMfaq','BLMTip','BLMGifPromo','BLMPromo','BLMEcard','BLMSolve');
    // Can manage multiple models
    static $url_segment = 'BLAdmin';
    // Linked as /admin/AlsoLikes/
    static $menu_title = 'BLM Admin';
}