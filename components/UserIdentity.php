<?php

/**
 * UserIdentity represents the data needed to identity a user.
 * It contains the authentication method that checks if the provided
 * data can identity the user.
 */
class UserIdentity extends CUserIdentity
{
	private $_id;

	const ERROR_EMAIL_INVALID=3;
	const ERROR_STATUS_NOTACTIV=4;
	const ERROR_STATUS_BAN=5;

	public function __construct($userInfo=array()){
		if ($userInfo==null) {
			Yii::app()->redirect(Yii::app()->getModule('user')->loginUrl);
		} else {
			$this->username = $userInfo['auth']['info']['email'];
			$this->password = null; //not used
		}
	}

	public function authenticate()
	{
		$record=User::model()->findByAttributes(array('email'=>$this->username));  // here I use Email as user name which comes from database
		if($record===null)
		{
			$this->_id='user Null';
			$this->errorCode=self::ERROR_USERNAME_INVALID;
		}
		/*else if($record->E_PASSWORD!==$this->password)            // here I compare db password with passwod field
		 {        $this->_id=$this->username;
		$this->errorCode=self::ERROR_PASSWORD_INVALID;
		}*/
		else if($record['status']!==0)                //  here I check status as Active in db
		{
			$err = "You have been Inactive by Admin.";
			$this->errorCode = $err;
		}

		else
		{
			$this->_id=$record->id;
			$this->setState('title', $record['username']);
			$this->errorCode=self::ERROR_NONE;

		}
		return !$this->errorCode;
	}

	public function getId()       //  override Id
	{
		return $this->_id;
	}
}