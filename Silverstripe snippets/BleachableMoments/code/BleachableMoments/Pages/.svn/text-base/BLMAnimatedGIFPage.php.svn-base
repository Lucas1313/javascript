<?php
/**
 * Class BLMAnimatedGIFPage
 * Describes the Model for a BLMAnimatedGIFPage
 *
 *  Purpose: Animated GIF Page
 * @author James Billings james.billings -at- clorox.com
 * @version $Id
 */
class BLMAnimatedGIFPage extends BLMMasterPage {
    static $db = array(
        'Description' => 'HtmlText',
        'TwitterCopy'=>'Text',
        'PinterestCopy'=>'Text'
    );
}

class BLMAnimatedGIFPage_Controller extends BLMMasterPage_Controller {

    public function init() {
        //Requirements::javascript("js/pages/blm-animatedgif-page.js");
        parent::init();
    }
	public static $allowed_actions = array('id', 'index');
	
	public function bodyId() {
		return 'animated-gif';
	}
	public function index(){
		$selectedGIF = BLMCleaningHowToPage::get()->first()->BLMGifPromos()->first();
		//TODO: make dynamic next / previous
		$nextGif = BLMCleaningHowToPage::get()->first()->BLMGifPromos()->last()->ID;
		$this->nextURL = $nextGif;
		$this->previousURL = $nextGif;
		//error_log(print_r($selectedGIF,1));
		// sets the object
        $this->BLMGifPromo = $selectedGIF;
        // renders the page
        return $this;
	}
	public function giflink(){
		$gifLink =  $this->BLMGifPromo->LargeImage()->Link();
		if(!empty($gifLink)){
			return $gifLink;
		}else{
			return '';
		}
	}
	public function BLMGifPromos($id){
		return BLMGifPromo::get()->filter('ID',$id)->first();
	}
	public function id(SS_HTTPRequest $request){
		//requests all the params
		$ids = $request->allParams();
		// extracts the first one
		$id = $ids['ID'];
		// get the gif by ID
		$selectedGIF = $this->BLMGifPromos($id);
		// sets the object
		$this->BLMGifPromo = $selectedGIF;
		// renders the page
         
		//TODO: make dynamic next / previous links
		$nextGif = BLMCleaningHowToPage::get()->last()->BLMGifPromos()->first()->ID;
		if($id == $nextGif){
			$nextGif = BLMCleaningHowToPage::get()->first()->BLMGifPromos()->first()->ID;
		}
		$this->nextURL = $nextGif;
		$this->previousURL = $nextGif;
		return $this;
	 }
}