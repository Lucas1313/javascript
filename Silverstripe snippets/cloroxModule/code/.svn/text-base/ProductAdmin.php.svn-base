<?php
class ProductAdmin extends ModelAdmin {

    public static $managed_models = array(
        'Product'=> array('title' => "All Products"),'ProductSubCategory'=> array('title' => "Scents"),'Ingredient', 'ProductListMaintenance'
    );
    // Can manage multiple models

    static $url_segment = 'product';
    // Linked as /admin/products/

    static $menu_title = 'Product Admin';

    static $model_importers = array('Product' => 'ProductOnCsvBulkLoader', 
    'ProductSubCategory' => 'ProductSubCategoryOnCsvBulkLoader',
    'Ingredient'=>'IngredientOnCsvBulkLoader',
    'ProductListMaintenance' => 'ProductListMaintenanceCsvLoader'
    );

    public function getExportFields() {
        
        $allProducts = Product::get();      
        foreach($allProducts as $k=>$product){
            $product->write();
        }
    }

}
?>