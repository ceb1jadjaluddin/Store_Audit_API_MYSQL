<?php 
	class lstSA_Category {
        private $catcode;
		private $catname;
		private $israting;
		private $bu;
		private $rating;
		private $tableName = '[lstSA_Category]';
		private $dbConn;

		function setCatCode($catcode) { $this->catcode = $catcode; }
		function getCatCode() { return $this->catcode; }
		function setCatName($catname) { $this->catname = $catname; }
		function getCatName() { return $this->catname; }
		function setIsRating($israting) { $this->israting = $israting; }
		function getIsRating() { return $this->israting; }
        function setBU($bu) { $this->bu = $bu; }
		function getBU() { return $this->bu; }
		function setRating($rating) { $this->rating = $rating; }
		function getRating() { return $this->rating; }

		public function __construct() {
			$db = new DbConnect();
			$this->dbConn = $db->connect();
		}
		public function getCountOfAllItems(){
			$totalItemsQuery = "SELECT COUNT(*) AS totalItems FROM $this->tableName";
			$result = $this->dbConn->query($totalItemsQuery);
			return $totalItems = $result->fetchColumn();
		}

		public function getAllCategory($page,$limit) {
		
		$offset = ($page - 1) * $limit;
			// SQL query to select all records from the customers table
		$sql = "SELECT [cat_code]
						,[cat_name]
						,[isRating]
						,[BU]
						,[rating] FROM " . $this->tableName .
						"ORDER BY cat_code
						OFFSET $offset ROWS FETCH NEXT $limit ROWS ONLY";
	
			// Prepare the SQL statement
			$stmt = $this->dbConn->prepare($sql);
			
			// Execute the SQL statement
			$stmt->execute();
			
			// Fetch all records as an associative array
			$responseEmployee = $stmt->fetchAll();
			
			// Return the array of customers
			return $responseEmployee;
		}
	}
 ?>