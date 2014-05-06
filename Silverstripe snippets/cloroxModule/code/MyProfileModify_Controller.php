<?php

/**
 * AccountController shows and modifies user profile information.
 *
 * @author Matt Ernst matt.ernst -at- clorox.com
 * @package cloroxModule.code
 * @version $Id: MyProfileModify_Controller.php 30123 2014-03-28 21:12:13Z ksmith $
 */
class MyProfileModify_Controller extends Page_Controller
{
    static $allowed_actions = array(
									'index', 'ModifyForm', 'ModifyAction'
    );

    public function init() {
        parent::init();

        Requirements::customScript('
            jQuery(document).ready(function() {
                jQuery("#Form_UnsubForm").validate({

                    rules: {
                        FirstName: {
                            required: true
                        },
                        Surname: {
                            required: true
                        }
                    },
                    messages: {
                        FirstName: "Please enter your first name",
                        Surname: "Please enter your last name"
                    }
                });
            });
        ');
    }


	/*
	 * Right now index only ever needs to display user modification form,
	 * so no decision logic needed here: always render
	 * MyProfileModify.ss
	 *
	 */
    public function index() {
		return $this->customise(array())->renderWith(array('MyProfileModify', 'Page'));
    }

	/*
	 * ModifyForm function.
	 * Displays user profile information with options to edit.
	 *
	 */

	public function ModifyForm() {
		$member = Member::currentUser();
		$mmap = $member->toMap();
		if (empty($mmap['ID'])) {
			return;
		}

        $consumer = CCL_PC_Model_Consumer::findById($mmap['pc_consumer_id']);
        
        // the fields
		$emailField = EmailField::create("Email", "Email Address");
		$emailField->setValue('lucas1313@gmail.com');
		$emailField = $emailField->performReadonlyTransformation();

		$firstNameField = TextField::create("FirstName", "First Name");
		$firstNameField->setValue($mmap['FirstName']);

		$surnameField = TextField::create("Surname", "Last Name");
		$surnameField->setValue($mmap['Surname']);

		$postalField = TextField::create("Postcode", "ZIP Code");
		$postalField->setValue($consumer->getPostalCode());

		$bdField = DateField::create("Birthday", "Date of Birth");
		$bdField->setConfig('dmyfields', true);
		// can't be any younger than 18
		$minbd = mktime(0,0,0, date("m"), date("d"), date("Y") - 18);
		$bdField->setConfig('max', date("Y-m-d", $minbd));
		$bdField->setValue($consumer->getDob());

		$mForm =  new Form(
            $this, "ModifyForm",

            new FieldList(

                // List your fields here
                $emailField,
				$firstNameField,
				$surnameField,
				$postalField,
				$bdField

            ),
            // one fieldlist holds the call to the form action (method)
            // and the text on the signup button
            new FieldList(

                // List the action buttons here
                new FormAction("ModifyAction", "Save Changes")

            ),
            // List the required fields here:
            new RequiredFields("FirstName", "Surname")

        );


		return $mForm;
	}

	/**
	 * MemberData function
	 * helper method to pre-populate fields
	 * used for editing user profile data
	 *
	 * @return Member object
	 */
	public function MemberData() {
	    $member = Member::currentUser();
		$mmap = $member->toMap();
		$consumer = CCL_PC_Model_Consumer::findById($mmap['pc_consumer_id']);
        if(empty($consumer)){
            return;
        }
		$email = $consumer->getEmailAddress();

	   // get pc subscription data
	   // and map a string onto member for use in template
	    $brandOpt = $consumer->getBrandOptIn();
		$member->Offers =  !empty($brandOpt)?'checked':null;

        return $member;
	}

    /**
     * ModifyAction function.
     * This function is called when the user submits the $ModifyForm.
     * Modify the user profile.
     *
     * @param array $data - the request
     * @param object $form  - the modified user profile form data
     * @return void
     */
    public function ModifyAction($data, $form) {
		$error = false;
        
		$member = Member::currentUser();
		$mmap = $member->toMap();
		$consumer = CCL_PC_Model_Consumer::findById($mmap['pc_consumer_id']);

		$form->saveInto($member);
		// prepare changes for Consumer attributes
		$consumer->setFirstName($data['FirstName']);
		$consumer->setLastName($data['Surname']);
		$consumer->setEmailAddress($data['Email']);
		// optional values
		if (array_key_exists('Postcode', $data)) {
			$consumer->setPostalCode($data['Postcode']);
		}

		if (array_key_exists('Birthday', $data)) {
			$dob = $data['Birthday']['year'] . '-'
			. str_pad($data['Birthday']['month'], 2, '0', STR_PAD_LEFT)
			. '-' . str_pad($data['Birthday']['day'], 2, '0', STR_PAD_LEFT);
			$consumer->setDob($dob);
		}

		// set up optional subscriptions

		if (array_key_exists('Offers', $data)) {
			$consumer->setBrandOptIn($data['Offers']);
		}else{
    		$consumer->setBrandOptIn(false);
		}



		// save changes
		try {
			$member->write();
			$consumer->save();
		}

		// catch everything, don't really want to show detailed errors in UI
		catch (Exception $e) {
			$error = true;
			}
		if ($error) {

			$form->addErrorMessage("Message", "An error occurred. Please try again later.", "bad");
			$this->redirectBack();
		}
		else {

			$form->addErrorMessage("Message", "Your profile has been updated.",
							   "good");
			$this->redirect('/');
		}
	}

	/**
     * HandleSignupFailure function.
     * This function is called when a validation check fails on the back end.
     *
     * @param array $data - the request
     * @param object $form  - the signup form data
	 * @param string $message - the failure message to return in UI
     * @return void
     */
	protected function HandleSignupFailure($data, $form, $message) {
		$form->addErrorMessage("Message", $message, "bad");

		// Load errors into session and post back
		Session::set(
					 "FormInfo.RegistrationForm_RegistrationForm.data",
					 $data
					 );

		// Redirect back to form
		$this->redirectBack();
	}

}
