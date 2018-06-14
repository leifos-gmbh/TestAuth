<?php
/* Copyright (c) 1998-2009 ILIAS open source, Extended GPL, see docs/LICENSE */

include_once './Services/Authentication/classes/class.ilAuthPlugin.php';
include_once './Services/Authentication/interfaces/interface.ilAuthDefinition.php';


/**
 * Base plugin class
 * 
 * @author Stefan Meyer <smeyer.ilias@gmx.de>
 */
class ilTestAuthPlugin extends ilAuthPlugin implements ilAuthDefinition
{
	private static $instance = null;

	const CTYPE = 'Services';
	const CNAME = 'Authentication';
	const SLOT_ID = 'authhk';
	const PNAME = 'TestAuth';
	
	const AUTH_ID = 2300;
	const AUTH_NAME = 'testauth';
	
	
	/**
	 * Get singleton instance
	 * @global ilPluginAdmin $ilPluginAdmin
	 * @return ilTestAuthPlugin
	 */
	public static function getInstance()
	{
		if(self::$instance)
		{
			return self::$instance;
		}

		include_once './Services/Component/classes/class.ilPluginAdmin.php';
		return self::$instance = ilPluginAdmin::getPluginObject(
			self::CTYPE,
			self::CNAME,
			self::SLOT_ID,
			self::PNAME
		);
		
	}


	/**
	 * @inheritdoc
	 */
	public function getPluginName()
	{
		return self::PNAME;
	}

	/**
	 * @inheritdoc
	 */
	protected function slotInit()
	{
		$this->initAutoLoad();
	}

	/**
	 * @inheritdoc
	 */
	public function getProvider($credentials, $a_auth_id)
	{
		return new ilAuthProviderTest(
			$credentials
		);
	}


	/**
	 * @inheritdoc
	 */
	public function getAuthIds()
	{
		return [
			self::AUTH_ID
		];
	}

	/**
	 * @inheritdoc
	 */
	public function getAuthIdByName($a_auth_name)
	{
		return self::AUTH_ID;
	}
	
	/**
	 * @inheritdoc
	 */
	public function getAuthName($a_auth_id)
	{
		return self::AUTH_NAME;
	}

	


	/**
	 * @inheritdoc
	 */
	public function getLocalPasswordValidationType($a_auth_id)
	{
		return ilAuthUtils::LOCAL_PWV_FULL;
	}

	/**
	 * @inheritdoc
	 */
	public function isExternalAccountNameRequired($a_auth_id)
	{
		return true;
	}

	/**
	 * @inheritdoc
	 */
	public function isPasswordModificationAllowed($a_auth_id)
	{
		return false;
	}

	/**
	 * @inheritdoc
	 */
	public function supportsMultiCheck($a_auth_id)
	{
		return true;
	}

	/**
	 * @inheritdoc
	 */
	public function getMultipleAuthModeOptions($a_auth_id)
	{
		return [
			self::AUTH_ID => [
				'txt' => 'Test Authentication U=P'
			]

		];
	}
	
	
	/**
	 * @inheritdoc
	 */
	public function isAuthActive($a_auth_id)
	{
		return true;
	}
	
	/**
	 * Init auto loader
	 * @return void
	 */
	protected function initAutoLoad()
	{
		spl_autoload_register(
			array($this,'autoLoad')
		);
	}

	/**
	 * Auto load implementation
	 *
	 * @param string class name
	 */
	private final function autoLoad($a_classname)
	{
		$class_file = $this->getClassesDirectory().'/class.'.$a_classname.'.php';
		@include_once($class_file);
	}
}
?>