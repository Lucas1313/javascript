<?php
/*
 * IcktionaryItem
 *
 * Describes the Model for a IcktionaryItem
 *
 * @author Luc Martin lmartin -at- clorox.com
 * @version $Id: IcktionaryItem.php 29240 2014-02-18 22:54:47Z lmartin $
 *
 * Relationships:
 *
 * hasOne = SingleIckPage, Image, AlsoLikesItem
 * many-one = ProductSubCategories
 * many-many = TagGeneral
 * Belong Many Many = Welcome
 */
class IcktionaryItem extends DataObject {

    static $db = array(
        'Title'=>'HtmlText',
        'Dark_Background'=>'Boolean',
        'Name' => 'Text', // The name of the ick
        'Display_Name' => 'Text', // the display name of the ick
        'Slogan' => 'Text', // the spelling
        'Definition' => 'Text', // the definition of the ick
        'Bubble_1_Header_Copy' => 'Text',  // first bubble title
        'Bubble_1_Body_Copy' => 'Text', // the text of the bubble 1
        'Bubble_2_Header_Copy' => 'Text', // second bubble title
        'Bubble_2_Body_Copy' => 'Text', // second bubble text
        'Feedback_Title_1' => 'Text', // customer feedback title firrst part
        'Feedback_Title_2' => 'Text', // customer feedback title second part
        'Feedback_View_Link' => 'Text', // VIEW all customer feedback -- the link
        'Footer_Date' => 'Date', // the date at the footer
        'See_Artist_Link' => 'Text', // the text for the artist link
        'Thumb_Up_Count' => 'Varchar', // The count of thumbs ups
        'Selected_Tags' => 'Text', // tagging
        'TagGenerals' => 'Text', // tagging
        'IckAuthors'=>'Text',
        'IckIllustrators' => 'Text',
        'Selected_Tags_IckSubstance_Cb'=>'Text',
        'Selected_Tags_IckArea_Cb'=>'Text',
        'Selected_Tags_General_Cb'=>'Text',
        'Use_Promo'=>'varchar', // use the promo item
        'SortOrder'=>'Int',
        'sortedDate' => 'Date',
        'User_Click_Counter'=>'Int',
        'Exclude_From_Menu'=>'Boolean',
        'Twitter_Short' => 'Text',
        'Facebook_Short' => 'Text',
        'Add_Link_To_Video_Page' => 'Boolean',
        'Youtube_Url' => 'HTMLText'
    );

    public  $alreadyWritten = false;

    //The class of the page which will list this DataObject

    static $has_one = array(
        'Image' => 'Image',
        'IcktionaryItemPage' => 'IcktionaryItemPage',
        'IckSinglePage' => 'IckSinglePage',
        'PinterestImage'=>'Image'
    );

    static $has_many = array('AlsoLikeItem' => 'AlsoLikeItem',
                                'Welcome' => 'Welcome');

    static $many_many = array('TagGeneral' => 'TagGeneral',
                                'IckAuthor'=>'IckAuthor',
                                'ProductPromoItem' => 'ProductPromoItem',
                                'IckIllustrator' => 'IckIllustrator');

    public static $summary_fields = array(
        'ID'=>'ID',
        'SortOrder'=>'SortOrder',
        'Title'=>'Title',
        'Name' => 'Name',
        'Display_Name' => 'Display_Name',
        'Slogan' => 'Slogan',
        'Definition' => 'Slogan',
        'Bubble_1_Header_Copy' => 'Bubble_1_Header_Copy',
        'Bubble_1_Body_Copy' => 'Bubble_1_Body_Copy',
        'Bubble_2_Header_Copy' => 'Bubble_2_Header_Copy',
        'Bubble_2_Body_Copy' => 'Bubble_2_Body_Copy',
        'Feedback_Title_1' => 'Feedback_Title_1',
        'Feedback_Title_2' => 'Feedback_Title_2',
        'Feedback_View_Link' => 'Feedback_View_Link',
        'Footer_Date' => 'Footer_Date',
        'See_Artist_Link' => 'See_Artist_Link',
        'Thumb_Up_Count' => 'Thumb_Up_Count'
    );

    // Drag and Drop ordering
    public static $default_sort = 'Display_Name';

    public function getCMSFields() {

        if(!empty($this->Title)){
            $this->generatePage();
        }

        // Do we need to add a also like?
        $alsoLikeItem = $this->AlsoLikeItem()->first();

        // If yes add a alsoLike
        if(!$alsoLikeItem){
             // generate the also like item
            $this->generateAlsoLike();
        }


        if (empty($this -> Title) || $this -> Title == 'New Item') {
            $this -> Title = $this -> Name;
            $this->write();
        }

        $this -> Name = html_entity_decode($this -> Name);
        $this -> Display_Name = html_entity_decode($this -> Display_Name);
        $this -> Slogan = html_entity_decode($this -> Slogan);
        $this -> Definition = html_entity_decode($this -> Definition);
        $this -> Bubble_1_Header_Copy = html_entity_decode($this -> Bubble_1_Header_Copy);
        $this -> Bubble_1_Body_Copy = html_entity_decode($this -> Bubble_1_Body_Copy);
        $this -> Bubble_2_Header_Copy = html_entity_decode($this -> Bubble_2_Header_Copy);
        $this -> Bubble_2_Body_Copy = html_entity_decode($this -> Bubble_2_Body_Copy);
        $this -> Feedback_Title_1 = html_entity_decode($this -> Feedback_Title_1);
        $this -> Feedback_View_Link = html_entity_decode($this -> Feedback_View_Link);
        $this -> Footer_Date = html_entity_decode($this -> Footer_Date);
        $this -> See_Artist_Link = html_entity_decode($this -> See_Artist_Link);
        $this -> Thumb_Up_Count = html_entity_decode($this -> Thumb_Up_Count);

        $fields = parent::getCMSFields();

        $fields -> removeFieldsFromTab('Root', array('TagGeneral','Welcome','ProductPromoItem'));
        $fields -> removeFieldFromTab('Root.Main', 'IcktionaryItemPageID');
        $fields -> removeFieldFromTab('Root.Main', 'TagGenerals');
        $fields -> removeFieldFromTab('Root.Main', 'IckIllustrators');
        $fields -> removeFieldFromTab('Root.Main', 'IckAuthors');
        $fields -> removeFieldFromTab('Root.Main', 'IckSinglePageID');
        $fields -> removeFieldFromTab('Root.Main', 'Content');
      //  $fields -> removeFieldFromTab('Root.Main', 'Name');
        $fields -> removeFieldFromTab('Root.Main', 'Selected_Tags');
        // remove the content field

        /** General info **/
        // the name of the Ick (Title)
        $fields -> addFieldToTab('Root.Main', new TextField('Title'));

        // the name of the Ick (Title)
        $fields -> addFieldToTab('Root.Main', new TextField('Display_Name'));

        // The name to display on the page
        $fields -> addFieldToTab('Root.Main', new TextField('Slogan'));

        // the spelling
        $fields -> addFieldToTab('Root.Main', new TextareaField('Definition'));
        // the definition of the ick
        $fields -> addFieldToTab('Root.Main', new CheckboxField('Dark_Background'));
        /** The bubbles **/

        // Bubble 1 header
        $fields -> addFieldToTab('Root.Main', new TextField('Bubble_1_Header_Copy'));

        // Bubble 1 text
        $fields -> addFieldToTab('Root.Main', new TextareaField('Bubble_1_Body_Copy'));

        //Bubble 2 header
        $fields -> addFieldToTab('Root.Main', new TextField('Bubble_2_Header_Copy'));

        // bubble 2 text
        $fields -> addFieldToTab('Root.Main', new TextareaField('Bubble_2_Body_Copy'));

        //SHORT URLS
        $fields -> addFieldToTab('Root.Main', new TextField('Twitter_Short'));
        $fields -> addFieldToTab('Root.Main', new TextField('Facebook_Short'));

        /** Customer feedback form **/

        // Feedback CTA title 1
        $fields -> addFieldToTab('Root.Main', new TextField('Feedback_Title_1'));
        // Feedback CTA title 1
        $fields -> addFieldToTab('Root.Main', new TextField('Feedback_Title_2'));
        // Link to feedback page
        $fields -> addFieldToTab('Root.Main', new TextField('Feedback_View_Link'));



        //** Footer DATE **/
        $dateField = new DateField('Footer_Date');
        $dateField -> setConfig('showcalendar', true);
        $fields -> addFieldToTab('Root.Main', $dateField);

        /** Artist Credentials **/

         // Link to artist page
        $fields -> addFieldToTab('Root.Main', new TextField('See_Artist_Link'));

        // Link Eventual youtube url
        $fields -> addFieldToTab('Root.Main', new HeaderField('Youtube_Url','Youtube and Videos'));
        $fields -> addFieldToTab('Root.Main', new  CheckboxField('Add_Link_To_Video_Page','Select this box to Add a Link To a Video Page'));
        $fields -> addFieldToTab('Root.Main', new TextField('Youtube_Url', 'Alternate link for the Video content'));

        /** PROMO **/


        /** Thumb up **/
        $fields -> addFieldToTab('Root.Main', new CheckboxField('Use_Promo', 'Use Promo'));

         //************** Promo



         //************************* Promo items
        $conf = GridFieldConfig_RelationEditor::create(30);

        // unlink button
        $conf -> addComponents(new GridFieldDeleteAction('unlinkrelation'));

        // the grid
        $ProductPromoItemField = new GridField('ProductPromoItem', 'PromoItem', $this -> ProductPromoItem(), $conf);
        $fields -> addFieldToTab('Root.Main', $ProductPromoItemField);

         //************************* Authors
        $conf = GridFieldConfig_RelationEditor::create(30);

        // unlink button
        $conf -> addComponents(new GridFieldDeleteAction('unlinkrelation'));

        // the grid
        $ItemField = new GridField('IckAuthor', 'IckAuthor', $this -> IckAuthor(), $conf);
        $fields -> addFieldToTab('Root.IckAuthor', $ItemField);

        //************************* IckIllustrator
        $conf = GridFieldConfig_RelationEditor::create(30);

        // unlink button
        $conf -> addComponents(new GridFieldDeleteAction('unlinkrelation'));

        // the grid
        $ItemField = new GridField('IckIllustrator', 'IckIllustrator', $this -> IckIllustrator(), $conf);
        $fields -> addFieldToTab('Root.IckIllustrator', $ItemField);


        // Thumb Count
        $fields -> addFieldToTab('Root.Main', new TextField('Thumb_Up_Count'));


        /** The image for the Ick **/
        $fields -> addFieldToTab('Root.Main', new HeaderField('ImageHeader', 'Images </h2><h4>Upload the Ick-Image and the Custom Pinterest Image</h4><p>This custom image if for the Pinterest</p><h4>'));
        $fields -> addFieldToTab('Root.Main', new UploadField($name = 'Image', $title = 'Upload the Ick-Image'));
        /** The image for the Ick **/
        $fields -> addFieldToTab('Root.Main', new UploadField($name = 'PinterestImage', $title = 'Upload the Pinterest Image'));

        //************************* Tag

        $allTagGeneralIckSubstance = TagGeneral::get()->filter(array('Tag_Type'=>'IckSubstance'));
        $allTagGeneralIckArea = TagGeneral::get()->filter(array('Tag_Type'=>'IckArea'));
        $allTagGeneralIckGeneral = TagGeneral::get()->filter(array('Tag_Type'=>'General'));

        $allTagGeneralIckSubstanceAr = array();
        foreach ($allTagGeneralIckSubstance as $key => $tag) {
            $allTagGeneralIckSubstanceAr[$tag->ID] = $tag->Title;
        }
        $allTagGeneralIckSubstance = $allTagGeneralIckSubstanceAr;

        $selectedTags = array();

        foreach ($this->TagGeneral() as $key => $value) {

            $selectedTags[] = $value->ID;

        }

        $fields -> addFieldToTab('Root.Tags', new LiteralField('Tag Needs', '<div style="font-weight:bold; padding:10px;">Tag Needs: Task (deodorize / remove stains / whiten / etc.)</div>'));

        $gridFieldConfig = GridFieldConfig::create() -> addComponents(new GridFieldToolbarHeader(), new GridFieldSortableHeader(), new GridFieldDataColumns(), new GridFieldEditButton(), new GridFieldDetailForm());
        $TagGeneralField = new GridField('IckTags', 'IckTags', $this -> TagGeneral(), $gridFieldConfig);
        $fields -> addFieldToTab('Root.Tags', $TagGeneralField);

        $tagsCheckBoxField = new CheckboxSetField($name = 'Selected_Tags_IckSubstance_Cb', $title = 'Available tags in IckSubstance', $source = $allTagGeneralIckSubstance);
        $tagsCheckBoxField->setDefaultItems( $selectedTags );
        $fields -> addFieldToTab('Root.Tags',$tagsCheckBoxField);

        $tagsCheckBoxField = new CheckboxSetField($name = 'Selected_Tags_IckArea_Cb', $title = 'Available tags in IckArea', $source = $allTagGeneralIckArea);
        $tagsCheckBoxField->setDefaultItems( $selectedTags );
        $fields -> addFieldToTab('Root.Tags',$tagsCheckBoxField);

        $tagsCheckBoxField = new CheckboxSetField($name = 'Selected_Tags_General_Cb', $title = 'Available tags in General', $source = $allTagGeneralIckGeneral);
        $tagsCheckBoxField->setDefaultItems( $selectedTags );
        $fields -> addFieldToTab('Root.Tags',$tagsCheckBoxField);


        return $fields;
    }

    /**
     * function manageTags
     * @author Luc Martin
     *
     * @param $localTags // the list of tags already in the object as relational objects
     * @param $selectedTags // the result of the checkbox selection
     * @param $tagType // the typeo of tag
     */
    public  function manageTags($localTags, $selectedTags, $tagType){

       $tg = $localTags->filter(array('Tag_Type'=>$tagType));

       $selectedCb = explode(',', $selectedTags);

       if(!empty($selectedCb)){

           foreach ($selectedCb as $k => $v) {

               $newTags = TagGeneral::get()->filter(array('ID'=>$v));

                    foreach($newTags as $k=>$newTag){
                        $localTags->add($newTag);
                    }
           }
       }

       foreach ($tg as $key => $localGeneralTag) {

            $selected = false;

            foreach ($selectedCb as $k => $v) {

                if($localGeneralTag->ID == $v){
                    $selected = true;
                }
            }
            if($selected == false){
                $localTags->remove($localGeneralTag);
            }

        }


        $relationshipImportController = new Relationship_Controller();

        // Update the relationship field at save time necessary for the CSV import
        $this -> Selected_Tags = $relationshipImportController -> updateRelationshipField($this, 'Selected_Tags', $localTags, 'ID');

    }
    /**
     * function GeneratePage
     * Method that generates a Ick page if it doesn't exists.
     */
    public  function generatePage(){

        $alreadyExistingPage = IckSinglePage::get()->filter('Title',$this->Title);
        foreach ($alreadyExistingPage as $key => $value) {
            if($value->Title == $this->Title){
                return;
            }
        }

        $this->createNewPage();
    }

    public  function createNewPage(){

        $page = new IckSinglePage();
        $page->Title = $this->Title;
        $ictionnaryItemPage = IcktionaryItemPage::get()->first();
        $parentId = $ictionnaryItemPage->ID;
        $page->setParent( $parentId);

        $page->write();
        $page->doPublish( );
        $page->IcktionaryItem()->add($this);
    }

    public  function generateAlsoLike(){

         $page = $this->IckSinglePage();
         $alsoLikeItem_Controller = new AlsoLikeItem_Controller();
            if(!empty($page->Title)){

               $alsoLikeItem_Controller->createAlsoLikeItem('Ick', $this, $this->Title, $this->Definition, $this->Image(), $page->Link());

            }

    }
    /**
     * function onBeforeWrite
     * Method called before the object is written to the DB
     *
     * Will generate a also like item for that object
     *
     * @param void
     * @return void
     */
    public function onBeforeWrite() {


        $this->manageTags( $this->TagGeneral(), $this->Selected_Tags_IckSubstance_Cb, 'IckSubstance');
        $this->manageTags( $this->TagGeneral(), $this->Selected_Tags_IckArea_Cb, 'IckArea');
        $this->manageTags( $this->TagGeneral(), $this->Selected_Tags_General_Cb, 'General');

        if (empty($this -> Title) || $this -> Title == 'New Item') {
            $this -> Title = $this -> Name;
        }

        // Fill automatically missing fields
        if (empty($this -> Name)) {
            $this -> Name = $this -> Title;
        }

        if (empty($this -> Display_Name)) {
            $this -> Name = $this -> Title;
        }


        parent::onBeforeWrite();
    }

    /**
     * function itemPageUrl
     * Lookup for the associated page URL
     * if there is none add it using Title
     */
    public function itemUrlSegment(){

            return $this->IckSinglePage()->URLSegment;

    }

    /**
     * Provides the SS template with the related page URL
     */
    public function itemPageUrl($title = null){

        if(empty($title)){
	        $title = $this->Title;
        }
        if($this->IckSinglePage()->Link()){
            return $this->IckSinglePage()->Link();
        }else{
            $alreadyExistingPage = IckSinglePage::get()->filter('Title',$title);
            foreach ($alreadyExistingPage as $key => $page) {
                if($page->Title == $this->Title){
                    $this->IckSinglePage()->add($page);
                    $this->write();
                    return $page->Link();
                }
            }
        }

    }
    public function webroot(){
        return $_SERVER['SERVER_NAME'];
    }

}
