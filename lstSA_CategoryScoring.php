<?php 
	class lstSA_CategoryScoring {
        private $id;
		private $batchno;
		private $userinfo;
		private $datevisit;
		private $branchcode;
		private $accuracyandproperhandlingofcashfunds;
		private $cashcountcompliance;
		private $endorsementandmonitoring;
        private $controlsoverothercashitems;
        private $regulationsandrequirements;
        private $interiorandexteriorstoreappearing;
        private $customerservice;
        private $personnelcompleteuniform;
        private $updatingcompletelistofstorepersonnel;
        private $securityguardsiccompliance;
        private $facilitiesandequipment;
        private $marketingcollateralsandactivation;
        private $stockscompliance;
        private $grandtotal;
		private $tableName = '[lstSA_CategoryScoring]';
		private $dbConn;

		function setID($id) { $this->id = $id; }
		function getID() { return $this->id; }
		function setBatchNo($batchno) { $this->batchno = $batchno; }
		function getBatchNo() { return $this->batchno; }
		function setUserInfo($userinfo) { $this->userinfo = $userinfo; }
		function getUserInfo() { return $this->userinfo; }
        function setDateVisit($datevisit) { $this->datevisit = $datevisit; }
		function getDateVisit() { return $this->datevisit; }
		function setBranchCode($branchcode) { $this->branchcode = $branchcode; }
		function getBranchCode() { return $this->branchcode; }
		function setAccuracyAndProperHandlingOfCashFunds($accuracyandproperhandlingofcashfunds) { $this->accuracyandproperhandlingofcashfunds = $accuracyandproperhandlingofcashfunds; }
		function getAccuracyAndProperHandlingOfCashFunds() { return $this->accuracyandproperhandlingofcashfunds; }
		function setCashCountCompliance($cashcountcompliance) { $this->cashcountcompliance = $cashcountcompliance; }
		function getCashCountCompliance() { return $this->cashcountcompliance; }
		function setEndorsementAndMonitoring($endorsementandmonitoring) { $this->endorsementandmonitoring = $endorsementandmonitoring; }
		function getEndorsementAndMonitoring() { return $this->endorsementandmonitoring; }
        function setControlsOverOtherCashItems($controlsoverothercashitems) { $this->controlsoverothercashitems = $controlsoverothercashitems; }
		function getControlsOverOtherCashItems() { return $this->controlsoverothercashitems; }
        function setRegulationsAndRequirements($regulationsandrequirements) { $this->regulationsandrequirements = $regulationsandrequirements; }
		function getRegulationsAndRequirements() { return $this->regulationsandrequirements; }
        function setInteriorAndExteriorStoreAppearing($interiorandexteriorstoreappearing) { $this->interiorandexteriorstoreappearing = $interiorandexteriorstoreappearing; }
		function getInteriorAndExteriorStoreAppearing() { return $this->interiorandexteriorstoreappearing; }
        function setCustomerService($customerservice) { $this->customerservice = $customerservice; }
		function getCustomerService() { return $this->customerservice; }
        function setPersonnelCompleteUniform($personnelcompleteuniform) { $this->personnelcompleteuniform = $personnelcompleteuniform; }
		function getPersonnelCompleteUniform() { return $this->personnelcompleteuniform; }
        function setUpdatingCompleteListOfStorePersonnel($updatingcompletelistofstorepersonnel) { $this->updatingcompletelistofstorepersonnel = $updatingcompletelistofstorepersonnel; }
		function getUpdatingCompleteListOfStorePersonnel() { return $this->updatingcompletelistofstorepersonnel; }
        function setSecurityGuardSicCompliance($securityguardsiccompliance) { $this->securityguardsiccompliance = $securityguardsiccompliance; }
		function getSecurityGuardSicCompliance() { return $this->securityguardsiccompliance; }
        function setFacilitiesAndEquipment($facilitiesandequipment) { $this->facilitiesandequipment = $facilitiesandequipment; }
		function getFacilitiesAndEquipment() { return $this->facilitiesandequipment; }
        function setMarketingCollateralsAndActivation($marketingcollateralsandactivation) { $this->marketingcollateralsandactivation = $marketingcollateralsandactivation; }
		function getMarketingCollateralsAndActivation() { return $this->marketingcollateralsandactivation; }
        function setStocksCompliance($stockscompliance) { $this->stockscompliance = $stockscompliance; }
		function getStocksCompliance() { return $this->stockscompliance; }
        function setGrandTotal($grandtotal) { $this->grandtotal = $grandtotal; }
		function getGrandTotal() { return $this->grandtotal; }
        
		public function __construct() {
			$db = new DbConnect();
			$this->dbConn = $db->connect();
		}

		public function getCountOfAllItems(){
			$totalItemsQuery = "SELECT COUNT(*) AS totalItems FROM $this->tableName";
			$result = $this->dbConn->query($totalItemsQuery);
			return $totalItems = $result->fetchColumn();
		}

		public function getAllCategoryScoring($page,$limit) {
			
			$offset = ($page - 1) * $limit;
				// SQL query to select all records from the customers table
			$sql = "SELECT [ID]
							,[Batch_No]
							,[User_Info]
							,[Date_Visit]
							,[Branch_Code]
							,[Accuracy_and_Proper_Handling_Of_Cash_Funds]
							,[Cash_Count_Compliance]
							,[Endorsement_and_Monitoring]
							,[Controls_Over_Other_Cash_Items]
							,[Regulations_and_Requirements]
							,[Interior_and_Exterior_Store_Appearing]
							,[Customer_Service]
							,[Personnel_Complete_Uniform]
							,[Updating_Complete_List_of_Store_Personnel]
							,[Security_Guard_Sic_Compliance]
							,[Facilities_and_Equipment]
							,[Marketing_Collaterals_and_Activation]
							,[Stocks_Compliance]
							,[Grand_Total] FROM " . $this->tableName .
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

		public function insertCategoryScoring(){
            $sql = 'INSERT INTO ' . $this->tableName . '([Batch_No],[User_Info], [Date_Visit], 
            [Branch_Code], [Accuracy_and_Proper_Handling_Of_Cash_Funds], [Cash_Count_Compliance], 
			[Endorsement_and_Monitoring], [Controls_Over_Other_Cash_Items], 
            [Regulations_and_Requirements], [Interior_and_Exterior_Store_Appearing], [Customer_Service],
			[Personnel_Complete_Uniform], [Updating_Complete_List_of_Store_Personnel], 
			[Security_Guard_Sic_Compliance], [Facilities_and_Equipment], 
			[Marketing_Collaterals_and_Activation], [Stocks_Compliance], [Grand_Total]) 
            VALUES(?,?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)';

			$stmt = $this->dbConn->prepare($sql);
			$result = $stmt->execute([$this->batchno, $this->userinfo, $this->datevisit,
            $this->branchcode, $this->accuracyandproperhandlingofcashfunds, $this->cashcountcompliance, $this->endorsementandmonitoring,
            $this->controlsoverothercashitems, $this->regulationsandrequirements, $this->interiorandexteriorstoreappearing, $this->customerservice, $this->personnelcompleteuniform,
            $this->updatingcompletelistofstorepersonnel, $this->securityguardsiccompliance, $this->facilitiesandequipment, $this->marketingcollateralsandactivation,
             $this->stockscompliance, $this->grandtotal]);
			if($result) {
				return true;
			} else {
				return false;
			}
        }
	}
 ?>