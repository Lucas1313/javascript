<?php

/**
 * SubscriptionController renders subscription management (opt-out) form and 
 * processes actions.
 * 
 * @author Matt Ernst matt.ernst -at- clorox.com
 * @package cloroxModule.code
 * @version $Id: SubscriptionController.php 26917 2013-11-05 02:09:30Z ksmith $
 */

class SubscriptionController extends Page_Controller
{
    static $allowed_actions = array(
									'index', 'UnsubForm', 'UnsubAction'
    );

    public function init() {
        parent::init();
    }
    

	/*
	 * Right now index only ever needs to display unsubscription form,
	 * so no decision logic needed here: always render 
	 * SubscriptionController_Unsubscribe.ss
	 *
	 */
    public function index() {
		return $this->customise(array())->renderWith(array('SubscriptionController_Unsubscribe', 'Page')); 
    }

	/*
	 * UnsubForm function.
	 * Sets up unsubscription options for a user identified by email.
	 * Email can be passed in as a query string parameter to pre-populate the
	 * field.
	 *
	 */

	public function UnsubForm() {

		// Auto-fill email field from query string parameter, or if qs param
		// not set and user logged in, fill it from current user identity.
		$email = $this->request->getVar('email');
		if (!$email) {
			$member = Member::currentUser();
			if ($member) {
				$m = $member->toMap();
				$consumer = CCL_PC_Model_Consumer::findById($m['pc_consumer_id']);
				$email = $consumer->getEmailAddress();
			}
		}

        // the fields
		$emailField = EmailField::create("Email", "Email Address");
		$emailField->setValue($email);

		$brandField = CheckboxField::create("BrandSub", "I NO longer want to receive news, special offers and information from Clorox.");

		$unsubForm =  new Form(
            $this, "UnsubForm", 
            // build one FieldList with the initial form fields
            // http://doc.silverstripe.org/framework/en/reference/form-field-types
            new FieldList(
            
                // List your fields here
                $emailField,
				$brandField           
            ), 
            // one fieldlist holds the call to the form action (method)
            // and the text on the signup button
            new FieldList(
            
                // List the action buttons here
                new FormAction("UnsubAction", "Submit")

            ), 
            // List the required fields here: "Email"
            new RequiredFields("Email")
   
        );

        Requirements::customScript('
            jQuery(document).ready(function() {
                jQuery("#Form_UnsubForm").validate({

                    rules: {
                        Email: {
                            required: true,
                            email: true
                        },
                        BrandSub: {
                            required: true
                        }
                    },
                    messages: {
                        Email: "Please enter a valid email address",
                    }
                });
            });
        ');

		return $unsubForm;
	}

    /**
     * UnsubAction function.
     * This function is called when the user submits the $UnsubForm.
     * Use the subscription service to unset every subscription opted out of.
     * 
     * @param array $data - the request
     * @param object $form  - the unsubscribe form data 
     * @return void
     */
    public function UnsubAction($data, $form) {
		$error = false;
		$consumer = CCL_PC_Model_Consumer::findByEmail($data['Email']);

		if (empty($consumer)) {
			$error = true;
		}

		else {
			$cs = new CCL_PC_Service_Subscription();
			$submap = array('BrandSub' => CCL_PC_Model_Subscription::SUBSCRIPTION_BRAND);

			// opt out of each designated subscription
			foreach ($submap as $k => $subscriptionType) {
				$optout = isset($data[$k]) ? $data[$k] : false;
				if ($optout) {

					$cs->setSubscription($consumer, $subscriptionType, false);
					$status = $cs->getResponse()->getStatus();

					if ($status != CCL_PC_Service_Abstract::SUCCESS)
						{
							$error = true;
						}

				}
			}
			
		}

		if ($error) {
			$form->addErrorMessage("Message", "An error occurred. Please try again later.", "bad");
			$this->redirectBack();
			return;
		}

		$form->addErrorMessage("Message", "Thank you. Your subscriptions have been updated.",
							   "good");
		$this->redirectBack();
	}
     
}
