<?php
/*
 * ProductsMaintenancePage
 *
 * Describes the Model for a Product¡sMaintenancePage
 * 
 *  Updates the system using data from the Clorox Api
 *
 * @author Luc Martin lmartin -at- clorox.com
 * @version $Id: ProductsMaintenancePage.php 29240 2014-02-18 22:54:47Z lmartin $
 *
 * Relationships:
 * one-many =
 * many-one = Products
 * many-many =
 *
 */
class ProductsMaintenancePage extends Page {

    /**
     * function getCMSFields
     *
     * Form fields for the CMS system
     *
     * @param null
     * @return $fields Form fields for the CMS
     */
    public function getCMSFields() {

        $this -> init();
        $fields = parent::getCMSFields();

        return $fields;
    }

    public  function init() {
        $CloroxApi_Controller = new CloroxApi_Controller();
        //$CloroxApi_Controller -> importProductsFromApi($this);
    }

}

class ProductsMaintenancePage_Controller extends Page_Controller {
    public function init() {

        echo('INIT CALLED IN MAINTENANCE PAGE !!! <br/>');

        $CloroxApi_Controller = new CloroxApi_Controller();
        $allProductsAndProductSubcategoryFromApi = $CloroxApi_Controller -> importProductsFromApi($this, false);

        $allProductSubcategoryFromApi = $allProductsAndProductSubcategoryFromApi['allProductSubcategory'];
        $allProductSubCategory = ProductSubCategory::get();

        $iterator = 0;
        foreach ($allProductSubCategory as $key => $productSubCategory) {

            $code_Name = $productSubCategory->Code_Name;

            foreach ($allProductSubcategoryFromApi as $key => $productSubcategoryFromApi) {

               if($productSubcategoryFromApi[0]['Code_Name'] == $code_Name){
                     $productSubCategory -> From_Api = true;
                    echo(++$iterator.'  - SUBPRODUCT BELONG TO API : '.$code_Name.'<br/>');
                    $productSubCategory ->write();
                
                }

            }
            
               
        }
echo('<hr/>');
        $allProductsFromApi = $allProductsAndProductSubcategoryFromApi['allProducts'];
        $allProducts = Product::get();
        $iteratorProduct = 0;
        foreach ($allProducts as $key => $product) {

            if(!empty($allProductsFromApi[$product->Code_Name])){
                $product -> From_Api = true;
                echo(++$iteratorProduct .'  - PRODUCT BELONG TO API : '.$product->Code_Name.'<br/>');
                $product ->write();
            }
        }
       
        parent::init();
    }

}
