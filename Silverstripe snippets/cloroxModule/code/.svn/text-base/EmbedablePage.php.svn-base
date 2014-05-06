<?php
/*
 * Class EmbedablePage
 *
 * Allows other sites to embed content from our site and display on theirs
 *
 * @author Kody Smith -at- clorox.com
 * @version $Id
 */
class EmbedablePage extends Page {
    static $db = array(
      'Template' => 'Varchar',
    );

 
	static $has_one = array(
	);

    static $many_many = array(
    	'DataObjects' => 'DataObject'
    );


	public function getCMSFields() {
        $fields = parent::getCMSFields();
		$fields -> addFieldToTab('Root.Main', new CheckboxField('NoHeaderFooter','Remove header / footer: ', 1));
		
		
		
		$fields -> addFieldToTab('Root.Main', new DropdownField('Template','Template',$this->getTemplateArray()));
		//$fields -> addFieldToTab('Root.Main', new TextField('Template','Template'));
		//$fields -> removeFieldFromTab('Root.Main', 'Content');
		
        return $fields;
    }
	public function getTemplateArray(){
		$dir = '../themes/clorox/templates/Includes/';
		$files = scandir($dir);
		$fileArray = array();
		foreach($files as $file){
			if(strpos($file,'.ss')>=1){
				$fileEdit = split('.ss',$file);
				$fileArray[] = $fileEdit[0];
			}
		}
		return $fileArray;
	}
	public function pageReference() {
        return $this -> getManyManyComponents('pageReference');
    }
	public function getObject(){
		return $this -> getManyManyComponents('DataObjects');	
	}
}

class EmbedablePage_Controller extends Page_Controller {

    public function init() {
		
        parent::init();
		if(isset($this->Template) && strlen($this->Template)>0 ){
			$this->renderWith($this->Template);
		}else{
			return $this->Content;
		}
    }

}
