This Module will generate a set of nested accordions in a dataObject or Page CMS admin area.
Use:

A) Install the module on the root level of your Silverstripe installation:
[Silverstripe ROOT]/NestedDataObjectField/(4 files)



/***  1) Generate a second level dataObject: ***/

<?php 
	class SecondLevelObject extends DataObject{
	
	static $db = array(
		'Name' => 'Varchar' , 
		'Description' => 'HTMLText'
	); 
	
	static $has_one = array(
		'firstLevelDataObject' => 'FirstLevelObject'
	);
		
	static $many_many = array(
		'thirdLevelObjects'=>'ThirdLevelObject'
	);
}
	
/*** 2) Generate a Third Level Object ***/

<?php 
	class ThirdLevelObject extends DataObject{
	
	static $db = array(
		'Name' => 'Varchar' , 
		'Description' => 'HTMLText'
	); 
	
	static $has_one = array(
		'firstLevelDataObject' => 'FirstLevelObject'
	);
	
	static $belong_many_many = array('secondLevelObjects'=>'SeccondLevelObject');
}

/*** 3) The Parent (first level) Object ***/
	
<?php 

	class FirstLevelObject extends DataObject{
	
	static $db = array(
		'Name' => 'Varchar' , 
		'Description' => 'HTMLText'
	); 
	
	static $has_many = array(
		'secondLevelObjects'=>'SeccondLevelObject'
	);
	
	
	//the cms fields
    public function getCMSFields() {

        $fields = parent::getCMSFields();
        
        // Generate the nested DataObject Accordion
        
        $fields -> addFieldToTab('Root.Main', new NestedDataObjectField('Nested_secondLevelObjects', 'secondLevelObjects', array(
            'parent' => $this,
            'parentClass' => 'firstLevelObject',
            'object' => 'secondLevelObjects',
            'objectClass' => 'SecondLevelObject',
            'fields' => array(
                'Name'=>'TextField',
                'Description'=>'TextField'
            ),
            'parentId' => $this -> ID,
            'addImageInfo' => array(
                'Icon' => array(
                    'path' => '/assets/Uploads/products/scents/icons',
                    'extension' => 'png'
                ),
                'Scent' => array(
                    'path' => '/assets/Uploads/products/scents',
                    'extension' => 'png'
                )
            ),
            recursions => array(
            
            	//************************** This is to Nest the third level Object in each of the second level objects ********  
            	          	
	            'object' => 'thirdLevelObjects',
	            'objectClass' => 'ThirdLevelObject',
	            'fields' => array(
	                'Name'=>'TextField',
	                'Description'=>'HTMLEditorField'
	            ),
	            'addImageInfo' => null, // no image info
	            recursions => null // you can keep going with as many recursion as your system can deal with...
            )
        )));
        
        return $fields;
    }
    
    
/*** 4) Update the Nested Object ***/

    /**
     * Method called before an Object is saved

     * @param none
     * @return void
     */
    public function onBeforeWrite() {
    	// Update all changes in the Nested Objects
        NestedDataObjectField::updateNestedDataObjects($this);

        parent::onBeforeWrite();
    }
    
/*** 5) Add new Nested Objects ***/

    /**
     * Method called After an Object is saved

     * @param none
     * @return void
     */
     
    function onAfterWrite() {
        parent::onAfterWrite();    
		// Saves New NestedDataObjects
        NestedDataObjectField::generateNewNestedDataObjectItem($this);

    }
} // end of the parent object


