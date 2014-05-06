<?php
/*
 * Class OurHistoryPage
 *
 * Describes the Model for a CommitementPage
 *
 * @author Luc Martin lmartin -at- clorox.com
 * @version $Id
 */
class SpecialOffersPage extends Page {
    static $db = array(

        'Title' => 'Varchar',
        'Is_New_delay'=>'Int',
        'Ending_Warning'=>'Int',
        'Expiring_Warning'=>'Int'

    );

    static $has_many = array(
        'SpecialOffers' => 'SpecialOffer'

    );
	public function getSpecialOffers(){
		$SpecialOffers = $this->SpecialOffers();
		
		$SpecialOffersArray = array();
		foreach($SpecialOffers as $SpecialOffer){
			$SpecialOffersArray[] = $SpecialOffer;
		}
		return new ArrayList($SpecialOffersArray);
	}
    public function getCMSFields() {

        $fields = parent::getCMSFields();
        $fields -> removeFieldFromTab('Root', 'Content');

        $fields -> addFieldToTab('Root.Main', new TextField('Title'));
        $fields -> addFieldToTab('Root.Main', new TextField('Is_New_delay'));
        $fields -> addFieldToTab('Root.Main', new TextField('Ending_Warning'));
        $fields -> addFieldToTab('Root.Main', new TextField('Expiring_Warning'));
        //***************** History Panels
        $SpecialOffersPanelField = new GridField('SpecialOffers', 'SpecialOffers', $this -> SpecialOffers(), GridFieldConfig_RelationEditor::create() -> addComponents(new GridFieldSortableRows('SortOrder'), new GridFieldDeleteAction('unlinkrelation')));
        $fields -> addFieldToTab('Root.Main', $SpecialOffersPanelField);


        return $fields;

    }

}

class SpecialOffersPage_Controller extends Page_Controller {



    public function init() {

        parent::init();
    }

}
