<?php

class RegistrationForm_ControllerTest extends SapphireTest {
	public function setUp() {
		parent::setUp();
		// set up Basic Members group, otherwise member signup fails
		$g = array('code' => 'basic-members', 'Title' => 'Basic Members');
		$factory = new FixtureFactory();
		$factory->createObject('Group', 'basic', $g);
	}

	// produce basic form data for a user with a random email address
	public function getUserData() {
		$email = md5(mt_rand()) . '@rndunittest.com';
		$birthday = array('year' => '1959', 'month' => '3', 'day' => '16');
		$data = array('FirstName' => 'Testy', 'Surname' => 'Testerson',
					  'Password' => 'Password123', 'Email' => $email,
					  'TermsOfUse' => 1, 'Birthday' => $birthday,
					  'Offers' => 0, 'Postcode' => '02142');	
		return $data;
	}

	// validate that signup creates a SS Member and matching PC Consumer
	public function testSignupActionBasic() {
		$rfc = new RegistrationForm_Controller();
		$form = $rfc->SignupForm();

		$data = $this->getUserData();
		$form->loadDataFrom($data);
		$rfc->SignupAction($data, $form);

		$member = Member::get()->filter('FirstName', 'Testy')->First();
		$this->assertEquals($data['Email'], $member->getField('Email'));

		$consumer = CCL_PC_Model_Consumer::findByEmail($data['Email']);
		$this->assertEquals($data['Email'], $consumer->getEmailAddress());

		// both users are created with matching password hashes
		$this->assertEquals($consumer->getPassword(),
							$member->getField('Password'));
	}

	// can't create Member with duplicate email address
	public function testSignupActionSSDupe() {
		$rfc = new RegistrationForm_Controller();
		$rfc2 = new RegistrationForm_Controller();
		$form = $rfc->SignupForm();
		
		$data = $this->getUserData();
		$data2 = $this->getUserData();

		// set up two users with same email, different first name
		$data2['Email'] = $data['Email'];
		$data2['FirstName'] = 'Dupey';

		$form->loadDataFrom($data);
		$rfc->SignupAction($data, $form);

		$form2 = $rfc->SignupForm();
		$form2->loadDataFrom($data2);
		$rfc2->SignupAction($data2, $form2);

		$member = Member::get()->filter('FirstName', 'Dupey')->First();
		$this->assertEquals(null, $member);
	   
	}

	// can't create Member when same email address exists for PC Consumer
	public function testSignupActionPCDupe() {
		$rfc = new RegistrationForm_Controller();
		$form = $rfc->SignupForm();
		$consumer = new CCL_PC_Model_Consumer();
		$cService = new CCL_PC_Service_Consumer();

		$data = $this->getUserData();
		$data['FirstName'] = 'Testie';

		$consumer->setFirstName($data['FirstName']);
		$consumer->setLastName($data['Surname']);
		$consumer->setRegPassword($data['Password']);
		$consumer->setEmailAddress($data['Email']);
		$consumer->setTermsOfUse($data['TermsOfUse']);
		$cService->consumerCreate($consumer);

		$form->loadDataFrom($data);
		$rfc->SignupAction($data, $form);

		$member = Member::get()->filter('FirstName', 'Testie')->First();
		$this->assertEquals(null, $member);
	   
	}
}
?>
