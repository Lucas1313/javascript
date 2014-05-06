<?php

/**
 * Encryptor to match Clorox passwords
 *
 * @author Matt Ernst matt.ernst -at- clorox.com
 * @version $Id: CloroxEncryptor.php 18879 2013-02-23 10:01:01Z mernst $
 * 
 */
class CloroxEncryptor extends PasswordEncryptor {
	function encrypt($password, $salt = null, $member = null) {

		// Augment member with a copy of the password in cleartext, so we
		// can re-authenticate the user in the Clorox system when they change
		// passwords. This field is not persisted.
		if ($member) {
			$member->setField('cleartext_pw', $password);
		}

		return CCL_PC_Model_Consumer::pwhash($password, $salt);
	}
	
	function salt($password, $member = null) {
		return CCL_PC_Model_Consumer::salt();
	}
}
