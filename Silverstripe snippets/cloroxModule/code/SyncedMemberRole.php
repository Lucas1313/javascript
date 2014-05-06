<?php
/*
 * SyncedMemberRole
 *
 * Ensures that user's pc_consumer entry is credential-synchronized to Member
 * when Member is updated.
 *
 * @author Matt Ernst matt.ernst -at- clorox.com
 * @version $Id: SyncedMemberRole.php 30123 2014-03-28 21:12:13Z ksmith $
 *
 */

class SyncedMemberRole extends DataExtension {

	/* pc consumer ids are properly bigint. SS doesn't have support for bigint
     * so store as varchar instead.
     */

    static $db = array(
       'pc_consumer_id' => 'Varchar(20)',
    );


	/*
	 * After the Member record is written, make sure Clorox users have their
	 * passwords synchronized with the Member record. Otherwise passwords
	 * could fall out of sync when a user activates the password reset process.
	 * Only Clorox users are affected (identified by 'ccl' PasswordEncryption).
	 *
	 */
	public function onAfterWrite() {
		$data = $this->owner->toMap();

		if ('ccl' == $data['PasswordEncryption']) {
			$email = $data['Email'];
			$password = $data['Password'];
			$consumer_id = $data['pc_consumer_id'];
            $consumer = CCL_PC_Model_Consumer::findById($consumer_id);
			$consumer->setPassword($password);
			$consumer->save();

			// use temporary cleartext_pw field set by Clorox encryptor to
			// log in if SS Member is logged in
			if (array_key_exists('cleartext_pw', $data) && Member::currentUserId()) {
				//CWF_Auth::processLoginCore($data['Email'], 
				//						   $data['cleartext_pw']);
			}
		}

		parent::onAfterWrite();
	}

}