<?php
/*
 * Class ProductStoreFinderPage
 *
 * Describes the Model for a ProductStoreFinderPage (Container for a PRODUCT)
 *
 * @author Kody Smith -at- clorox.com
 * @version $Id:
 *
 *
 */
class ProductStoreFinderPage extends Page {

    static $db = array(
        'Category_Class' => 'Text',
        'Title' => 'Text',
    );

    static $has_one = array(
		  'Product' => 'Product',
	);

    static $many_many = array(
    );

    public static $many_many_extraFields = array(
    );

  

    public function getCMSFields() {


        $fields = parent::getCMSFields();

        // remove unnecessary fields
        $fields -> removeFieldFromTab('Root.Main', 'Content');


        return $fields;

    }

    /**
     * Method called before an Object is saved
     * Will synchronise the "specific uses for the product with the selected checkboxes"
     * @param none
     * @return void
     */
    public function onBeforeWrite() {

        parent::onBeforeWrite();

    }

    public function getIfAdmin() {
        $member = Member::currentUser();
        if (Permission::checkMember($member, "ADMIN")) {
            return true;
        }
        else {
            return false;
        }
    }

}

class ProductStoreFinderPage_Controller extends Page_Controller {
	
    public function init() {
    	if(isset($_REQUEST['productID'])){
			$this -> product = Product::get()->filter('id',$_REQUEST['productID'])->first();
			//$this -> product = Product::get()->first();
			//error_log(print_r($this->product,1));
		}else{
			$this -> product = Product::get()->first();
		}
        
        Requirements::javascript("js/pages/find-store-page.js");
		//Requirements::javascript("js/single-product-page.js");
        parent::init();

    }
	public function getProductCategories(){
		$uses = UseFor::get();
		
		$retArIds = array(
			'Cleaning & Sanitizing',
			'Cleaning, Sanitizing & Disinfecting',
			'Cleaning, Deodorizing & Sanitizing',
			'Cleaning',
			'Cleaning & Deodorizing HIDDEN BY MAYA',
			'Cleaning & Deodorizing',
		);
		$retArray = array();
		foreach ($uses as $key => $use) {			
			if(!in_array($use->Display_Name, $retArIds)){
				$retArIds[] = $use->Display_Name;
				$retArray[] = $use;
			}
		}
		//print_r($retArray);
		$retArray = new ArrayList($retArray);
		return $retArray;
	}
	public function getProductsByCategory($category){
		/**
		 * Switch statement to give more wiggle room for finding the correct calls
		 * This is not needed if the proper item is called for
		 */
		switch(strtolower($category)){
			case ('cleaning_disinfecting'):
				$productList = $this->ProductsCleaningDisinfecting();
				//$category='Cleaning_Disinfecting';
			break;
			case ('Bathroom'):
				//$productList = $productsPage->ProductsBathroom();
				$productList = $this->ProductsBathroom();
				//$category='Bathroom';
			break;
			case ('do_laundry'):
				$productList = $this->ProductsLaundry();
				//$category='Doing_Laundry';
			break;
			
		}
		
		return $productList;
	}
/*
	public function ProductsCleaningDisinfectingSorted() {
        return Product::get() -> sort(array('Display_Name' => 'ASC'));
    }

    public function ProductsLaundrySorted() {
        return Product::get() -> sort(array('Display_Name' => 'ASC','SortOrderLaundry'));
    }

    public function ProductsBathroomSorted() {
        return Product::get() -> sort(array('Display_Name' => 'ASC','SortOrderBathroom'));
    }
    /**
     * embedSmartSEO function
     * renders SEO content for bots from cached Amazon
     * cloudfront service. Uses bvModule_Controller_SmartSEO
     * in www/bvModule/code/controller to accomplish this
     *
     * @param none
     * @return string - html seo content
     */
    public function embedSmartSEO() {


        return $smartSEOContent;

    }

}
