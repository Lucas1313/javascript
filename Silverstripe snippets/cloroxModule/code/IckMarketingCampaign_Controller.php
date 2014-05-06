<?php
/*
 * IckMarketingCampaign_Controller
 *
 * Manages the Ick marketing Campaign
 *
 * @author Luc Martin lmartin -at- clorox.com
 * @version $Id: IckMarketingCampaign_Controller.php 29240 2014-02-18 22:54:47Z lmartin $
 *
 * Relationships:
 * one-many =
 * many-one =
 * many-many =
 *
 */
class IckMarketingCampaign_Controller  extends Extension {
    /**
    public  $now;
    
    function __construct(){
        $this->now = Date('N',strtotime(('Today')));
        error_log('Today is '.$this->now);
        $icks = IcktionaryItem::get();
        foreach ($icks as $key => $ick) {
            error_log('Ick sortOrder '.$ick->now);
        }
        parent::__construct(); 
    }
    **/
}