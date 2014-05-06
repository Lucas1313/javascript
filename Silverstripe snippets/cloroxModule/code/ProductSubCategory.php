<?php
/*
 * Class ProductSubCategory
 *
 * Describes the Model for a ProductSubCategory (Scent)
 *
 * @author Luc Martin lmartin -at- clorox.com
 * @version $Id: ProductSubCategory.php 29240 2014-02-18 22:54:47Z lmartin $
 *
 * Relationships:
 *
 * hasOne = "Product" => "Product",
 'Logo' => 'Image',
 'image' => 'Image',
 'AlsoLikesItems' => 'AlsoLikeItem'
 * many-one = ProductSubCategories
 * many-many = 'Ingredients' => 'Ingredient',
 'ProductBenefits' => 'ProductBenefit'
 *
 */
class ProductSubCategory extends DataObject {

    static $db = array(
        'Title' => 'Text',
        'Name' => 'Text', // The name for internal purpose
        'Display_Name' => 'Text', // The display Name for the Product Sub Category
        'Scent' => 'Text', // The display Name for the Product Sub Category
        'Description' => 'Text', // The long description
        'Legal_Name' => 'Text', // The legal name
        'Code_Name' => 'Text', // Generated Code name as per Clorox API
        'Sort_Order_Sub_Cat' => 'Int', // drag and drop order
        'Related_Product' => 'Text', // Product related to this Product Sub Category
        'From_Api' => 'Boolean',
        'Bazard_Voice_ID' => 'Text',
        'UPC' =>'Varchar'
    );

    static $has_one = array(

        'Product' => "Product", // The related Product
        'Logo' => 'Image', // the logo (scent logo)
        'DefaultImage' => 'Image', // The image for the scent bottle
        'AlsoLikesItems' => 'AlsoLikeItem' // The also like for that sub Product
    );

    static $has_many = array('Containers' => 'Container');

    static $many_many = array(
        'Ingredients' => 'Ingredient',
        'ProductBenefits' => 'ProductBenefit',
        'SingleProductPage' => 'SingleProductPage',
        'UseFor' => 'UseFor'
    );

    // Drag and Drop Sorting variables
    public static $default_sort = 'Sort_Order_Sub_Cat';

    // Drag and Drop Sorting variables
    public static $many_many_extraFields = array('Ingredients' => array('sortOrderIngredients' => 'Int'));

    // Drag and Drop Sorting function
    public function Ingredients() {
        return $this -> getManyManyComponents('Ingredients') -> sort('sortOrderIngredients');
    }

    // import and display fields
    public static $summary_fields = array(
        'ID' => 'ID',
        'Scent'=>'Scent',
        'Product.ID'=>'ProductID',
        'Product.Title'=>'Product',
        'UPC'=>'UPC',
        'Name' => 'Name',
        'From_Api' => 'From_Api',
        'Display_Name' => 'Display_Name',
        'Legal_Name' => 'Legal_Name',
        'Code_Name' => 'Code_Name',
        'Description' => 'Description'
    );

    // The CMS fields generation
    public function getCMSFields() {

        $fields = parent::getCMSFields();

        $fields -> removeFieldfromTab('Root.Main', 'ProductID');
        $fields -> removeFieldfromTab('Root.Main', 'Sort_Order_Sub_Cat');
        $fields -> removeFieldfromTab('Root.Main', 'Name');
        $fields -> removeFieldfromTab('Root.Main', 'AlsoLikesItemsID');
        $fields -> removeFieldfromTab('Root.Main', 'Related_Product');
        $fields -> removeFieldfromTab('Root.Main', 'From_Api');
        $fields -> addFieldToTab('Root.Main', new TextField('Display_Name'));

        // Create a Checkbox field for all scents
        $cssClasses_Controller = new CssClasses_Controller();
        $AllScentsField = $cssClasses_Controller -> allScentsClasses();

        $fields -> addFieldToTab('Root.Main', $AllScentsField);

        //*****************  Bazard Voice
        $allBvIds = explode(',', BV_REVIEW_PRODUCT_IDS);
        $bvField = new DropdownField('Bazard_Voice_ID', 'Bazard_Voice_ID', $allBvIds);
        $bvField -> setEmptyString('(Select one)');
        $fields -> addFieldToTab('Root.Main', $bvField);

        //***************** Code_Name AS PER API DATABASE CONVENTION
        //$fields -> addFieldToTab('Root.Main', new TextField('Code_Name'));
        $fields -> addFieldToTab('Root.Main', new TextField('Code_Name', 'Codename'));

        //***************** From API
        $fields -> addFieldToTab('Root.Main', new LiteralField('From API IMport', '<div id="From_Api" class="field"><label class="left" for="middleColumn">Imported From Clorox API</label><div class="middleColumn"><div class="readOnlyText" style="font-weight: bold; font-size: 12px; height: 100%; padding: 8px 0 8px 7px; width: 500px; border: 1px solid #999; border-radius: 5px;">' . $this -> From_Api . '</div></div></div>'));

        $fields -> addFieldToTab('Root.Main', new TextField('Title'));
        $fields -> addFieldToTab('Root.Main', new TextField('Legal_Name'));
        $fields -> addFieldToTab('Root.Main', new TextareaField('Description'));
        $fields -> addFieldToTab('Root.Main', new TextField('UPC'));
        $fields -> addFieldToTab('Root.Main', new UploadField($name = 'Logo', $title = 'Upload the Logo for that Scent'));

        $fields -> addFieldToTab('Root.Main', new UploadField($name = 'DefaultImage', $title = 'Upload the Default Product Image for that Scent'));

        //************************* Use

        $UseForField = new GridField('UseFor', 'UseFor', $this -> UseFor(), GridFieldConfig_RelationEditor::create() -> addComponents(new GridFieldDeleteAction('unlinkrelation')));
        $fields -> addFieldToTab('Root.UseFor', $UseForField);

        /************************* Associated Product *********/

        // add a gridfield without the delete button to display the related product
        $product = Product::get() -> filter('ID', $this -> Product() -> ID);

        $gridFieldConfig = GridFieldConfig::create() -> addComponents(new GridFieldToolbarHeader(), new GridFieldSortableHeader(), new GridFieldDataColumns(), new GridFieldEditButton(), new GridFieldDetailForm());

        $ProductField = new GridField('Product', 'Associated to Product', $product, $gridFieldConfig);

        // Create a tab named "scentS" and add our field to it

        $fields -> addFieldToTab('Root.Main', $ProductField);

        /************************* Product Benefits *********/

        // Create a gridfield to hold the HowTos relationship

        $productBenefitField = new GridField('ProductBenefits', 'ProductBenefits', $this -> ProductBenefits(), GridFieldConfig_RelationEditor::create());

        // Create a tab named "HowTos" and add our field to it

        $fields -> addFieldToTab('Root.ProductBenefits', $productBenefitField);

        /** end Product Benefits **/

        /************************* Ingredients *********/

        $conf = GridFieldConfig_RelationEditor::create(10);
        $conf -> addComponent(new GridFieldSortableRows('sortOrderIngredients'));

        $IngredientsField = new GridField('Ingredients', 'Ingredients', $this -> Ingredients(), $conf);

        $fields -> addFieldToTab('Root.Ingredients', $IngredientsField);

        /*************************  Containers  *********/

        $ContainersField = new GridField('Containers', 'Containers', $this -> Containers(), GridFieldConfig_RelationEditor::create());

        $fields -> addFieldToTab('Root.Containers', $ContainersField);

        /** end Containers **/

        return $fields;
    }

    /**
     * function fillDisplayName
     *
     * Method that will fill up the display name if the content editor has forget to do it
     * @param void
     * @return void
     */
    public  function fillDisplayName() {
        $display_name = $this -> Display_Name;
        if (empty($display_name)) {
            $this -> Display_Name = html_entity_decode($this -> Legal_Name);
        }

    }

    /**
     * function fillRelatedProduct
     *
     * Method that will fill up the related product field on save
     * TODO reverse operation on csv import (associate with a Product)
     */
    public  function fillRelatedProduct() {
        $related_product = $this -> Related_Product;
        if (empty($related_product)) {
            $this -> Related_Product = $this -> Product() -> Code_Name;
        }

    }

    /**
     * Method called before an Object is saved
     * Will synchronise the "specific uses for the product with the selected checkboxes"
     * @param none
     * @return void
     */
    public function onBeforeWrite() {
        $this -> fillDisplayName();
        $this -> fillRelatedProduct();

        parent::onBeforeWrite();
    }

    /**
    * Name: function Scent
    *
    * Description: Output the scent and default to no-scent
    * @ID
    * @autor
    **/

    public function Scent(){
        if(empty($this->Scent)){
            $this->Scent = 'no-scent';
        }
        return $this->Scent;
    }
    /**
    * Name: function Scent
    *
    * Description: Output the scent and default to no-scent
    * @ID
    * @autor
    **/

    public function friendlyname(){
        // Create a Checkbox field for all scents
        $cssClasses_Controller = new CssClasses_Controller();
        $AllScentsField = $cssClasses_Controller -> allScentsClasses(false);

        return $AllScentsField[$this->Scent];
    }

    public function Code_Name(){
        $strManipulator = new StringManipulator_Controller();
        $Code_Name = $strManipulator -> generateCodeName($this -> Code_Name);
        if($this -> Code_Name != $Code_Name){
            $this -> Code_Name = $Code_Name;
            $this->write();
        }
        return $this -> Code_Name;
    }


}
