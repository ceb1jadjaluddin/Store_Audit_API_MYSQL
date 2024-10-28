<?php 
	class lstSA_ResponseDTL {
		private $id;
		private $batchno;
		private $questionid;
		private $responsedesc;
		private $branchcode;
		private $userinfo;
		private $resresk;
		private $resoption;
        private $resrating;
        private $category;
        private $criticalfindings;
        private $bucode;
        private $created;
		private $tableName = '[lstSA_ResponseDTL]';
		private $dbConn;

		function setID($id) { $this->id = $id; }
		function getID() { return $this->id; }
		function setBatchNo($batchno) { $this->batchno = $batchno; }
		function getBatchNo() { return $this->batchno; }
		function setQuestionID($questionid) { $this->questionid = $questionid; }
		function getQuestionID() { return $this->questionid; }
        function setResponseDesc($responsedesc) { $this->responsedesc = $responsedesc; }
		function getResponseDesc() { return $this->responsedesc; }
		function setBranchCode($branchcode) { $this->branchcode = $branchcode; }
		function getBranchCode() { return $this->branchcode; }
		function setUserInfo($userinfo) { $this->userinfo = $userinfo; }
		function getUserInfo() { return $this->userinfo; }
		function setResResk($resresk) { $this->resresk = $resresk; }
		function getResResk() { return $this->resresk; }
		function setResOption($resoption) { $this->resoption = $resoption; }
		function getResOption() { return $this->resoption; }
		function setResRating($resrating) { $this->resrating = $resrating; }
		function getResRating() { return $this->resrating; }
        function setCategory($category) { $this->category = $category; }
		function getCategory() { return $this->category; }
        function setCriticalFindings($criticalfindings) { $this->criticalfindings = $criticalfindings; }
		function getCriticalFindings() { return $this->criticalfindings; }
        function setBuCode($bucode) { $this->bucode = $bucode; }
		function getBuCode() { return $this->bucode; }
        function setCreated($created) { $this->created = $created; }
		function getCreated() { return $this->created; }

		public function __construct() {
			$db = new DbConnect();
			$this->dbConn = $db->connect();
		}

		public function getCountOfAllItems(){
			$totalItemsQuery = "SELECT COUNT(*) AS totalItems FROM $this->tableName";
			$result = $this->dbConn->query($totalItemsQuery);
			return $totalItems = $result->fetchColumn();
		}

		public function getAllResponseDTL($page, $limit) {

			$offset = ($page - 1) * $limit;
				// SQL query to select all records from the customers table
			$sql = "SELECT [ID]
							,[Batch_No]
							,[Question_ID]
							,[Response_Desc]
							,[Branch_Code]
							,[User_Info]
							,[Res_Resk]
							,[Res_Option]
							,[Res_Rating]
							,[Category]
							,[Critical_Findings]
							,[BU_Code]
							,[Created] FROM " . $this->tableName . 
							"ORDER BY ID
							OFFSET $offset ROWS FETCH NEXT $limit ROWS ONLY";
		
			// Prepare the SQL statement
			$stmt = $this->dbConn->prepare($sql);
			
			// Execute the SQL statement
			$stmt->execute();
			
			// Fetch all records as an associative array
			$responseHDR = $stmt->fetchAll(PDO::FETCH_ASSOC);
			
			// Return the array of customers
			return $responseHDR;
		}

		public function insertResponseDTL(){
            $sql = 'INSERT INTO ' . $this->tableName . '([Batch_No]
													,[Question_ID]
													,[Response_Desc]
													,[Branch_Code]
													,[User_Info]
													,[Res_Resk]
													,[Res_Option]
													,[Res_Rating]
													,[Category]
													,[Critical_Findings]
													,[BU_Code]
													,[Created]) 
            										VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?, 
													?, ?, ?)';

			$stmt = $this->dbConn->prepare($sql);
			$result = $stmt->execute([$this->batchno, $this->questionid, $this->responsedesc,
            $this->branchcode, $this->userinfo, $this->resresk, $this->resoption,
            $this->resrating, $this->category, $this->criticalfindings, $this->bucode, $this->created]);
			if($result) {
				return true;
			} else {
				return false;
			}
        }

		public function bulkInsertResponseDTL($dataArray){
            
            try {
                
                $this->dbConn->beginTransaction();
				$rowsAffected = 0;
				$chunkSize = 100; // Since 100 objects * 12 fields = 1200 which is less than 2000 for the mssql limit parameters
				$totalCount = count($dataArray); // Total number of rows to insert

                // Prepare a single insert query for multiple rows
            

				for ($i=0; $i < $totalCount ; $i += $chunkSize) { 
					$placeholders = [];
					$values = [];
					$insertedCount = 0;
					$chunk = array_slice($dataArray, $i, $chunkSize);
					
					foreach ($chunk as $row) 
					{
                        // Add the placeholders and values for each row
                        $placeholders[] = "(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
                        $values[] = $row["Batch_No"];
                        $values[] = $row["Question_ID"];
                        $values[] = $row["Response_Desc"];
                        $values[] = $row["Branch_Code"];
                        $values[] = $row["User_Info"];
                        $values[] = $row["Res_Resk"];
                        $values[] = $row["Res_Option"];
                        $values[] = $row["Res_Rating"];
                        $values[] = $row["Category"];
                        $values[] = $row["Critical_Findings"];
                        $values[] = $row["BU_Code"];
                        $values[] = $row["Created"];
                        $insertedCount++;
                   
                	}

					if (!empty($placeholders)) {
						// Perform bulk insert using one SQL query
						$sql = "INSERT INTO $this->tableName ([Batch_No],[Question_ID], [Response_Desc], 
								[Branch_Code], [User_Info], [Res_Resk], [Res_Option], [Res_Rating], 
								[Category], [Critical_Findings], [BU_Code], [Created]) 
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
                return $e->getMessage();
            }
        }
	}
 ?>