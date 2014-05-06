<?php
/*
 * CLTPanel
 *
 * Describes the Model for a CLTPanel
 *
 * @author Luc Martin lmartin -at- clorox.com
 * @version $Id: FaqCategoy.php 21419 2013-04-12 23:01:24Z lmartin $
 *
 * Relationships:
 *
 * hasOne =CTLLandongPage
 * many-many =
 * belong-many-many = Products
 */
class ClassroomsTipPanel extends CLTPanel {

    static $db = array(

    );



    public function getCMSFields() {

        return $fields;
    }

    /**
     * function GeneratePage
     * Method that generates a Single Item page if it doesn't exists.
     */
    public  function generatePage() {

        $alreadyExistingPage = ClassroomsArticlePage::get() -> filter(array('Title' => $this -> Name))->first();
        if($this->ClassroomsArticlePageID == $alreadyExistingPage->ID){

            return $this -> ClassroomsArticlePageID;

        }elseif(!empty($alreadyExistingPage->ID)){
            //error_log('There is a page but it was not associated to the Panel '.$alreadyExistingPage->ID);
            $this->ClassroomsArticlePageID = $alreadyExistingPage->ID;
            $this -> write();
            return $this -> ClassroomsArticlePageID;

        }

        //error_log('There is NO page but we are fixing this!');
        $this -> createNewPage();
    }
    /**
     * function createNewPage
     * Definition: Generates a Page for any single panel
     */
    public  function createNewPage() {

        // Single Item page
        $page = new ClassroomsArticlePage();

        // Page title
        $page -> Title = $this -> Name;

        // All parent pages
        $allParentPages = CLTLocationPage::get();
		
		// Landing page to assign articles to
		$landingPage = CLTLandingPage::get();

		if($landingPage){
			 foreach ($allParentPages as $key => $landingPage) {

            $allTipsPanels = $landingPage -> Tips();

            foreach ($allTipsPanels as $key => $Tip) {

                if ($Tip -> Name == $this -> Name) {

                    $parentpageId = $landingPage -> ID;

                }
            }
		
		// Create article pages on landing page
		// Iterate Through all Articles
            
            	$allArticlesPanels = $landingPage -> Articles();

            foreach ($allArticlesPanels as $key => $Article) {

                if ($Article -> Name == $this -> Name) {

                    $parentpageId = $parentPage -> ID;

                }
            }
		 }
		$page -> setParent($parentpageId);

        $page -> write();

        $page -> MainContentPanels() -> add($this);

        $page -> doPublish();

        $this -> ClassroomsArticlePageID = $page -> ID;

        $this -> write();
        return $this -> ClassroomsArticlePageID;
		
	 }
        // Make sure that all panels we are displaying have a parent page

        // Iterate thought all Parent pages
        foreach ($allParentPages as $key => $parentPage) {

            $allTipsPanels = $parentPage -> Tips();

            foreach ($allTipsPanels as $key => $Tip) {

                if ($Tip -> Name == $this -> Name) {

                    $parentpageId = $parentPage -> ID;

                }
            }

            // Iterate Through all Articles
            $allArticlesPanels = $parentPage -> Articles();

            foreach ($allArticlesPanels as $key => $Article) {

	                if ($Article -> Name == $this -> Name) {
	
	                    $parentpageId = $parentPage -> ID;
	
	                }
	            }
        }
        if (!isset($parentpageId)) {
            return;
        }
	
        $page -> setParent($parentpageId);

        $page -> write();

        $page -> MainContentPanels() -> add($this);

        $page -> doPublish();

        $this -> ClassroomsArticlePageID = $page -> ID;

        $this -> write();
        return $this -> ClassroomsArticlePageID;
	    
    }
    /**
     * function GetSingleItemPageUrl
     *
     * Description Get or Defines the url of the single page for this CLTPanel
     * If the CLTPanel already has a url it will return it if not it will search and create one
     * @author Luc Martin at Clorox
     * @version $ID
     */
    public function GetSingleItemPageUrl($section=null) {

        // Test if the page exists in case something happen to the relationship
        if (!isset($this -> ClassroomsArticlePageID) && empty($this -> ClassroomsArticlePageID)) {
            // if the page dowsn't exist generate it
            $singlePageId = $this -> generatePage();

        }
        // return only the last part of the url
        if($section == 'segment'){
            return $this -> ClassroomsArticlePage() -> URLSegment;
        }
        // full url
        $url = $this -> ClassroomsArticlePage() -> Link();
        return $url;
    }



}
