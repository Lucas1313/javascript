<?php
class SingleIckPage extends Page {
    static $db = array(
        'Title'=>'Text',
        'Associated_Ick' => 'Text',
        'Date' => 'Date',
        'Author' => 'Text'
    );
    static $has_many = array('IcktionaryItems' => 'IcktionaryItem');
    static $many_many = array('AlsoLikeItems' => 'AlsoLikeItem');

    public function getCMSFields() {

        $allIcksArray = $this -> getAllIcks();

        $this -> associateIckToPage();
        $fields = parent::getCMSFields();

        $fields -> removeFieldFromTab('Root.Main', 'Content');

        $fields -> addFieldToTab('Root.Main', new DropdownField('Associated_Ick', 'Associated_Ick', $allIcksArray));

        $dateField = new DateField('Date');
        $dateField -> setConfig('showcalendar', true);
        $fields -> addFieldToTab('Root.Main', $dateField, 'Content');
        $fields -> addFieldToTab('Root.Main', new TextField('Author'), 'Content');

        return $fields;
    }

    public  function getAllIcks() {
        $allIcks = IcktionaryItem::get();
        $allIcksArray = array(0 => 'Choose Ick');
        foreach ($allIcks as $k => $value) {

            $UsableName = $value -> UsableName;
            $allIcksArray[$value -> ID] = $value -> Display_Name;
        }
        return $allIcksArray;
    }

    function associateIckToPage() {
        if ($this -> AssociatedProduct) {

            $myProduct = IcktionaryItem::get() -> filter(array('ID' => $this -> Associated_Ick)) -> First();

            if ($this -> IcktionaryItem() !== $newIck && !empty($newIck)) {

                foreach ($this->IcktionaryItems() as $key => $value) {
                    $this -> IcktionaryItems() -> remove($value);
                }

                $this -> IcktionaryItems() -> add($newIck);
            }
        }
    }

}

class SingleIckPage_Controller extends Page_Controller {
}
?>