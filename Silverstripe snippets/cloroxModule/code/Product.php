<?php
/*
 * Product
 *
 * Describes the Model for a Product
 *
 * @author Luc Martin lmartin -at- clorox.com
 * @version $Id: Product.php 29959 2014-03-25 00:00:18Z ksmith $
 *
 * Relationships:
 *
 * hasOne = Image, ProductPage, SingleProductPage, ProductCategoryPage, AlsoLikeItem
 * many-one = ProductSubCategories
 * many-many = FAQs, ProductBenefits, TagFeatures, TagNeed, TagType
 *
 */
class Product extends DataObject {
    public function init() {
        $this -> updateTagFields();
    }

    static $db = array(
        'Title' => 'Text',
        'Add_To_Global_Nav' => 'Boolean', //appears in global navigation sub menu
        'Display_Name' => 'HtmlText', //The name cleaned up includes special characters
        'Code_Name' => 'Text', //The Code_Name, from Clorox API naming convention
        'BV_Id' => 'Text',
        'Publication_Date' => 'Date', //The date the product should be published
        'Slogan' => 'Text', // The "h2" right under the title of the product
        'Hide_In_Product_Page' => 'Boolean',
        'Default_In_Product_Page_Cleaning_Disinfecting' => 'Boolean',
        'Default_In_Product_Page_Doing_Laundry' => 'Boolean',
        'Default_In_Product_Page_Bathroom' => 'Boolean',

        'Intro_Description' => 'HTMLText', //A more detailled description
        'CTA_Title' => 'Text', //Call to action text
        'CTA_Text' => 'Text', //Call to action text
        'CTA_URL' => 'Text', //Call to action link
        'All_Product_Sub_Category' => 'Text', //Text field with All related ProductSubCategory (scents) in a CSV text
        'All_Tags_General' => 'Text',
        'All_Tags_Features' => 'Text',
        'All_Tags_Need' => 'Text',
        'All_Tags_Type' => 'Text',
        'Sort_Order_Product' => 'Int', //Drag anddrop order
        'Related_Use_On' => 'Text', //A text field tracking all the useOn for the product
       // 'Related_Use_In' => 'Text', //A text field tracking all the UseInRoom for the product
        'Related_Use_For' => 'Text', //A text field tracking all the RelatedUses for the product
        'New_Product' => 'Boolean',
        'Add_To_New_Product_Page' => 'Boolean',
        'Title_Class' => 'Varchar',
        'Product_Page_Link' => 'Text',
        'Material_Safety_Data_Sheet_URL' => 'Text',
        'SortOrderProducts' => 'Int',
        'SortOrderLaundry' => 'Int',
        'SortOrderCleaningDisinfecting' => 'Int',
        'SortOrderBathroom' => 'Int',
        'SortOrderSmarttube' => 'Int',
        'SortOrderProductSelector' => 'Int',
        'SortOrderNav' => 'Int',

        'AssociatedProduct'=>'HTMLText',
        'AssociatedProductLink'=>'HTMLText',
        'AssociatedProductHeader'=>'HTMLText',

        'AssociatedProduct1'=>'HTMLText',
        'AssociatedProductLink1'=>'HTMLText',
        'AssociatedProductHeader1'=>'HTMLText',

        'AssociatedProduct2'=>'HTMLText',
        'AssociatedProductLink2'=>'HTMLText',
        'AssociatedProductHeader2'=>'HTMLText',

        'AssociatedProduct3'=>'HTMLText',
        'AssociatedProductLink3'=>'HTMLText',
        'AssociatedProductHeader3'=>'HTMLText',
    );

    static $has_one = array(
        'BV_Image' => 'Image',
        'Image' => 'Image', //Product Image
        'Product_Page' => 'ProductsPage', // Page where all the products should be displayed
        'SmartTubeTechnologyPage' => 'SmartTubeTechnologyPage',
        'ProductPromoItem'=>'ProductPromoItem',
        'ProductSelectorPage'=>'ProductSelectorPage',
        'AssociatedProductIcon'=>'Image',
        'AssociatedProductIcon1'=>'Image',
        'AssociatedProductIcon2'=>'Image',
        'AssociatedProductIcon3'=>'Image',
    );

    static $many_many = array(
        'UseFor' => 'UseFor', // The use For (example: Cleaning)
        'FaqCategory' => 'FaqCategory', // All facs for the product
        'ProductBenefits' => 'ProductBenefit', // All benefits for that product
        'TagGeneral' => 'TagGeneral', // Tags Features
        'TagFeatures' => 'TagFeatures', // Tags Features
        'TagNeed' => 'TagNeed', // Tag for Needs
        'TagType' => 'TagType', // Tag for Type
        'FeaturePanel' => 'FeaturePanel', // The Product Featured data
        'Parent_Page' => 'SingleProductPage', // The single page for that product(Set by hand)
        'Product_Category_Page' => 'ProductCategoryPage', // The product category page that has that product
        'relatedSubstances' => 'TagProductSelector',
        'relatedSurfaces' => 'TagProductSelector',
        
    );

    static $belongs_many_many = array(
        'ProductFamilies' => 'ProductFamily'
    );

    static $has_many = array(
        'AlsoLikeItem' => 'AlsoLikeItem',
        'ProductSubCategories' => 'ProductSubCategory' //Sub Categories for the Product (Scents)
    );

    public static $many_many_extraFields = array(
        'FeaturePanel' => array('SortOrderFeaturePanel' => 'Int')
    );

    public function FeaturePanel() {
        return $this -> getManyManyComponents('FeaturePanel') -> sort('SortOrderFeaturePanel');
    }
    // Fields used for the display of info in the CSV and The grids
    static $summary_fields = array(
        'ID',
        'SortOrderProducts',
        'SortOrderBathroom',
        'Title',
        'BV_Id',
        'Code_Name',
        'Display_Name',
        'Publication_Date',
        'Slogan',
        'CTA_Text',
        'CTA_URL',
        'All_Product_Sub_Category',
        'All_Tags_Features',
        'All_Tags_Need',
        'All_Tags_Type',
        'SortOrderProductselector'
    );

    // Searchable fields
    static $searchable_fields = array(
        'Display_Name',
        'Code_Name',
        'Publication_Date',
        'Slogan'
    );

    // Drag and Drop ordering
    public static $default_sort = 'Sort_Order_Product';

    /**
     * function returnProductSubCategory
     *
     * @param null
     * @return Array all ProductSubCategory associated with this Product as an array
     */
    public function returnProductSubCategory() {
        $allSub = array();
        foreach ($this->ProductSubCategories() as $k => $v) {
            $allSub[] = $v;
        }
        return $allSub;
    }

    /**
     * function returnSubProducsRelationship
     *
     * @param null
     * @return ProductSubCategory associated with this Product as a query
     */
    public function returnSubProducsRelationship() {
        return $this -> ProductSubCategories();
    }
    public function urlencodedName(){
        return urlencode(str_replace(" ","-",strtolower($this->Title)));
    }
    public function bvDisplayName(){
        $title = $this->Title;
        $title = str_replace("Clorox 2", "Clorox2&#174; ",$title);
        return str_replace("Clorox ","Clorox&#174; ",$title);
    }
    /**
     * function returnUseFor
     *
     * @param null
     * @return useFor associated with this Product as a query
     */
    public function returnUseFor() {
        return $this -> UseFor();
    }

    public function hasProductFamilies() {
        return $this -> ProductFamilies() -> Count() > 0;
    }

    public function getPrimaryProductFamily() {
        return $this -> ProductFamilies() -> first();
    }
	public function getProductFamilies(){
		return ProductFamily::get();
	}

	public function getAssociatedProductPromo(){
		  // grab all the prodSub from the database
        $allProducts = ProductPromoItem::get();
        // init the array that will store the results
        $allProductArray = array();

        foreach ($allProducts as $k => $value) {
            // build the array
            $allProductArray[$value -> ID] = $value -> Name;
        }
        return $allProductArray;
	}
	/**
	 * function getProductPromos
	 * 
	 * description: this function is used to get a list of the current promo items
	 * that are avialable for selection 
	 * 
	 * @return an array list of names / ids
	 */
	public function getProductPromos(){
		$sql = "SELECT ID, Name FROM ProductPromoItem ORDER BY ID DESC";
		$promos = DB::query($sql);
		$promosArray;
		foreach($promos as $value){
			$promosArray[$value['ID']]=$value['Name'];
		}
		
        return $promosArray;
	}
    //the cms fields
    public function getCMSFields() {

        $fields = parent::getCMSFields();
        $this -> init();

        // Remove un-necessary fields
        // these fields are automatically set and don't need to be edited by hand
        $fields -> removeFieldFromTab('Root.Main', 'Content');
        $fields -> removeFieldFromTab('Root.Main', 'From_Api');
        $fields -> removeFieldFromTab('Root.Main', 'All_Product_Sub_Category');
        $fields -> removeFieldFromTab('Root.Main', 'Product_Page');
        $fields -> removeFieldFromTab('Root.Main', 'Parent_Page');
        $fields -> removeFieldFromTab('Root.Main', 'Product_Category_PageID');

        $fields -> removeFieldFromTab('Root.Main', 'Sort_Order_Product');
        $fields -> removeFieldFromTab('Root.Main', 'Parent_PageID');
        $fields -> removeFieldFromTab('Root.Main', 'Product_PageID');

        $fields -> removeFieldsFromTab('Root', array(
            'UseFor',
            'TagFeatures',
            'TagNeed',
            'TagType',
            'Related_Use_On',
            'Related_Use_On',
            'Related_Use_For',
            'All_Product_Sub_Category',
            'TagGeneral',
            'FaqCategory',
            'ProductSubCategories',
            'Product_Page',
            'ProductPage',
            'Parent_Page',
            'ParentPage',
            'Product_Category_Page',
            'ProductCategoryPage',
            'ProductBenefits',
            'RelatedSubstances',
            'RelatedSurfaces'

        ));
        $fields -> addFieldToTab('Root.Main', new checkboxField('New_Product', 'New_Product'));
        $fields -> addFieldToTab('Root.Main', new checkboxField('Add_To_New_Product_Page', 'Add_To_New_Product_Page'));

        // **************** Default_In_Product_Page
        $fields -> addFieldToTab('Root.Main', new checkboxField('Add_To_Global_Nav', 'Add to the subnav dropdown menu (limit: 3)'));
        $fields -> addFieldToTab('Root.Main', new checkboxField('Hide_In_Product_Page', 'Hide In Products Page'));
        $fields -> addFieldToTab('Root.Main', new checkboxField('Default_In_Product_Page_Cleaning_Disinfecting', 'Show in the "Cleaning and Disinfecting" nav'));
        $fields -> addFieldToTab('Root.Main', new checkboxField('Default_In_Product_Page_Doing_Laundry', 'Show in the "Doing Laundry" nav'));
        $fields -> addFieldToTab('Root.Main', new checkboxField('Default_In_Product_Page_Bathroom', 'Show in the "Bathroom" nav'));
		
		// **************** PRODUCT PROMO ITEM
		//$fields -> addFieldToTab('Root.Main', new DropdownField('ProductPromoItem', 'Product Promo',$this->getAssociatedProductPromo()));
		
        //***************** PUBLICATION DATE
        $dateField = new DateField('Publication_Date');
        $dateField -> setConfig('showcalendar', true);
        $fields -> addFieldToTab('Root.Main', $dateField);

        //***************** DISPLAY NAME
        $fields -> addFieldToTab('Root.Main', new TextField('Title'));

        //***************** DISPLAY NAME
        $fields -> addFieldToTab('Root.Main', new TextField('Display_Name'));

        //***************** Product_Page_Link
        $fields -> addFieldToTab('Root.Main', new TextField('Product_Page_Link'));

        //*******************AssociatedProduct
         $fields -> addFieldToTab('Root.Main', new TextField('AssociatedProduct', 'Associated Product 1'));
         $fields -> addFieldToTab('Root.Main', new TextField('AssociatedProductHeader', 'Associated Product 1 Header'));
         $fields -> addFieldToTab('Root.Main', new TextField('AssociatedProductLink', 'Associated Product 1 link'));
         $fields -> addFieldToTab('Root.Main', new UploadField('AssociatedProductIcon', 'Associated Product 1 Icon'));

         //*******************AssociatedProduct
         $fields -> addFieldToTab('Root.Main', new TextField('AssociatedProduct1', 'Associated Product 2'));
         $fields -> addFieldToTab('Root.Main', new TextField('AssociatedProductHeader1', 'Associated Product 2 Header'));
         $fields -> addFieldToTab('Root.Main', new TextField('AssociatedProductLink1', 'Associated Product 2 link'));
         $fields -> addFieldToTab('Root.Main', new UploadField('AssociatedProductIcon1', 'Associated Product 2 Icon'));

         //*******************AssociatedProduct
         $fields -> addFieldToTab('Root.Main', new TextField('AssociatedProduct2', 'Associated Product 3'));
         $fields -> addFieldToTab('Root.Main', new TextField('AssociatedProductHeader2', 'Associated Product 3 Header'));
         $fields -> addFieldToTab('Root.Main', new TextField('AssociatedProductLink2', 'Associated Product 3 link'));
         $fields -> addFieldToTab('Root.Main', new UploadField('AssociatedProductIcon2', 'Associated Product 3 Icon'));

         //*******************AssociatedProduct
         $fields -> addFieldToTab('Root.Main', new TextField('AssociatedProduct3', 'Associated Product 4'));
         $fields -> addFieldToTab('Root.Main', new TextField('AssociatedProductHeader3', 'Associated Product 4 Header'));
         $fields -> addFieldToTab('Root.Main', new TextField('AssociatedProductLink3', 'Associated Product 4 link'));
         $fields -> addFieldToTab('Root.Main', new UploadField('AssociatedProductIcon3', 'Associated Product 4 Icon'));


        //************************ Material Safety Datasheet
        $fields -> addFieldToTab('Root.Main', new TextField('Material_Safety_Data_Sheet_URL', 'Add material Safety Data Sheet if required:'));

        //***************** CLASSES Checkboxes
        $cssClassController = new CssClasses_Controller();
        $fields -> addFieldToTab('Root.Main', $cssClassController -> titleClasses('Title_Class'));

        //***************** BV ID
        $allBvIds = explode(',', BV_REVIEW_PRODUCT_IDS);
        $allBvIdAr = array();

        foreach ($allBvIds as $bv) {
            $allBvIdAr[$bv] = $bv;
        }
        $bvField = new DropdownField('BV_Id', 'BV ID', $allBvIdAr);
        $bvField -> setEmptyString('(Select one)');
        $fields -> addFieldToTab('Root.Main', $bvField);

        //***************** Code_Name AS PER API DATABASE CONVENTION
        $fields -> addFieldToTab('Root.Main', new LiteralField('Code_Name', '<div id="Code_Name" class="field"><label class="left" for="middleColumn">Code Name</label><div class="middleColumn"><div class="readOnlyText" style="font-weight: bold; font-size: 12px; height: 100%; padding: 8px 0 8px 7px; width: 500px; border: 1px solid #999; border-radius: 5px;">' . $this -> Code_Name . '</div></div></div>'));

        //***************** DEFAULT IMAGE (the default image for that class of products)
        $fields -> addFieldToTab('Root.Main', new UploadField($name = 'Image', $title = 'Upload the Product Image'));

        //***************** DEFAULT IMAGE (the default image for that class of products)
        $fields -> addFieldToTab('Root.Main', new HeaderField('BVImageHeader', 'Add a special BV Image if needed </h4><p>This will supercede the product image in the BV Panel</p><h4>'));
        $fields -> addFieldToTab('Root.Main', new UploadField($name = 'BV_Image', $title = 'Upload the Special Image'));

        $fields -> addFieldToTab('Root.Main', new TextAreaField('Slogan'));
        $fields -> addFieldToTab('Root.Main', new HTMLEditorField('Intro_Description'));

        //************************* ProductSubCategories (also called scents)
        $conf = GridFieldConfig_RelationEditor::create(30);

        // unlink button
        $conf -> addComponents(new GridFieldDeleteAction('unlinkrelation'));

        // drag and drop
        $conf -> addComponent(new GridFieldSortableRows('Sort_Order_Sub_Cat'));

        // the grid
        $ProductSubCategoriesField = new GridField('ProductSubCategories', 'Scents', $this -> ProductSubCategories(), $conf);
        $fields -> addFieldToTab('Root.Scents', $ProductSubCategoriesField);

        //***************** CALL TO ACTION
        $fields -> addFieldToTab('Root.Main', new TextField('CTA_Title'));
        $fields -> addFieldToTab('Root.Main', new TextField('CTA_Text'));
        $fields -> addFieldToTab('Root.Main', new TextField('CTA_URL'));

        //************************* PRODUCT FEATURED

        $FeaturePanelField = new GridField('FeaturePanel', 'FeaturePanel', $this -> FeaturePanel(), GridFieldConfig_RelationEditor::create() -> addComponents(new GridFieldSortableRows('SortOrderFeaturePanel'), new GridFieldDeleteAction('unlinkrelation')));
        $fields -> addFieldToTab('Root.FeaturePanel', $FeaturePanelField);

        //************************* FaqCategory

        $FaqField = new GridField('FaqCategory', 'FaqCategory', $this -> FaqCategory(), GridFieldConfig_RelationEditor::create() -> addComponents(new GridFieldDeleteAction('unlinkrelation')));
        $fields -> addFieldToTab('Root.FAQs', $FaqField);

        //************************* PRODUCT BENEFITS

        $productBenefitField = new GridField('ProductBenefits', 'ProductBenefits', $this -> ProductBenefits(), GridFieldConfig_RelationEditor::create() -> addComponents(new GridFieldDeleteAction('unlinkrelation')));
        $fields -> addFieldToTab('Root.ProductBenefits', $productBenefitField);

        //************************* USES

        $UseOnField = new GridField('UseFor', 'UseFor', $this -> UseFor(), GridFieldConfig_RelationEditor::create() -> addComponents(new GridFieldDeleteAction('unlinkrelation')) -> removeComponent('autocompleter'));
        $fields -> addFieldToTab('Root.Uses', $UseOnField);

        //************************* TagGeneral
        $allTags = TagGeneral::get();
        $allTagAr = array();
        foreach ($allTags as $key => $value) {
            $checked = $this -> TagGeneral() -> filter(array('ID' => $value -> ID));
            $allTagAr[$value -> ID] = $value -> Name;
        }

        $fields -> addFieldToTab('Root.Tags.General', new LiteralField('Tag General', '<div style="font-weight:bold; padding:10px;"><h4>Tag General:</h4> Primary Cleaning Objective (To clean or disinfect / to do laundry / to clean toilet)</div>'));
        $TagGeneralField = new GridField('Tag_General', 'To clean or disinfect/to do laundry/to clean toilet', $this -> TagGeneral(), GridFieldConfig_RelationEditor::create() -> addComponents(new GridFieldDeleteAction('unlinkrelation')));
        $fields -> addFieldToTab('Root.Tags.General', $TagGeneralField);
        $fields -> addFieldToTab('Root.Tags.General', new CheckboxSetField($name = 'TagGeneral', $title = 'AvailableTags', $source = $allTagAr));
        $fields -> addFieldToTab('Root.Tags.General', new TextAreaField('All_Tags_General', 'ThisProductTagsGeneral'));

        //************************* TagFeatures
        $allTags = TagFeatures::get();
        $allTagAr = array();
        foreach ($allTags as $key => $value) {
            $checked = $this -> TagFeatures() -> filter(array('ID' => $value -> ID));
            $allTagAr[$value -> ID] = $value -> Name;
        }

        $fields -> addFieldToTab('Root.Tags.Features', new LiteralField('Tag Features', '<div style="font-weight:bold; padding:10px;">Tag Features: Product Attributes (scented / splash-free / concentrated / HE / etc.)</div>'));
        $TagFeaturesField = new GridField('Tag_Features', 'TagFeatures', $this -> TagFeatures(), GridFieldConfig_RelationEditor::create() -> addComponents(new GridFieldDeleteAction('unlinkrelation')));
        $fields -> addFieldToTab('Root.Tags.Features', $TagFeaturesField);
        $fields -> addFieldToTab('Root.Tags.Features', new CheckboxSetField($name = 'TagFeatures', $title = 'AvailableTags', $source = $allTagAr));
        $fields -> addFieldToTab('Root.Tags.Features', new TextAreaField('All_Tags_Features', 'This Product: Tags Features'));

        //************************* TagNeed
        $allTags = TagNeed::get();
        $allTagAr = array();
        foreach ($allTags as $key => $value) {
            $checked = $this -> TagNeed() -> filter(array('ID' => $value -> ID));
            $allTagAr[$value -> ID] = $value -> Name;
        }

        $fields -> addFieldToTab('Root.Tags.Need', new LiteralField('Tag Needs', '<div style="font-weight:bold; padding:10px;">Tag Needs: Task (deodorize / remove stains / whiten / etc.)</div>'));
        $TagNeedField = new GridField('Tag_Need', 'TagNeed', $this -> TagNeed(), GridFieldConfig_RelationEditor::create() -> addComponents(new GridFieldDeleteAction('unlinkrelation')));
        $fields -> addFieldToTab('Root.Tags.Need', $TagNeedField);
        $fields -> addFieldToTab('Root.Tags.Need', new CheckboxSetField($name = 'TagNeed', $title = 'AvailableTags', $source = $allTagAr));
        $fields -> addFieldToTab('Root.Tags.Need', new TextAreaField('All_Tags_Need', 'This Product: Tags Needs'));

        //************************* TagType
        $allTags = TagType::get();
        $allTagAr = array();
        foreach ($allTags as $key => $value) {
            $checked = $this -> TagType() -> filter(array('ID' => $value -> ID));
            $allTagAr[$value -> ID] = $value -> Name;
        }
        $fields -> addFieldToTab('Root.Tags.Type', new LiteralField('Tag Types', '<div style="font-weight:bold; padding:10px;">Tag Types: Form of Product (gel / powder / liquid / spray / etc.)</div>'));
        $TagTypeField = new GridField('Tag_Type', 'TagType', $this -> TagType(), GridFieldConfig_RelationEditor::create() -> addComponents(new GridFieldDeleteAction('unlinkrelation')));
        $fields -> addFieldToTab('Root.Tags.Type', $TagTypeField);
        $fields -> addFieldToTab('Root.Tags.Type', new CheckboxSetField($name = 'TagType', $title = 'AvailableTags', $source = $allTagAr));
        $fields -> addFieldToTab('Root.Tags.Type', new TextAreaField('All_Tags_Type', 'This Product: Tags Type'));
/*
        $fields -> addFieldToTab('Root.Uses', new NestedDataObjectField('All_Uses', 'This Product: Uses', array(
            'parent' => $this,
            'parentClass' => 'Product',
            'object' => 'UseFor',
            'objectClass' => 'UseFor',
            'fields' => array(
                'Name' => 'TextField',
                'Display_Name' => 'TextField'
            ),
            'parentId' => $this -> ID,
            'addImageInfo' => null,
            'recursions' => array(

                //************************** This is to Nest the third level Object in each of the second level objects ********

                'object' => 'UseInRooms',
                'objectClass' => 'UseInRoom',
                'fields' => array(
                    'Name' => 'TextField',
                    'Display_Name' => 'TextField'
                ),
                'addImageInfo' => null, // no image info
                'recursions' => array(
                    'object' => 'UsesOn',
                    'objectClass' => 'UseOn',
                    'fields' => array(
                        'Title' => 'TextField',
                        'Display_Name' => 'TextField',
                        'Instructions' => 'HtmlEditorField',
                        'Disclaimer' => 'HtmlEditorField',
                        'Image_Class' => 'TextField'
                    ),
                    'addImageInfo' => null, // no image info
                    'recursions' => null
                ) // you can keep going with as many recursion as your system can deal with...
            )
        )));
*/
        $tags_Substances= DataObject::get('TagProductSelector')->filter(array('Tag_Type'=>'Substance'));

        if (!empty($tags_Substances) && $this->Tag_Type !== 'Substance') {

            // create an array('ID'=>'Name')
            $map = $tags_Substances->map('ID', 'Name');

            // create a Checkbox group based on the array
            $fields->addFieldToTab('Root.ProductFinderTags',
                new CheckboxSetField(
                    $name = "relatedSubstances",
                    $title = "Select Substance",
                    $source = $map
            ));

        }
        $tags_Surfaces= DataObject::get('TagProductSelector')->filter(array('Tag_Type'=>'Surface'));

        if (!empty($tags_Surfaces)) {

            // create an array('ID'=>'Name')
            $map = $tags_Surfaces->map('ID', 'Name');

            // create a Checkbox group based on the array
            $fields->addFieldToTab('Root.ProductFinderTags',
                new CheckboxSetField(
                    $name = "relatedSurfaces",
                    $title = "Select Surface",
                    $source = $map
            ));

        }


        return $fields;
    }

    /**
     * Method called before an Object is saved
     * Will synchronise the "specific uses for the product with the selected checkboxes"
     * @param none
     * @return void
     */
    public function onBeforeWrite() {

        // Manipulate data before editing
        //String cleanup and manipulation
        $strManipulator = new StringManipulator_Controller();

        // Clean up special characters
        $this -> Display_Name = $strManipulator -> cleanupSpecialChar($this -> Display_Name);
        $this -> Display_Name = htmlentities($this -> Display_Name);
        $this -> Display_Name = html_entity_decode($this -> Display_Name);

        // generate the Code_Name from the ProductName
        $this -> Code_Name = $strManipulator -> generatecodeName($this -> Name);

        // Make sure that the Name field is filled up (necessary for searches)
        if (empty($this -> name)) {
            $this -> name = $this -> Display_Name;
        }
        $this -> updateTagFields();

        // Update the relationship field
        $this -> updateSubProductField();

        // Do we need to add a also like?
        $alsoLikeItem = $this -> AlsoLikeItem() -> first();

        // If yes add a alsoLike
        if (!$alsoLikeItem) {
            // generate the also like item
            $this -> generateAlsoLike();
        }

        parent::onBeforeWrite();
    }
    
    /**
     * function generateAlsoLike
     * will generate a also like item for the product
     */
    public  function generateAlsoLike() {

        $alsoLikeItem_Controller = new AlsoLikeItem_Controller();

        $page = SingleProductPage::get() -> filter('AssociatedProduct', $this -> ID) -> first();

        if (!empty($page -> Title)) {

            $alsoLikeItem_Controller -> createAlsoLikeItem('Product', $this, $this -> Title, $this -> Intro_Description, $this -> Image(), $page -> Link());

        }
    }

    /**
     * Method used while importing/exporting  csv in the Product Management tab, it will add all related ProductSubCategory to a text field
     * it will also add a relationship with ProductSubCategory added to the All_Product_Sub_Category field from a csv import
     *
     * @param null
     * @return void
     */
    public function updateSubProductField() {

        $relationshipImportController = new Relationship_Controller();
        $this -> All_Product_Sub_Category = $relationshipImportController -> updateRelationshipField($this, 'All_Product_Sub_Category', $this -> ProductSubCategories(), 'Code_Name');

    }

    /**
     * function updateTagFields()
     *
     * Method used while importing/exporting  csv in the Product Management tab, it will add all related tags to a text field
     * it will also add a relationship with tag added to the tag field from a csv import
     *
     * @param null
     * @return void
     */
    public function updateTagFields() {

        $relationshipImportController = new Relationship_Controller();

        $this -> All_Tags_General = $relationshipImportController -> updateRelationshipField($this, 'All_Tags_General', $this -> TagGeneral(), 'Name');
        $this -> All_Tags_Features = $relationshipImportController -> updateRelationshipField($this, 'All_Tags_Features', $this -> TagFeatures(), 'Name');
        $this -> All_Tags_Need = $relationshipImportController -> updateRelationshipField($this, 'All_Tags_Need', $this -> TagNeed(), 'Name');
        $this -> All_Tags_Type = $relationshipImportController -> updateRelationshipField($this, 'All_Tags_Type', $this -> TagType(), 'Name');
    }

    public function pageUrl() {
        if (!empty($this -> Product_Page_Link)) {
            return $this -> Product_Page_Link;
        }
        $ret = '';
        $singleProductPage = SingleProductPage::get();

        foreach ($singleProductPage as $key => $productPage) {

            $products = $productPage -> Product() -> filter(array('ID' => $this -> ID));

            foreach ($products as $key => $product) {
                $this -> Product_Page_Link = $productPage -> Link();
                $this -> write();
                return $productPage -> Link();
            }

        }

    }

    public function Display_Name() {
        return str_replace('1', '<small>1</small>', $this -> Display_Name);
    }

	/**
	 * productID function
	 * returns the current product's ID
	 *
	 */
	 public function productID(){
	 	return $this->ID;
	 }
    
    /**
     * starRatingImage function
     * generate a star rating URL based on the current
     * product's BV id. main functionality found in bvModule
     *
     * @return string $bvStarRatingURL or void
     */
    public function starRatingImage() {
        $bvProductId = $this -> BV_Id;
        // only do this if valid so as not to
        // return an error if BV data is out of sync
        if (!empty($bvProductId)) {
            $bvStarRatingURL = bvModule_Controller_HelperMethods::starRatingImage($bvProductId);
            return $bvStarRatingURL;
        }
        //else ss needs return of some sort
        return false;
    }
    public function bvformatImage(){
        $image = $this->Image();
        //$image = new Image;
       if($image instanceof Image){
        return $image;
       }
    }
    /**
     * bvStats function
     * get an object populated with statistics
     * to be used on the template from the method
     * by the same name in bvModule_Controller_HelperMethods
     *
     * @return ArrayList $bvStats or void
     */
    public function bvStats() {
        $bvProductId = $this -> BV_Id;
        // only do this if valid so as not to
        // return an error if BV data is out of sync
        if (!empty($bvProductId)) {
            $bvStats = bvModule_Controller_HelperMethods::bvStats($bvProductId);
            if (!empty($bvStats)) {
                $bvStatDataSet = new ArrayList();
                $bvStatDataSet -> push($bvStats);
                return $bvStatDataSet;
            }
        }
        //else ss needs return of some sort
        return false;
    }

    public function Code_Name() {
        $strManipulator = new StringManipulator_Controller();
        $Code_Name = $strManipulator -> generateCodeName($this -> Title);
        if ($this -> Code_Name != $Code_Name) {
            $this -> Code_Name = $Code_Name;
            $this -> write();
        }
        return $this -> Code_Name;
    }
    public function Codename() {
        $strManipulator = new StringManipulator_Controller();
        return $strManipulator -> generateCodeName($this -> Display_Name);

    }

}
