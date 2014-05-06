<?php
/*
 * ProductsPage
 *
 * Describes the Model for a ProductPage
 * The product page is a top Level Object in the Taxonomy
 * Also updates the system using data from the Clorox Api
 *
 * @author Luc Martin lmartin -at- clorox.com
 * @version $Id: ProductsPage.php 29750 2014-03-15 01:34:26Z ksmith $
 *
 * Relationships:
 * one-many =
 * many-one = Products
 * many-many =
 *
 */
class ProductsPage extends Page {

    static $allowed_children = array(
        'ProductCategoryPage',
        'ProductsNewPage',
        'SingleProductPage',
        'CouponPreprintPage',
        'GenericPage',
        'SmartTubeTechnologyPage',
        'ProductStoreFinderPage'
    );
    // Restrict the children page type

    static $db = array(
        'Title' => 'Text',
        'Date' => 'Date', // Release date
        'Author' => 'Text', // Author of the page
        'UPC' => 'Varchar'
    );

    static $has_many = array(
        'Products' => 'Product',
        'ProductsCleaningDisinfecting' => 'Product',
        'ProductsLaundry' => 'Product',
        'ProductsBathroom' => 'Product',
        'ProductsNav' => 'Product'
    );
    // All Products

    static $many_many = array('AlsoLikesItems' => 'AlsoLikeItem');

    // Generated Also Like Item

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

        // Remove unnecessary fields
        $fields -> removeFieldFromTab('Root.Main', 'Content');

        //************************ RELEASE DATE
        $dateField = new DateField('Date');
        $dateField -> setConfig('showcalendar', true);
        $fields -> addFieldToTab('Root.Main', $dateField, 'Content');

        //************************ UPC
        $fields -> addFieldToTab('Root.Main', new TextField('UPC'));

        //************************* PRODUCTS
        // setup the grid
        $allProducts = $this -> Products();
        //Product::get();
        $allProductsLaundry = $this -> ProductsLaundry();
        $allProductsCleaningDisinfecting = $this -> ProductsCleaningDisinfecting();
        $allProductsBathroom = $this -> ProductsBathroom();
        $allProductsNav = $this -> ProductsNav() -> filter(array('Add_To_Global_Nav' => true));

        $gridFieldConfig = GridFieldConfig::create() -> addComponents(new GridFieldSortableRows('SortOrderProducts'), new GridFieldToolbarHeader(), new GridFieldAddNewButton('toolbar-header-right'), new GridFieldSortableHeader(), new GridFieldDataColumns(), new GridFieldPaginator(500), new GridFieldEditButton(), new GridFieldDeleteAction(), new GridFieldDetailForm(), new GridFieldAddExistingAutocompleter('toolbar-header-left'), new GridField('Products'));

        $ProductsField = new GridField('Products', 'Products', $allProducts, $gridFieldConfig);
        $fields -> addFieldToTab('Root.Main', $ProductsField);

        //************************* Sorting Products

        // *** Cleaning and Disinfecting
        $gridFieldConfig2 = GridFieldConfig::create() -> addComponents(new GridFieldSortableRows('SortOrderCleaningDisinfecting'), new GridFieldToolbarHeader(), new GridFieldAddNewButton('toolbar-header-right'), new GridFieldSortableHeader(), new GridFieldDataColumns(), new GridFieldPaginator(500), new GridFieldEditButton(), new GridFieldDeleteAction(), new GridFieldDetailForm(), new GridFieldAddExistingAutocompleter('toolbar-header-left'), new GridField('ProductsCleaningDisinfecting'));

        $ProductsField2 = new GridField('ProductsCleaningDisinfecting', 'Products Cleaning and Disinfecting Sortable', $allProductsCleaningDisinfecting, $gridFieldConfig2);
        $fields -> addFieldToTab('Root.Sorting.CleaningDisinfecting', $ProductsField2);

        // *** Laundry

        $gridFieldConfig = GridFieldConfig::create() -> addComponents(new GridFieldSortableRows('SortOrderLaundry'), new GridFieldToolbarHeader(), new GridFieldAddNewButton('toolbar-header-right'), new GridFieldSortableHeader(), new GridFieldDataColumns(), new GridFieldPaginator(500), new GridFieldEditButton(), new GridFieldDeleteAction(), new GridFieldDetailForm(), new GridFieldAddExistingAutocompleter('toolbar-header-left'), new GridField('ProductsLaundry'));

        $ProductsField = new GridField('ProductsLaundry', 'Products Doing Laundry Sortable', $allProductsLaundry, $gridFieldConfig);
        $fields -> addFieldToTab('Root.Sorting.Laundry', $ProductsField);

        // *** Bathroom

        $gridFieldConfig = GridFieldConfig::create() -> addComponents(new GridFieldSortableRows('SortOrderBathroom'), new GridFieldToolbarHeader(), new GridFieldAddNewButton('toolbar-header-right'), new GridFieldSortableHeader(), new GridFieldDataColumns(), new GridFieldPaginator(500), new GridFieldEditButton(), new GridFieldDeleteAction(), new GridFieldDetailForm(), new GridFieldAddExistingAutocompleter('toolbar-header-left'), new GridField('ProductsBathroom'));

        $ProductsField = new GridField('ProductsBathroom', 'Products Bathroom Sortable', $allProductsBathroom, $gridFieldConfig);
        $fields -> addFieldToTab('Root.Sorting.Bathroom', $ProductsField);

        // *** Navigation
        $gridFieldConfig2 = GridFieldConfig::create() -> addComponents(new GridFieldSortableRows('SortOrderNav'), new GridFieldToolbarHeader(), new GridFieldAddNewButton('toolbar-header-right'), new GridFieldSortableHeader(), new GridFieldDataColumns(), new GridFieldPaginator(500), new GridFieldEditButton(), new GridFieldDeleteAction(), new GridFieldDetailForm(), new GridFieldAddExistingAutocompleter('toolbar-header-left'), new GridField('ProductsNav'));

        $ProductsField2 = new GridField('ProductsNav', 'Set the order of items in the sub nav', $allProductsNav, $gridFieldConfig2);
        $fields -> addFieldToTab('Root.Sorting.Navigation', $ProductsField2);

        //************************* ALSO LIKE

        $AlsoLikesItemsField = new GridField('AlsoLikesItems', 'AlsoLikesItems', $this -> AlsoLikesItems(), GridFieldConfig_RelationEditor::create() -> addComponents(new GridFieldDeleteAction('unlinkrelation')));
        $fields -> addFieldToTab('Root.Main', $AlsoLikesItemsField);

        return $fields;
    }

    public function generateSectionName() {
        foreach ($_REQUEST as $key => $value) {
            $_REQUEST[$key] = Convert::raw2sql($value);
        }

        if (isset($_REQUEST["show"])) {
            return strtolower($_REQUEST["show"]);
        }
        else {
            return 'allproducts';
        }

    }

    public function ProductFamilies() {
        return ProductFamily::get();
    }

    public function ProductsSorted() {
        return Product::get() -> sort(array('SortOrderProducts' => 'ASC'));
    }

    public function ProductsCleaningDisinfectingSorted() {
        return Product::get() -> sort('SortOrderCleaningDisinfecting');
    }

    public function ProductsLaundrySorted() {
        return Product::get() -> sort('SortOrderLaundry');
    }

    public function ProductsBathroomSorted() {
        return Product::get() -> sort('SortOrderBathroom');
    }

    public  function init() {
        //Use this function if the product list is empty it will repopulate from the API
        // I will disable this since we don't need to add the products anymore

        // $CloroxApi_Controller = new CloroxApi_Controller();
        // $CloroxApi_Controller -> importProductsFromApi($this);
    }

    function generateAlsoLikeForAllProducts() {

        $alsoLikeItem_Controller = new AlsoLikeItem_Controller();
        $alsoLikeItem_Controller -> updateAllAlsoLikeItems();

        return;
        $allProducts = Product::get();

        foreach ($allProducts as $key => $product) {
            $alsoLikeItem_Controller = new AlsoLikeItem_Controller();

            $page = SingleProductPage::get() -> filter('AssociatedProduct', $product -> ID) -> first();

            if (!empty($page -> Title)) {

                $alsoLikeItem_Controller -> createAlsoLikeItem('Product', $product, $product -> Title, $product -> Intro_Description, $product -> Image(), $page -> Link());

            }
        }
    }

    function rebuiltProductList() {
        // Rebuilt the product list in the product page
        // enable to re-import all products

        $allProducts = Product::get();
        $products = $this -> Products();
        $ProductsCleaningDisinfecting = $this -> ProductsCleaningDisinfecting();
        $ProductsLaundry = $this -> ProductsLaundry();
        $ProductsBathroom = $this -> ProductsBathroom();
        /*
         foreach ($products as $key => $product) {
         $this -> Products() -> remove($product);
         $this->ProductsCleaningDisinfecting() -> remove($product);
         $this->ProductsLaundry() -> remove($product);
         $this->ProductsBathroom() -> remove($product);
         }
         * */
        foreach ($allProducts as $key => $product) {
            $this -> Products() -> add($product);
            $this -> ProductsCleaningDisinfecting() -> add($product);
            $this -> ProductsLaundry() -> add($product);
            $this -> ProductsBathroom() -> add($product);
        }

    }

    function rebuiltUses() {

        $allUseFor = UseFor::get();

        foreach ($allUseFor as $key => $UseFor) {
            $product = Product::get() -> filter('Title', $UseFor -> Name) -> first();
            $product -> UseFor() -> add($UseFor);
            $product -> write();
        }
    }

    /**
     * Method called before an Object is saved
     * Will synchronise the "specific uses for the product with the selected checkboxes"
     * @param none
     * @return void
     */
    public function onBeforeWrite() {

        $this -> rebuiltProductList();
        parent::onBeforeWrite();
    }

}

class ProductsPage_Controller extends Page_Controller {

    public function init() {

        //Combine!
        Requirements::javascript("js/plugins/jquery.simplefilter.js");
        Requirements::javascript("js/libs/dropdown-filter.js");
        Requirements::javascript("js/product-page.js");

        parent::init();

    }

    /**
     * countUseFor function
     * Purpose Returns a count for the quantity of products in relation with their uses
     *
     * @author Luc Martin at Clorox.com
     * @version $ID
     */
    public function countUseFor($useForName, $useInRoom = null) {
        $count = 0;
        $ret = '';
        $products = $this -> Products();
        foreach ($products as $key => $product) {
            $usesFor = $product -> UseFor();
            foreach ($usesFor as $key => $useFor) {

                if ($useFor -> Name == $useForName) {
                    if (empty($useInRoom)) {
                        ++$count;
                    }
                    else {
                        $room = $useFor -> UseInRooms() -> filter('Name', $useInRoom) -> first();

                        if (!empty($room -> Name)) {
                            ++$count;
                        }
                    }

                }
            }
        }
        return $count;
    }

}
