<?php 
	class lstSA_ResponseHDR {
		private $batchid;
		private $branchcode;
		private $branchname;
		private $branchaddress;
		private $branchgeoloc;
        private $branchareaop;
        private $branchdissup;
        private $branchoic;
        private $datevisit;
        private $title;
        private $surveystatus;
        private $userinfo;
        private $sicsignature;
        private $countyes;
        private $countno;
        private $status;
        private $totalanswered;
        private $screen;
		private $tableName = '[lstSA_ResponseHDR]';
		private $dbConn;

		function setBatchID($batchid) { $this->batchid = $batchid; }
		function getBatchID() { return $this->batchid; }
		function setBranchCode($branchcode) { $this->branchcode = $branchcode; }
		function getBranchCode() { return $this->branchcode; }
		function setBranchName($branchname) { $this->branchname = $branchname; }
		function getBranchName() { return $this->branchname; }
        function setBranchAddress($branchaddress) { $this->branchaddress = $branchaddress; }
		function getBranchAddress() { return $this->branchaddress; }
        function setBranchGeoLoc($branchgeoloc) { $this->branchgeoloc = $branchgeoloc; }
		function getBranchGeoLoc() { return $this->branchgeoloc; }
        function setBranchAreaOP($branchareaop) { $this->branchareaop = $branchareaop; }
		function getBranchAreaOP() { return $this->branchareaop; }
        function setBranchDisSup($branchdissup) { $this->branchdissup = $branchdissup; }
		function getBranchDisSup() { return $this->branchdissup; }
        function setBranchOIC($branchoic) { $this->branchoic = $branchoic; }
		function getBranchOIC() { return $this->branchoic; }
        function setDateVisit($datevisit) { $this->datevisit = $datevisit; }
		function getDateVisit() { return $this->datevisit; }
        function setTitle($title) { $this->title = $title; }
		function getTitle() { return $this->title; }
        function setSurveyStatus($surveystatus) { $this->surveystatus = $surveystatus; }
		function getSurveyStatus() { return $this->surveystatus; }
        function setUserInfo($userinfo) { $this->userinfo = $userinfo; }
		function getUserInfo() { return $this->userinfo; }
        function setSicSignature($sicsignature) { $this->sicsignature = $sicsignature; }
		function getSicSignature() { return $this->sicsignature; }
        function setCountYes($countyes) { $this->countyes = $countyes; }
		function getCountYes() { return $this->countyes; }
        function setCountNo($countno) { $this->countno = $countno; }
		function getCountNo() { return $this->countno; }
        function setStatus($status) { $this->status = $status; }
		function getStatus() { return $this->status; }
        function setTotalAnswered($totalanswered) { $this->totalanswered = $totalanswered; }
		function getTotalAnswered() { return $this->totalanswered; }
        function setScreen($screen) { $this->screen = $screen; }
		function getScreen() { return $this->screen; }

		public function __construct() {
			$db = new DbConnect();
			$this->dbConn = $db->connect();
		}

        public function insertResponseHeader(){
            $sql = 'INSERT INTO ' . $this->tableName . '([Batch_ID],[Branch_Code], [Branch_Name], 
            [Branch_Address], [Branch_GeoLoc], [Branch_AreaOP], [Branch_DisSup], [Branch_OIC], 
            [Date_Visit], [Title], [Survey_Status], [User_Info], [SIC_Signature],
            [Count_Yes], [CountNo], [Status], [Total_Answered], [Screen]) 
            VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)';

			$stmt = $this->dbConn->prepare($sql);
			$result = $stmt->execute([$this->batchid, $this->branchcode, $this->branchname,
            $this->branchaddress, $this->branchgeoloc, $this->branchareaop, $this->branchdissup,
            $this->branchoic, $this->datevisit, $this->title, $this->surveystatus, $this->userinfo,
            $this->sicsignature, $this->countyes, $this->countno, $this->status,
             $this->totalanswered, $this->screen]);
			if($result) {
				return true;
			} else {
				return false;
			}
        }

        public function bulkInsertResponseHeader($dataArray){
            
            try {
                
                $this->dbConn->beginTransaction();
                $chunkSize = 100; // Since 100 objects * 18 fields = 1800 which is less than 2000 for the mssql limit parameters
				$totalCount = count($dataArray); // Total number of rows to insert
        
                $insertedCount = 0;
                $skippedCount = 0;
                $rowsAffected=0;

                $checkStmt = $this->dbConn->prepare("SELECT COUNT(*) FROM $this->tableName WHERE Batch_ID = ?");
                
                for ($i=0; $i < $totalCount ; $i+=$chunkSize) 
                { 
                    // Prepare a single insert query for multiple rows
                    $placeholders = [];
                    $values = [];
					$insertedCount = 0;
					$chunk = array_slice($dataArray, $i, $chunkSize);
                    # code...
                    foreach ($chunk as $row) 
                    {
                        // Check if the email already exists
                        $checkStmt->execute([$row["Batch_ID"]]);
                        $exists = $checkStmt->fetchColumn();
            
                        if ($exists == 0) {
                            // Add the placeholders and values for each row
                            $placeholders[] = "(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
                            $values[] = $row["Batch_ID"];
                            $values[] = $row["Branch_Code"];
                            $values[] = $row["Branch_Name"];
                            $values[] = $row["Branch_Address"];
                            $values[] = $row["Branch_GeoLoc"];
                            $values[] = $row["Branch_AreaOP"];
                            $values[] = $row["Branch_DisSup"];
                            $values[] = $row["Branch_OIC"];
                            $values[] = $row["Date_Visit"];
                            $values[] = $row["Title"];
                            $values[] = $row["Survey_Status"];
                            $values[] = $row["User_Info"];
                            $values[] = $row["SIC_Signature"];
                            $values[] = $row["Count_Yes"];
                            $values[] = $row["CountNo"];
                            $values[] = $row["Status"];
                            $values[] = $row["Total_Answered"];
                            $values[] = $row["Screen"];
                            $insertedCount++;
                        } else {
                            $skippedCount++;
                        }
                    }

                    if (!empty($placeholders)) {
                        // Perform bulk insert using one SQL query
                        $sql = "INSERT INTO $this->tableName ([Batch_ID],[Branch_Code], [Branch_Name], 
                                [Branch_Address], [Branch_GeoLoc], [Branch_AreaOP], [Branch_DisSup], [Branch_OIC], 
                                [Date_Visit], [Title], [Survey_Status], [User_Info], [SIC_Signature],
                                [Count_Yes], [CountNo], [Status], [Total_Answered], [Screen]) 
                                VALUES " . implode(", ", $placeholders);
                        $stmt = $this->dbConn->prepare($sql);
                        $stmt->execute($values);
    
                        $rowsAffected = $stmt->rowCount();
                    }
                }
         
                if ($rowsAffected > 0) {
                    $this->dbConn->commit();
                    return true;
                } else {
                   return false;
                }
                
            } catch (Exception $e) {
                 // Roll back the transaction if something failed
                $this->dbConn->rollBack();
                //echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
                return false;
            }
        }

        public function getCountOfAllItems(){
			$totalItemsQuery = "SELECT COUNT(*) AS totalItems FROM $this->tableName";
			$result = $this->dbConn->query($totalItemsQuery);
			return $totalItems = $result->fetchColumn();
		}

        public function getAllResponseHeader($page, $limit) {
            $offset = ($page - 1) * $limit;
            // SQL query to select all records from the customers table
			$sql = "SELECT [Batch_ID]
                            ,[Branch_Code]
                            ,[Branch_Name]
                            ,[Branch_Address]
                            ,[Branch_GeoLoc]
                            ,[Branch_AreaOP]
                            ,[Branch_DisSup]
                            ,[Branch_OIC]
                            ,[Date_Visit]
                            ,[Title]
                            ,[Survey_Status]
                            ,[User_Info]
                            ,[SIC_Signature]
                            ,[Count_Yes]
                            ,[CountNo]
                            ,[Status]
                            ,[Total_Answered]
                            ,[Screen] FROM " . $this->tableName.
                            "ORDER BY Batch_ID
                            OFFSET $offset ROWS FETCH NEXT $limit ROWS ONLY";
		
			// Prepare the SQL statement
			$stmt = $this->dbConn->prepare($sql);
			
			// Execute the SQL statement
			$stmt->execute();
			
			// Fetch all records as an associative array
			$responseHDR = $stmt->fetchAll();
			
			// Return the array of customers
			return $responseHDR;
		}
	}
 ?>