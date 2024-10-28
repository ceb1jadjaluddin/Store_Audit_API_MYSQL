<?php 
	class lstSA_Employee {
        private $id;
		private $employeeno;
		private $firstname;
		private $middlename;
		private $lastname;
		private $position;
		private $unit;
		private $bu;
		private $tableName = '[lstSA_Employee]';
		private $dbConn;

		function setID($id) { $this->id = $id; }
		function getID() { return $this->id; }
		function setEmployeeNo($employeeno) { $this->employeeno = $employeeno; }
		function getEmployeeNo() { return $this->employeeno; }
		function setFirstName($firstname) { $this->firstname = $firstname; }
		function getFirstName() { return $this->firstname; }
        function setMiddleName($middlename) { $this->middlename = $middlename; }
		function getMiddleName() { return $this->middlename; }
		function setLastName($lastname) { $this->lastname = $lastname; }
		function getLastName() { return $this->lastname; }
		function setPosition($position) { $this->position = $position; }
		function getPosition() { return $this->position; }
		function setUnit($unit) { $this->unit = $unit; }
		function getUnit() { return $this->unit; }
		function setBU($bu) { $this->bu = $bu; }
		function getBU() { return $this->bu; }
        

		public function __construct() {
			$db = new DbConnect();
			$this->dbConn = $db->connect();
		}

			public function getCountOfAllItems(){
				$totalItemsQuery = "SELECT COUNT(*) AS totalItems FROM $this->tableName";
				$result = $this->dbConn->query($totalItemsQuery);
				return $totalItems = $result->fetchColumn();
			}

			public function getAllEmployee($page,$limit) {
			
			$offset = ($page - 1) * $limit;
				// SQL query to select all records from the customers table
			$sql = "SELECT [EMPLOYEE_NO]
							,[FIRSTNAME]
							,[MIDDLENAME]
							,[LASTNAME]
							,[POSITION]
							,[UNIT]
							,[BU]
							,[ID] FROM " . $this->tableName .
							"ORDER BY ID
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