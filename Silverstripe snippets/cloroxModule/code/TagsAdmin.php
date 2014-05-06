<?php
/**
 * class TagsAdmin extends ModelAdmin
 *
 * The administration for the Tags
 *
 * @author Luc Martin lmartin -at- clorox.com
 * @version $Id: TagsAdmin.php 25489 2013-09-17 22:32:09Z ksmith $
 *
 */
class TagsAdmin extends ModelAdmin {

    public static $managed_models = array(
        'TagGeneral',
        'TagFeatures',
        'TagNeed',
        'TagType',
        'TagProductSelector'
    );

    static $model_importers = array('TagProductSelector' => 'TagProductSelectorCsvBulkLoader');

    // Can manage multiple models
    static $url_segment = 'Tags';
    static $menu_title = 'Tagging Admin';

}
?>