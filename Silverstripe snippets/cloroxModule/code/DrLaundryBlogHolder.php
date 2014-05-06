<?php
/*
 * Class DrLaundryBlogHolder 
 *
 * Describes the Model for the Dr Laundry Blog Holder
 *
 *
 */
class DrLaundryBlogHolder extends BlogHolder {

	static $singular_name = "Dr Laundry Blog Holder";
	public function checkFilterz(){
			switch ($_GET('blogFilter')){
				case 'LatestPosts':
					LatestPosts();
				break;
				case 'Categories':
					return "filtered for categories";
				break;
				case 'Most Popular':
					return "filtered for Popular";
				break;
				case 'Watch Videos':
					return "filtered for Videos";
				break;
				
				default;
					return LatestPosts();
			}
	}
	
	public function LatestPosts(){
		return DrLaundryBlogEntry::get()->sort('Date', 'DESC'); // ASC or DESC
		 
	}
}

class DrLaundryBlogHolder_Controller extends BlogHolder_Controller {
	

	function init() {
		Requirements::javascript("/js/plugins/jquery.filteredPages.js");
		Requirements::javascript("js/pages/DrLaundryBlogHolderPage.js");
		
		Requirements::javascript("js/pages/CLTPageNavigation.js");
        parent::init();
		
	}
}
?>
