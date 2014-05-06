<?php
/**
 * Class IcktionaryItemPage
 * Description The Page that displays all the ICKS
 * @author Luc Martin lmartin -at- clorox.com
 * @version $Id:
 */
class IcktionaryItemPage extends Page {
	public $icktionaryItems;
    static $has_many = array('Items' => 'IcktionaryItem');

    public function getCMSFields() {
        $fields = parent::getCMSFields();

        //************************* Icks
        //************************* PRODUCTS
        // setup the grid
        $allIcks = $this -> Items();
        //Product::get();
        $gridFieldConfig = GridFieldConfig::create() -> addComponents(new GridFieldToolbarHeader(), new GridFieldAddNewButton('toolbar-header-right'), new GridFieldSortableHeader(), new GridFieldDataColumns(), new GridFieldPaginator(1500), new GridFieldEditButton(), new GridFieldDeleteAction('unlinkrelation'), new GridFieldDetailForm(), new GridFieldAddExistingAutocompleter('toolbar-header-left'), new GridFieldSortableRows('SortOrder'));

        $IckField = new GridField('Products', 'Products', $allIcks, $gridFieldConfig);
        $fields -> addFieldToTab('Root.Main', $IckField);

        $fields -> removeFieldFromTab('Root.Main', 'Content');

        return $fields;
    }

    public function onBeforeWrite() {

        $ickItems = IcktionaryItem::get();
        $alsoLikeItem_Controller = new AlsoLikeItem_Controller();

        foreach ($ickItems as $key => $ick) {

            $page = $ick -> IckSinglePage();
            $this -> Items() -> add($ick);

            if (!empty($page -> Title)) {

                $alsoLikeItem_Controller -> createAlsoLikeItem('Ick', $ick, $ick -> Title, $ick -> Definition, $ick -> Image(), $page -> Link());

            }

        }

        parent::onBeforeWrite();
    }
    /*
     * function IckItems
     * Description this is the first display of Icks for the page,
     * The lazy Loader will then load using the Request switch situated in Page.php
     */
    public function IckItems() {
    	
		if(empty($icktionaryItems)){
			$limit = 20;
	        if(!empty($_REQUEST['limit'])){
	            $limit = $_REQUEST['limit'];
	        }
	        $items = IcktionaryItem::get() -> exclude('Exclude_From_Menu',1) -> sort('SortOrder')->limit($limit);
	
	        $this->icktionaryItems =  $items;
		}
		
		return $this->icktionaryItems;
        

    }

    /**
     * function RequestType
     * Description will provide the template with a feedback about the request tagType
     */
    public function RequestType() {
        if (isset($_REQUEST['tagType'])) {
            return $_REQUEST['tagType'];
        }
        else {
            return null;
        }
    }

    /**
     * function getAllIcks
     * description: Provides the Template SS file with all Icks
     * Organized for the IckMenu
     */
    public function getAllIcks($sort = 'Title') {

        $itsTheFirstLetter = 1;
        $initDone = false;
        $oldLetter = '';
        $icks = IcktionaryItem::get() -> exclude('Exclude_From_Menu',1) -> sort('Title ASC');

        $ret = '';

        foreach ($icks as $key => $ick) {

                $title = $ick -> Title;
                $firstLetter = strtolower(substr($title, 0, 1));

                if ($firstLetter !== $oldLetter && $itsTheFirstLetter == 1) {
                    $ret .= '<span class="slideItem">';
                    $ret .= '<h1>' . strtoupper($firstLetter) . '</h1>';
                    $ret .= '<ul>';
                    $itsTheFirstLetter = 0;
                }
                elseif ($firstLetter !== $oldLetter) {
                    $ret .= '</ul>';
                    $ret .= '</span>';
                    $ret .= '<span class="slideItem">';
                    $ret .= '<h1>' . strtoupper($firstLetter) . '</h1>';
                    $ret .= '<ul>';
                }

                $ret .= '<li><a href="#' . str_replace(' ', '', $title) . '">' . $title . '</a></li>';

                $oldLetter = $firstLetter;


        }

        $ret .= '</ul>';
        $ret .= '</span>';

        return $ret;
    }



}

class IcktionaryItemPage_Controller extends Page_Controller {

    //Set the sort for the items (defaults to Created DESC)
    static $item_sort = 'SortOrder';

    /**
     * function sortIcks
     * Definition: Will generate a new Ick order depending of the day of the week.
     * Order the Icks in the IckAdmin area, then the function will switch them in certain days of the week
     * The top Ick will go to the end of line
     */
    public  function sortIcks() {
        $lastUpdate = IcktionaryItem::get() -> first() -> sortedDate;
        $sortedDate = Date('U', strtotime($lastUpdate));
        $today = Date('U', strtotime('today'));
        $dayOfWeek = Date('N', strtotime('today'));

        $dayOfWeekToSwitchIck = array(
            1,
            3,
            5
        );

        if ((empty($lastUpdate) || $sortedDate !== $today) && in_array($dayOfWeek, $dayOfWeekToSwitchIck)) {

            $icks = IcktionaryItem::get() -> sort('SortOrder', 'ASC');

            foreach ($icks as $key => $ick) {
                if ($ick -> SortOrder == 1) {

                    $ick -> SortOrder = count($icks);

                }
                else {

                    $sortOrder = $ick -> SortOrder;
                    $ick -> SortOrder = --$sortOrder;
                }
                $ick -> sortedDate = $today;
                $ick -> write();
            }
        }
    }
    /**
     * function init
     * Definition Initialize the page
     */
    function init() {
        // get the artists for the Ick
        if (!empty($_GET['illustrator'])) {
            $illustrator = $_GET['illustrator'];
            $icktionaryItems = IcktionaryItem::get();
            $allIcksAr = array();

            foreach ($icktionaryItems as $key => $ick) {
                $author = $ick -> IckAuthor() -> filter(array('Name' => $illustrator)) -> first();
            }

        }
        // Sort the Icks depending of the day of the week
        $this -> sortIcks();

        // loads Javascript
        Requirements::javascript("js/pages/icktionary.js");

        parent::init();

    }

}
