<?php 
	class lstSA_Question {
		private $questionid;
		private $categoryid;
		private $subqid;
		private $questiondesc;
		private $buallowed;
		private $attachmentid;
		private $respdatatype;
		private $sortid;
        private $subqidx;
        private $bu;
		private $tableName = '[lstSA_Question]';
		private $dbConn;

		function setQuestionID($questionid) { $this->questionid = $questionid; }
		function getQuestionID() { return $this->questionid; }
		function setCategoryID($categoryid) { $this->categoryid = $categoryid; }
		function getCategoryID() { return $this->categoryid; }
		function setSubqID($subqid) { $this->subqid = $subqid; }
		function getSubqID() { return $this->subqid; }
        function setQuestionDesc($questiondesc) { $this->questiondesc = $questiondesc; }
		function getQuestionDesc() { return $this->questiondesc; }
		function setBUAllowed($buallowed) { $this->buallowed = $buallowed; }
		function getBUAllowed() { return $this->buallowed; }
		function setAttachmentID($attachmentid) { $this->attachmentid = $attachmentid; }
		function getAttachmentID() { return $this->attachmentid; }
		function setRespDataType($respdatatype) { $this->respdatatype = $respdatatype; }
		function getRespDataType() { return $this->respdatatype; }
		function setSortID($sortid) { $this->sortid = $sortid; }
		function getSortID() { return $this->sortid; }
		function setSubqIDX($subqidx) { $this->subqidx = $subqidx; }
		function getSubqIDX() { return $this->subqidx; }
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

		public function getAllQuestion($page,$limit) {
			
			$offset = ($page - 1) * $limit;
				// SQL query to select all records from the customers table
			$sql = "SELECT [Question_ID]
							,[Category_ID]
							,[SubQ_ID]
							,[Question_Desc]
							,[BU_Allowed]
							,[Attachment_ID]
							,[Resp_DataType]
							,[Sort_ID]
							,[SubQ_IDX]
							,[BU] FROM " . $this->tableName .
							"ORDER BY Question_ID
							OFFSET $offset ROWS FETCH NEXT $limit ROWS ONLY";
		
				// Prepare the SQL statement
				$stmt = $this->dbConn->prepare($sql);
				
				// Execute the SQL statement
				$stmt->execute();
				
				// Fetch all records as an associative array
				$responseQuestion = $stmt->fetchAll();
				
				// Return the array of customers
				return $responseQuestion;
		}
	}
 ?>