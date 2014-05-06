<?php
class IckAdmin extends ModelAdmin {
    public static $managed_models = array('IcktionaryItem','IckAuthor','IckIllustrator');
    // Can manage multiple models
    static $url_segment = 'IckAdmin';
    // Linked as /icks/products/
    static $menu_title = 'Ick Admin';
    
    public function getEditForm($id = null, $fields = null) {
        
     $form = parent::getEditForm($id, $fields);
        $gridField = $form->Fields()->fieldByName($this->sanitiseClassName($this->modelClass));
        $gridField->getConfig()->addComponent(new GridFieldSortableRows('SortOrder'),new GridFieldPaginator(500));
        return $form;
 
     }
    
}
