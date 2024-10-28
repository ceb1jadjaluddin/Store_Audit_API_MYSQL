<?php 
	class lstSA_UserAccess {
		private $userid;
		private $userinfo;
		private $userlevel;
		private $userstatus;
		private $userlastlogin;
		private $tableName = '[lstSA_UserAccess]';
		private $dbConn;

		function setUserInfo($userinfo) { $this->userinfo = $userinfo; }
		function getUserInfo() { return $this->userinfo; }
		function setUserLevel($userlevel) { $this->userlevel = $userlevel; }
		function getUserLevel() { return $this->userlevel; }
		function setUserStatus($userstatus) { $this->userstatus = $userstatus; }
		function getUserStatus() { return $this->userstatus; }
        function setUserLastLogin($userlastlogin) { $this->userlastlogin = $userlastlogin; }
		function getUserLastLogin() { return $this->userlastlogin; }

		public function __construct() {
			$db = new DbConnect();
			$this->dbConn = $db->connect();
		}
	}
 ?>