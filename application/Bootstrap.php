<?php

class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{
	protected function _initDatabase(){
		$db_config =array (
        'adapter' => 'Pdo_Mysql',
        'params' => array (
            'host' => 'localhost',
            'dbname' => 'gps_database',
            'username'=>'gps.21com',
            'password'=>'n8y4PGLP5DqWu9Er',
            'charset' => 'utf8'
        ));
		try {
			$db = Zend_Db::Factory($db_config['adapter'], $db_config['params']);  
		}
		catch (Zend_Db_Exception $e) {
			exit($e->getMessage());
		}
		try {
			$db->query("SET NAMES '" . $db_config['params']['charset'] . "'");
		}
		catch (Zend_Db_Exception $e) {
			exit('Database Connect Fail!');
			//exit($e->getMessage());
		}
		Zend_Db_Table::setDefaultAdapter($db);
		Zend_Registry::set('db', $db);
	}
	
	protected function _initUser(){
		$session = Zend_Session::getId();
		$user = new users();
		$isLogin=$user->checkLoginStatus(strtoupper($session),$user_id);
		if ($isLogin){
			$userinfo = $user->getInfo($user_id);
			$user->updateSessionTime($user_id);
			Zend_Registry::set('userinfo',$userinfo);
			$smarty=Zend_Registry::get('smarty');
			$smarty->assign("username",$userinfo['username']);
		}else{
			Zend_Registry::set('userinfo',false);
		}
				
	}
	
	protected function _initAcl(){
		if (Zend_Registry::get('userinfo')){
			$this->acl = new acl();
		}
	}
	
}

