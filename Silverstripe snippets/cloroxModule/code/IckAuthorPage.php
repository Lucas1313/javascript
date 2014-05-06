<?php
class IckAuthorPage extends DataObjectAsPageHolder{
    
}
class IckAuthorPage_Controller extends DataObjectAsPageHolder_Controller{
    //This needs to know be the Class of the DataObject you want this page to list
    static $item_class = 'IckAuthor';
    //Set the sort for the items (defaults to Created DESC)
    static $item_sort = 'Name ASC';
}
