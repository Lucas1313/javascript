<?php
/*
 * ProductsPage
 *
 * Describes the Model for a ProductPage
 * The product page is a top Level Object in the Taxonomy
 * Also updates the system using data from the Clorox Api
 *
 * @author Luc Martin lmartin -at- clorox.com
 * @version $Id: ProductsPage.php 23759 2013-07-19 22:38:56Z rgoodman $
 *
 * Relationships:
 * one-many =
 * many-one = Products
 * many-many =
 *
 */
class ProductSelectorPage extends Page {
	public  $_Product, $_Substances, $_Surfaces;
    // Restrict the children page type

    static $db = array(
        'Title' => 'Text',
        'Subtitle' => 'HTMLText', // Release date
        'CTA_Title' => 'Text',
        'CTA_Text' => 'Text',
        'CTA_Link' => 'Text'
    );

    static $has_many = array(
        'Products' => 'Product',
        'Surfaces' => 'TagProductSelector',
        'Substances' => 'TagProductSelector'
    );

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

        $fields = parent::getCMSFields();

        // Remove unnecessary fields
        $fields -> removeFieldFromTab('Root.Main', 'Content');

        $fields -> addFieldToTab('Root.Main', new TextField('Title'));
        $htmleditor = new HtmlEditorField('Subtitle');

        $fields -> addFieldToTab('Root.Main', $htmleditor);
        $htmleditor -> setRows(5);

        $fields -> addFieldToTab('Root.Main', new TextField('CTA_Title'));
        $fields -> addFieldToTab('Root.Main', new TextField('CTA_Text'));
        $fields -> addFieldToTab('Root.Main', new TextField('CTA_Link'));

        //************************* Products Sorted
        $conf = GridFieldConfig_RelationEditor::create(300);

        // unlink button
        $conf -> addComponents(new GridFieldDeleteAction('unlinkrelation'));

        // drag and drop
        $conf -> addComponent(new GridFieldSortableRows('SortOrderProductSelector'));

        // the grid
        $ProductField = new GridField('Products', 'Products', $this -> Products(), $conf);
        $fields -> addFieldToTab('Root.Main', $ProductField);

        //************************* Tags Surfaces Sorted

        $conf = GridFieldConfig_RelationEditor::create(300);

        // unlink button
        $conf -> addComponents(new GridFieldDeleteAction('unlinkrelation'));

        // drag and drop
        $conf -> addComponent(new GridFieldSortableRows('SortOrderProductSelectorSurface'));

        // the grid
        $field = new GridField('Surfaces', 'Surfaces', $this -> Surfaces(), $conf);
        $fields -> addFieldToTab('Root.SurfacesAndSubstances', $field);

        //************************* Tags Substances Sorted

        $conf = GridFieldConfig_RelationEditor::create(300);

        // unlink button
        $conf -> addComponents(new GridFieldDeleteAction('unlinkrelation'));

        // drag and drop
        $conf -> addComponent(new GridFieldSortableRows('SortOrderProductSelectorSubstance'));

        // the grid
        $field = new GridField('Substances', 'Substances', $this -> Substances(), $conf);
        $fields -> addFieldToTab('Root.SurfacesAndSubstances', $field);

        return $fields;

    }



    function Products() {
    	if(!empty($this->_Product)){
    		//error_log('using local variable');
    		if(!empty($this->_Product)){
    			return $this->_Product;
    		}
    	}else{
			if(isset($_SESSION['_Product'])){
				return $_SESSION['_Product'];
			}
		}
        return $this->_Product = Product::get() -> sort('SortOrderProductSelector');
		//return Product::get() -> sort('SortOrderProductSelector');
		 /**/
    }

    function Substances() {
		if(is_object($this->_Substances)){
    		if(!empty($this->_Substances)){
    			return $this->_Substances;
    		}
    	}
        return $_Substances = TagProductSelector::get() -> filter(array('Tag_Type' => 'Substance'));
		//return TagProductSelector::get() -> filter(array('Tag_Type' => 'Substance'));
    }

    function Surfaces() {
		if(is_object($this->_Surfaces)){
    		if(!empty($this->_Surfaces)){
    			return $this->_Surfaces;
    		}
    	}
        return $this->_Surfaces = TagProductSelector::get() -> filter(array('Tag_Type' => 'Surface'));
    }

}

class ProductSelectorPage_Controller extends Page_Controller {

    public function init() {

        //Combine!

        Requirements::javascript("js/plugins/jquery.productSelector.js");
        Requirements::javascript("js/pages/productSelectorPage.js");

        parent::init();

    }

}
