<?php
/*
 * Class SingleProductPage
 *
 * Describes the Model for a SingleProductPage (Container for a PRODUCT)
 *
 * @author Luc Martin lmartin -at- clorox.com
 * @version $Id: SingleProductPage.php 29240 2014-02-18 22:54:47Z lmartin $
 *
 *
 */
class SingleProductPage extends Page {

    static $db = array(
        'Category_Class' => 'Text',
        'AssociatedProduct' => 'Text',
        'AssociatedProductSubcategory' => 'Text',
        'Title' => 'Text',
        'ExcludeFromMenu' => 'Boolean',
        'PromoItem' => 'Text'
    );

    static $has_one = array(
	);

    static $many_many = array(
        'Product' => 'Product',
        'AlsoLikeItems' => 'AlsoLikeItem',
        'ProductSubCategory' => 'ProductSubCategory',
        'UniversalSlideShows' => 'UniversalSlideShow'
    );

    public static $many_many_extraFields = array(
        'AlsoLikeItems' => array('SortOrderSingleProductPage' => 'Int', ),
        'UniversalSlideShows' => array('SortOrderUniversalSlideShow' => 'Int')
    );

    /**
     * function getAllProducts
     * Get all ProductSubcategories and generate an array with their Name and ID
     * */
    public  function getAllProductsSubcategory() {

        // grab all the prodSub from the database
        $allProducts = ProductSubCategory::get();
        // init the array that will store the results
        $allProductArray = array();

        foreach ($allProducts as $k => $value) {
            // build the array
            $allProductArray[$value -> ID] = $value -> Display_Name;
        }
        return $allProductArray;
    }
	
	
    /**
     * function associateProductToPage
     * Helper that removes and re-creates relationships with Products ProductSubcategories and the SingleProductpage
     * Uses the selection from the dropbox where content editors choose the ProductSubCategory
     * **/

    public  function associateProductToPage() {

        // Retreive the ProductSubCat from the database using the selection in the dropbox
        $productSubCategoryItems = ProductSubCategory::get() -> filter(array('ID' => $this -> AssociatedProductSubcategory));

        // Process the items
        foreach ($productSubCategoryItems as $key => $productSubCategory) {

            //error_log('found A ProductSubCategory TO ASSOCIATE TO '.$value->ProductID);

            // extract the ID
            $productID = $productSubCategory -> ProductID;
            // find the Product related to the ID
            $productItems = Product::get() -> filter(array('ID' => $productID));
            // iterate and keep only the last one
            foreach ($productItems as $key => $product) {
                //error_log('found A PRODUCT TO ASSOCIATE TO '.$v->Name);
                $this -> AssociatedProduct = $product -> ID;
            }
        }

        // test if there is an associated product
        if ($this -> AssociatedProduct) {
            // grab the product item from the database
            $myProduct = Product::get() -> filter(array('ID' => $this -> AssociatedProduct)) -> First();

            // Cleanup the datagrid and the relationship
            // there should be only one product listed here
            if ($this -> Product() !== $myProduct && !empty($myProduct)) {
                foreach ($this->Product() as $key => $value) {
                    $this -> Product() -> remove($value);
                }
                // add the Product to the page
                $this -> Product() -> add($myProduct);
            }
        }
        // the same operation but on productSubCategory
        if ($this -> AssociatedProductSubcategory) {

            $myProductSubCategory = ProductSubCategory::get() -> filter(array('ID' => $this -> AssociatedProductSubcategory)) -> First();

            if ($this -> ProductSubCategory() !== $myProductSubCategory && !empty($myProductSubCategory)) {

                foreach ($this->ProductSubCategory() as $key => $value) {
                    $this -> ProductSubCategory() -> remove($value);
                }

                $this -> ProductSubCategory() -> add($myProductSubCategory);
            }
        }
    }
	
    public function getCMSFields() {

        // build the DropdownField
        $allProductSubcategoryArray = $this -> getAllProductsSubcategory();

        $fields = parent::getCMSFields();

        // remove unnecessary fields
        $fields -> removeFieldFromTab('Root.Main', 'Content');

        //***************** Category CLASSES Checkboxes
        $cssClassController = new CssClasses_Controller();
        $fields -> addFieldToTab('Root.Main', $cssClassController -> productCategoryClasses('Category_Class'));

        $fields -> addFieldToTab('Root.Main', new HeaderField('ExcludeFromMenuHeader', 'Exclude that page from Menu!</h3><p>Check that box to exclude that page from the Menu</p>'));
        $fields -> addFieldToTab('Root.Main', new checkboxField('ExcludeFromMenu', 'Exclude From Menu'));
		
		//********* Associate a product promo with this page
		$fields -> addFieldToTab('Root.Main', new HeaderField('AddPromoItem', 'AddPromoItem'));
		/** Promo Type defines which CSS to use for the given promo **/
		/**
		 * TODO: make these values into data objects that can be searched through / assigned images
		 * 		This is hard coded, because brand says that They arn't going to add the inline promo items 
		 * 		in the product page
		 */
		$fields -> addFieldToTab('Root.Main', new DropdownField('PromoItem','Promo Item',
			array(
				''=>'None',
				'100MoreCleaning' => 'Cleaning Power',
				'2xmorebrightening' => '2x more brightening',
				'33More' => '33% More Disinfecting',
				'disinfect2x' => 'Disinfects 2x the surface',
				'999germkilling' => '99.9% germ killing',
			)		
		));
        //********* The drop down to associate the page with a productSubCat
        $allProductsSubcategory = new DropdownField('AssociatedProductSubcategory', 'Associated product (with this page)', $allProductSubcategoryArray);
        // this field needs a description
        $allProductsSubcategory -> setRightTitle('This also associates the default scent, so choose accordingly. ' . '<br>To edit additional scents associated with the chosen product, ' . 'edit the associated product itself. <br>Values will appear in ' . 'the fields below once saved.');
        $allProductsSubcategory -> setEmptyString('(Select one Scent)');
        $fields -> addFieldToTab('Root.Main', $allProductsSubcategory);

        //********* Product and ProductSubcategory fields (feedback only)
        $gridFieldConfig = GridFieldConfig::create() -> addComponents(new GridFieldToolbarHeader(), new GridFieldSortableHeader(), new GridFieldDataColumns(), new GridFieldPaginator(10), new GridFieldEditButton(), new GridFieldDetailForm());

        $ProductField = new GridField('Product', 'Product', $this -> Product(), $gridFieldConfig);

        $fields -> addFieldToTab('Root.Main', $ProductField);

        $gridFieldConfig = GridFieldConfig::create() -> addComponents(new GridFieldToolbarHeader(), new GridFieldSortableHeader(), new GridFieldDataColumns(), new GridFieldPaginator(10), new GridFieldEditButton(), new GridFieldDetailForm());

        $ProductField = new GridField('ProductSubCategory', 'Default Scent', $this -> ProductSubCategory(), $gridFieldConfig);

        $fields -> addFieldToTab('Root.Main', $ProductField);

        $AlsoLikeItemsField = new GridField('AlsoLikeItems', 'AlsoLikeItems', $this -> AlsoLikeItems(), GridFieldConfig_RelationEditor::create() -> addComponents(new GridFieldSortableRows('SortOrderSingleProductPage'), new GridFieldDeleteAction('unlinkrelation')));
        $fields -> addFieldToTab('Root.Main', $AlsoLikeItemsField);

        //***************** Universal Slideshows
        $fields -> addFieldToTab('Root.Main', new HeaderField('UniversalSlideShowsHeader', 'Universal Slideshow </h3><p>To Add a Slideshow, add Universal Slides to this panel</p><h3>'));
        $Field = new GridField('UniversalSlideShows', 'UniversalSlideShows', $this -> UniversalSlideShows(), GridFieldConfig_RelationEditor::create() -> addComponents(new GridFieldSortableRows('SortOrderUniversalSlideShow'), new GridFieldDeleteAction('unlinkrelation')));
        $fields -> addFieldToTab('Root.Main', $Field);
		
        return $fields;

    }

    public function AlsoLikeItems() {
        return $this -> getManyManyComponents('AlsoLikeItems') -> sort('SortOrderSingleProductPage');
    }

    public function UniversalSlideShows() {
        return $this -> getManyManyComponents('UniversalSlideShows') -> sort('SortOrderUniversalSlideShow');
    }

    /**
     * Method called before an Object is saved
     * Will synchronise the "specific uses for the product with the selected checkboxes"
     * @param none
     * @return void
     */
    public function onBeforeWrite() {

        $this -> associateProductToPage();
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

class SingleProductPage_Controller extends Page_Controller {

    public function init() {

        Requirements::javascript("js/single-product-page.js");

        parent::init();

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

        // we need the product as it stores the BV id
        $thisProduct = Product::get() -> filter(array('ID' => $this -> AssociatedProduct)) -> First();

        // get full URI minus query string
        $currentPage = explode('?', URI::getURI());
        $currentPage = $currentPage[0];

        // get class instance
        $smartSEO = new bvModule_Controller_SmartSEO;
        // this will return SEO content or an empty string
        // depending upon user agent
        $smartSEOContent = $smartSEO -> insert_bv_seo(BV_DISPLAY_CODE, $thisProduct -> BV_Id, 'reviews', $currentPage, 'product', BV_SMART_SEO_KEY,
        // is this staging?
        ($GLOBALS['currentEnvironment'] == 'ENV_PRODUCTION' ? false : true));

        return $smartSEOContent;

    }

}
