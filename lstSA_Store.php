<?php 
	class lstSA_Store {
		private $storeid;
		private $storecode;
		private $storename;
		private $storeaddress;
		private $storegeoloc;
		private $storeareamanager;
		private $storedissup;
		private $storeic;
        private $storebu;
		private $tableName = '[lstSA_Store]';
		private $dbConn;

		function setStoreID($storeid) { $this->storeid = $storeid; }
		function getStoreID() { return $this->storeid; }
		function setStoreCode($storecode) { $this->storecode = $storecode; }
		function getStoreCode() { return $this->storecode; }
		function setStoreName($storename) { $this->storename = $storename; }
		function getStoreName() { return $this->storename; }
        function setStoreAddress($storeaddress) { $this->storeaddress = $storeaddress; }
		function getStoreAddress() { return $this->storeaddress; }
		function setStoreGeoLoc($storegeoloc) { $this->storegeoloc = $storegeoloc; }
		function getStoreGeoLoc() { return $this->storegeoloc; }
		function setStoreAreaManager($storeareamanager) { $this->storeareamanager = $storeareamanager; }
		function getStoreAreaManager() { return $this->storeareamanager; }
		function setStoreDisSup($storedissup) { $this->storedissup = $storedissup; }
		function getStoreDisSup() { return $this->storedissup; }
		function setStoreIC($storeic) { $this->storeic = $storeic; }
		function getStoreIC() { return $this->storeic; }
		function setStoreBU($storebu) { $this->storebu = $storebu; }
		function getStoreBU() { return $this->storebu; }

		public function __construct() {
			$db = new DbConnect();
			$this->dbConn = $db->connect();
		}

		public function getCountOfAllItems(){
			$totalItemsQuery = "SELECT COUNT(*) AS totalItems FROM $this->tableName";
			$result = $this->dbConn->query($totalItemsQuery);
			return $totalItems = $result->fetchColumn();
		}

		public function getAllStore($page,$limit) {
			
			$offset = ($page - 1) * $limit;
				// SQL query to select all records from the customers table
			$sql = "SELECT [Store_ID]
							,[Store_Code]
							,[Store_Name]
							,[Store_Address]
							,[Store_GeoLoc]
							,[Store_AreaManager]
							,[Store_DisSup]
							,[Store_IC]
							,[Store_BU]
							,[STORE_ID_SSD] FROM " . $this->tableName. "
							ORDER BY Store_ID
							OFFSET $offset ROWS FETCH NEXT $limit ROWS ONLY";
		
			// Prepare the SQL statement
			$stmt = $this->dbConn->prepare($sql);
			
			// Execute the SQL statement
			$stmt->execute();
			
			// Fetch all records as an associative array
			$responseStore = $stmt->fetchAll();
			
			// Return the array of customers
			return $responseStore;
		}
	}
 ?>