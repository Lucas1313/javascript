<?php
/**
 * AlsoLikeItem_Controller extends Extension
 * 
 * Class that manages the "Also like" generation and updates
 * This class is usually called at Item creation and updates
 * 
 * @author Luc Martin lmartin -at- clorox.com
 * @version $Id: AlsoLikeItem_Controller.php 25489 2013-09-17 22:32:09Z ksmith $
 */
class AlsoLikeItem_Controller extends Extension {

    /**
     * Method to update all the LikeItems in relation with the object they are related to.
     * Called mostly when a major update is done in the system.
     *
     * @param null
     * @return void
     */
    public function updateAllAlsoLikeItems($itemsToUpdate = null) {
        set_time_limit ( 700 );
        //error_log('Updating the also like item');
        
        $allAlsoLike = AlsoLikeItem::get()->exclude('RelatedObjCategory', 'Ick');;        
        
        foreach ($allAlsoLike as $key => $alsoLikeItem) {
            
            //error_log('iterating');
            $id = $alsoLikeItem->ID;
            
            $productPages = SingleProductPage::get();
            
            foreach ($productPages as $key => $page) {
                //error_log('iterating Pages');
                $alsoLike = $page->AlsoLikeItems()->filter('ID',$id)->first();
                
                if(!empty($alsoLike)){
                    //error_log('NOT Empty');
                    $alsoLikeItem->hasPage = true;
                    $alsoLikeItem->write();
                    break;
                    
                }
                
            }
            if($alsoLikeItem->hasPage == false){
                //error_log('DELETED ');
                $alsoLikeItem->delete();
            }
        }
        return;
        
        if (empty($itemsToUpdate)) {
            $itemsToUpdate = array(Product);
        }

        foreach ($this->itemsToUpdate as $k => $value) {
            $itemQuery = $value::get();

            foreach ($itemQuery as $key => $item) {
                $newAlsoLike = new AlsoLikeItem();
                $newAlsoLike = $this -> createAlsoLikeItem((string)$item, $item, $newAlsoLike, $item -> Display_Name, $item -> Slogan, $item -> Image(), $item -> Parent_Page() -> RelativeLink());
                $this -> AlsoLikesItem = $newAlsoLike;
                $this -> AlsoLikesItem -> write();
            }
        }
        return true;
    }
    /**
     * Method to create a new AlsoLike Item
     *
     * @param $newAlsoLike DataObject // created by the associated object
     * @param $target // the object that calls the controller
     * @param $title Text //the title of the associated Object
     * @param $description Text
     * @param $image //The image to crop
     * @param $link //The link to the page that will represent the object
     * @param $urlSegment // the last part of the url
     * 
     * @return $newAlsoLike // A also like Object to be added to the original object
     */
    public function createAlsoLikeItem($relatedObjectCategory, &$target, $title, $description, $image, $Link = null) {
        // Process images from the original object
        // crop them to the correct size to fit the also like panel mask
        
        //  first check if there is an image in the object
        if ($image) {
            // crop to size
            $croppedImage = $this->cropAndResizeImage($image,250,250);
            
        }
        
        $existingLikeItem = AlsoLikeItem::get()->filter(array('Title'=>$title))->first();
        
        // If there is none create a new one
        if (empty($existingLikeItem)) {
            // also like new 
            $newAlsoLike = new AlsoLikeItem();
        }
        // Also like item already exists
        else {
            // update it
            $newAlsoLike = $existingLikeItem;
        }
        
       
        // the related Object class 
        $newAlsoLike -> RelatedObjCategory = $relatedObjectCategory;
        // Related Object ID
        $newAlsoLike -> RelatedObjectId = $target -> ID;
        // Related Object Name
        $newAlsoLike -> Name = $target -> Name;
        // Related Object Title
        $newAlsoLike -> Title = $title;
        // Related Object Description
        $newAlsoLike -> Description = $description;
        
        if(!empty($Link)){
            // // Related Object Name
            $newAlsoLike -> LinkUrl = $Link;
        }
        if(!empty($croppedImage)){
            $newAlsoLike -> Product_Image = $croppedImage;
        }
        
        $newAlsoLike -> write();
        
        // Check if there is already a also like for this Object
        $existingLikeItem = $target -> AlsoLikeItem() -> first();
        
        
        if (empty($existingLikeItem)) {
            $target -> AlsoLikeItem() -> add($newAlsoLike);
        }

    }
    /**
     * function cropAndResizeImage
     * to resize then crop an image
     *
     * @param $image Image
     * @param $h the required height
     * @param $w the required width
     * @return the resized-cropped image
     */
    function cropAndResizeImage($image, $w, $h) {

        // get the original image sizes
        $width = $image -> getWidth();
        $height = $image -> getHeight();

        if ($width && $height) {

            // get the portrait vs landscape ratio
            $actualRatio = $height / $width;

            // we have landscape image
            if ($actualRatio >= 1) {

                //get proportional width
                $newWidth = $w * $height / $width;

                // set the height to the required measurements
                $newImage = $image -> setHeight($h);

            }
            else
            // its a portrait image
            {
                // set the height
                $newImage = $image -> setWidth($w);

            }
            if ($newImage -> getHeight() < $h) {
                // if the portrait image is not tall enough for the requirement
                $newImage = $image -> setHeight($h);

            }
            // grab the new proportions
            $newWidth = $newImage -> getWidth();
            $newHeight = $newImage -> getHeight();

            // crop the extraneous width and height
            if ($newWidth > $w) {
                $newImage = $newImage -> croppedImage($w, $newHeight);
            }
            elseif ($newHeight > $h) {
                $newImage = $newImage -> croppedImage($newWidth, $h);
            }

            //$newImage = $newImage->croppedImage($newWidth,$newHeight);
            return $newImage -> getAbsoluteURL();

        }
        else {
            return false;
        }
    }
    public function init() {

        parent::init();

    }

}
?>
