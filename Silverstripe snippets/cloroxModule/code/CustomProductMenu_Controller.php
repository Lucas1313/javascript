<?php
/**
 * CustomProductMenu_Controller
 * methods to assist in rendering a category based
 * menu
 *
 * @author jason ware jason.ware -at- clorox.com
 * @package cloroxModule.code
 * @version $Id: CustomProductMenu_Controller.php 22869 2013-06-10 17:04:18Z lmartin $
 */
class CustomProductMenu_Controller extends DataExtension {

    public function init() {
        parent::init();
    }

    /**
     * ProductMenu function
     * hierarchically categorizes products into
     * groups based on the Category_Class field.
     * They are then assigned to an object that
     * can be parsed by SS.
     *
     * @return ArrayList $categoryDataSet
     */
    public function getProductMenu() {

        // select all product pages and return
        // data from SiteTree and SingleProductPage
        $sqlQuery = new SQLQuery();
        $sqlQuery->setFrom('SingleProductPage');
        $sqlQuery->addLeftJoin('SiteTree','"SingleProductPage"."ID" = "SiteTree"."ID"');

        // Execute and return a Query object
        $productPages = $sqlQuery->execute();


        /***********************************
        / loop through the result to create
        / a new categorized array
        ************************************/
        $categorized['Category'] = array();
        foreach($productPages as $page) {
           // get a list of categories for the
           // current product. This is not great to work with
           // but it seems the data is not structured properly
           $categoryList = $page['Category_Class'];
           $categories = explode(',',$categoryList);
           // add the product to the
           // categories in it's own list
           foreach( $categories as $key=>$category ){
                if(!empty($category)){
                   $categorized['Category'][$category][] = $page;
                }
           }

        }


        // since we have created a category array hierarchy
        // lets get a dynamic representation of the category object
        $classesController = new CssClasses_Controller;
        $categoryClassArray = $classesController->productCategoryClasses();
        $categoryClassArray = $categoryClassArray->getSource();

        /****************************************
        / convert the array to an object that
        / can be used by silverstripe templates
        *****************************************/
        $categoryDataSet = new ArrayList();
        // loop through the highest categorized level
        foreach($categorized['Category'] as $key=>$pagesCategorized) {
            // create a unique data object to write
            $tempObject = new ArrayList();
            // key is all we have that is category
            // related at the moment
            $tempObject->Category = $key;
            $tempObject->CategoryName = $categoryClassArray[$key];

            // for the iterator value
            $i=0;

            // need to push the product data to a structure
            // that ss will accept. This is very touchy and precise.
            // You can't just parse out an array in a template
            $tempObject->Products = new ArrayList();
            foreach($pagesCategorized as $page){

                // set a limit of 5 results
                // per category
                if( $i < 8 || 1==1 ){

                    // super ugly hack !!! :(
                    // but Title was set on this extension of page
                    // so it could not be parsed out in the templates
                    $page['Product_Name'] = str_replace('Regular Bleach1', 'Regular Bleach<sub>1 </sub>', $page['Title']);
                    $page['Product_Name'] = str_replace('[Sub', '<sub>', $page['Product_Name']);
                    $page['Product_Name'] = str_replace('Sub]', '</sub>', $page['Product_Name']);
                    unset($page['Title']);

                    // set an iterator per category
                    $page['Product_Iterator'] = $i;

                    // generate this level of the object with
                    // the modification above made to the "Title" key
                    if($page['ExcludeFromMenu'] == false){

                        $tempObject->Products->push(new DataObject($page));
                    }

                }

                // increment iterator
                $i++;

            }
            $tempObject->Products->sort('Product_Name');
            // push it all into the master object that
            // will be parsed out in the template
            $categoryDataSet->push($tempObject);
        }

        return $categoryDataSet;
    }

}