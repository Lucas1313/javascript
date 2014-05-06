<?php
class UsesAdmin extends ModelAdmin {
    public static $managed_models = array(
        'UseOn','UseInRoom','UseFor'
    );
    // Can manage multiple models
    static $url_segment = 'Uses';
    static $menu_title = 'Uses Admin';

    static $model_importers = array('UseOn' => 'UseOnCsvBulkLoader' );

}
?>