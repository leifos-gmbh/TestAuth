<?php

/* Copyright (c) 1998-2009 ILIAS open source, Extended GPL, see docs/LICENSE */

class ilAuthProviderTest extends ilAuthProvider implements ilAuthProviderInterface
{
	/**
	 * Constructor
	 */
	public function __construct(\ilAuthCredentials $credentials)
	{
		parent::__construct($credentials);
	}

	/**
	 * Do authentication 
	 * @param \ilAuthStatus $status
	 * @return bool
	 */
	public function doAuthentication(\ilAuthStatus $status)
	{
		$login_name = $this->getCredentials()->getUsername();
		$password = $this->getCredentials()->getPassword();
		
		$this->getLogger()->debug('Trying to login as: '. $login_name );
		
		$user = ilObjectFactory::getInstanceByObjId(ilObjUser::_loginExists($this->getCredentials()->getUsername()),false);

		if($user instanceof ilObjUser)
		{
			// Authentication is successful, if username equals password.
			if(strcmp($login_name, $password) === 0)
			{
				$this->getLogger()->debug('Successfully authenticated user: ' . $this->getCredentials()->getUsername());
				$status->setStatus(ilAuthStatus::STATUS_AUTHENTICATED);
				$status->setAuthenticatedUserId($user->getId());
				return true;
			}
		}

		$status->setStatus(ilAuthStatus::STATUS_AUTHENTICATION_FAILED);
		$status->setReason('err_wrong_login');
		return false;
	}
}