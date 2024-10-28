<?php
class Api extends Rest
{
	public function __construct()
	{
		parent::__construct();
	}

	public function generateToken()
	{
		if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
			$this->throwError(REQUEST_METHOD_NOT_VALID, 'Request Method is not valid. ' . $_SERVER['REQUEST_METHOD']);
		}
		$email = $this->validateParameter('email', $this->param['email'], STRING,true);
		$pass = $this->validateParameter('pass', $this->param['pass'], STRING,true);
		try {
			// print_r($this->param);
			// $stmt = $this->dbConn->prepare("
			// DECLARE @inputPassword VARCHAR(50) = :pass;
			// SELECT id,activestatus
			// FROM STORE_AUDIT_API_USERS 
			// WHERE email = :email 
			// AND user_pass = HASHBYTES('SHA2_256', @inputPassword)
			// ");
			$stmt = $this->dbConn->prepare("
				SELECT id, activestatus
				FROM STORE_AUDIT_API_USERS 
				WHERE email = :email 
				AND user_pass = SHA2(:pass, 256)
			");
			$stmt->bindParam(":email", $email);
			$stmt->bindParam(":pass", $pass);
			$stmt->execute();
			$user = $stmt->fetch(PDO::FETCH_ASSOC);
			if (!is_array($user)) {
				$this->returnResponse(INVALID_USER_PASS, "Email or Password is incorrect.");
			}

			if ($user['activestatus'] == 0) {
				$this->returnResponse(USER_NOT_ACTIVE, "User is not activated. Please contact to admin.");
			}

			$paylod = [
				'iat' => time(),
				'iss' => 'localhost',
				'exp' => time() + (15 * 60),
				'userId' => $user['id']
			];

			$token = JWT::encode($paylod, SECRETE_KEY);

			$data = ['token' => $token];
			$this->returnResponse(SUCCESS_RESPONSE, $data);
		} catch (Exception $e) {
			$this->throwError(JWT_PROCESSING_ERROR, $e->getMessage());
		}
	}

	public function bulkInsertResponseHeader()
	{
		if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
			$this->throwError(REQUEST_METHOD_NOT_VALID, 'Request Method is not valid. ' . $_SERVER['REQUEST_METHOD']);
		}
		// Retrieve the POSTed JSON data
		$json = file_get_contents('php://input');
		$dataArray = json_decode($json, true);
		$bulkData = $dataArray['param'];

		if (json_last_error() === JSON_ERROR_NONE && is_array($bulkData)) {
			$responseHRD = new lstSA_ResponseHDR;
			if (!$responseHRD->bulkInsertResponseHeader($bulkData)) {
				$message = 'Failed to insert. Error encountered in trying to insert.';
			} else {
				$message = "Inserted successfully.";
			}
		}
		else {
			$message = "Invalid JSON format.";
		}
		$this->returnResponse(SUCCESS_RESPONSE, $message);
	}

	public function bulkInsertResponseDTL()
	{
		if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
			$this->throwError(REQUEST_METHOD_NOT_VALID, 'Request Method is not valid. ' . $_SERVER['REQUEST_METHOD']);
		}
		// Retrieve the POSTed JSON data
		$json = file_get_contents('php://input');
		$dataArray = json_decode($json, true);
		$bulkData = $dataArray['param'];

		if (json_last_error() === JSON_ERROR_NONE && is_array($bulkData)) {
			$responseDTL = new lstSA_ResponseDTL;
			if (!$responseDTL->bulkInsertResponseDTL($bulkData)) {
				$message = "Error in trying to insert the data.";
			} else {
				$message = "Inserted successfully.";
			}
		}
		else {
			$message = "Invalid JSON format.";
		}
		$this->returnResponse(SUCCESS_RESPONSE, $message);
	}

	public function insertResponseHeader()
	{
		if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
			$this->throwError(REQUEST_METHOD_NOT_VALID, 'Request Method is not valid. ' . $_SERVER['REQUEST_METHOD']);
		}
		$Batch_ID = $this->validateParameter('Batch_ID', $this->param['Batch_ID'], INTEGER, true);
		$Branch_Code = $this->validateParameter('Branch_Code', $this->param['Branch_Code'], STRING, true);
		$Branch_Name = $this->validateParameter('Branch_Name', $this->param['Branch_Name'], STRING, true);
		$Branch_Address = $this->validateParameter('Branch_Address', $this->param['Branch_Address'], STRING, true);
		$Branch_GeoLoc = $this->validateParameter('Branch_GeoLoc', $this->param['Branch_GeoLoc'], STRING, true);
		$Branch_AreaOP = $this->validateParameter('Branch_AreaOP', $this->param['Branch_AreaOP'], STRING, true);
		$Branch_DisSup = $this->validateParameter('Branch_DisSup', $this->param['Branch_DisSup'], STRING, true);
		$Branch_OIC = $this->validateParameter('Branch_OIC', $this->param['Branch_OIC'], STRING, true);
		$Date_Visit = $this->validateParameter('Date_Visit', $this->param['Date_Visit'], STRING, true);
		$Title = $this->validateParameter('Title', $this->param['Title'], STRING, true);
		$Survey_Status = $this->validateParameter('Survey_Status', $this->param['Survey_Status'], STRING, true);
		$User_Info = $this->validateParameter('User_Info', $this->param['User_Info'], STRING, true);
		$SIC_Signature = $this->validateParameter('SIC_Signature', $this->param['SIC_Signature'], STRING, true);
		$Count_Yes = $this->validateParameter('Count_Yes', $this->param['Count_Yes'], INTEGER, true);
		$CountNo = $this->validateParameter('CountNo', $this->param['CountNo'], INTEGER, true);
		$Status = $this->validateParameter('Status', $this->param['Status'], STRING, true);
		$Total_Answered = $this->validateParameter('Total_Answered', $this->param['Total_Answered'], INTEGER, true);
		$Screen = $this->validateParameter('Screen', $this->param['Screen'], STRING, true);

		$responseHRD = new lstSA_ResponseHDR;
		$responseHRD->setBatchID($Batch_ID);
		$responseHRD->setBranchCode($Branch_Code);
		$responseHRD->setBranchName($Branch_Name);
		$responseHRD->setBranchAddress($Branch_Address);
		$responseHRD->setBranchGeoLoc($Branch_GeoLoc);
		$responseHRD->setBranchAreaOP($Branch_AreaOP);
		$responseHRD->setBranchDisSup($Branch_DisSup);
		$responseHRD->setBranchOIC($Branch_OIC);
		$responseHRD->setDateVisit($Date_Visit);
		$responseHRD->setTitle($Title);
		$responseHRD->setSurveyStatus($Survey_Status);
		$responseHRD->setUserInfo($User_Info);
		$responseHRD->setSicSignature($SIC_Signature);
		$responseHRD->setCountYes($Count_Yes);
		$responseHRD->setCountNo($CountNo);
		$responseHRD->setStatus($Status);
		$responseHRD->setTotalAnswered($Total_Answered);
		$responseHRD->setScreen($Screen);

		if (!$responseHRD->insertResponseHeader()) {
			$message = 'Failed to insert.';
		} else {
			$message = "Inserted successfully.";
		}

		$this->returnResponse(SUCCESS_RESPONSE, $message);
	}

	public function insertResponseDTL()
	{
		if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
			$this->throwError(REQUEST_METHOD_NOT_VALID, 'Request Method is not valid. ' . $_SERVER['REQUEST_METHOD']);
		}

		$Batch_No = $this->validateParameter('Batch_No', $this->param['Batch_No'], INTEGER, true);
		$Question_ID = $this->validateParameter('Question_ID', $this->param['Question_ID'], INTEGER, true);
		$Response_Desc = $this->validateParameter('Response_Desc', $this->param['Response_Desc'], STRING, true);
		$Branch_Code = $this->validateParameter('Branch_Code', $this->param['Branch_Code'], STRING, true);
		$User_Info = $this->validateParameter('User_Info', $this->param['User_Info'], STRING, true);
		$Res_Resk = $this->validateParameter('Res_Resk', $this->param['Res_Resk'], STRING, true);
		$Res_Option = $this->validateParameter('Res_Option', $this->param['Res_Option'], STRING, true);
		$Res_Rating = $this->validateParameter('Res_Rating', $this->param['Res_Rating'], INTEGER, true);
		$Category = $this->validateParameter('Category', $this->param['Category'], INTEGER, true);
		$Critical_Findings = $this->validateParameter('Critical_Findings', $this->param['Critical_Findings'], STRING, true);
		$BU_Code = $this->validateParameter('BU_Code', $this->param['BU_Code'], STRING, true);
		$Created = $this->validateParameter('Created', $this->param['Created'], STRING, true);

		$responseHRD = new lstSA_ResponseDTL;
		$responseHRD->setBatchNo($Batch_No);
		$responseHRD->setQuestionID($Question_ID);
		$responseHRD->setResponseDesc($Response_Desc);
		$responseHRD->setBranchCode($Branch_Code);
		$responseHRD->setUserInfo($User_Info);
		$responseHRD->setResResk($Res_Resk);
		$responseHRD->setResOption($Res_Option);
		$responseHRD->setResRating($Res_Rating);
		$responseHRD->setCategory($Category);
		$responseHRD->setCriticalFindings($Critical_Findings);
		$responseHRD->setBuCode($BU_Code);
		$responseHRD->setCreated($Created);

		if (!$responseHRD->insertResponseDTL()) {
			$message = 'Failed to insert.';
		} else {
			$message = "Inserted successfully.";
		}

		$this->returnResponse(SUCCESS_RESPONSE, $message);
	}

	public function insertCategoryScoring()
	{
		if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
			$this->throwError(REQUEST_METHOD_NOT_VALID, 'Request Method is not valid. ' . $_SERVER['REQUEST_METHOD']);
		}
		$Batch_No = $this->validateParameter('Batch_ID', $this->param['Batch_No'], INTEGER, true);
		$User_Info = $this->validateParameter('Branch_Code', $this->param['User_Info'], STRING, true);
		$Date_Visit = $this->validateParameter('Branch_Name', $this->param['Date_Visit'], STRING, true);
		$Branch_Code = $this->validateParameter('Branch_Address', $this->param['Branch_Code'], STRING, true);
		$Accuracy_and_Proper_Handling_Of_Cash_Funds = $this->validateParameter('Accuracy_and_Proper_Handling_Of_Cash_Funds', $this->param['Accuracy_and_Proper_Handling_Of_Cash_Funds'], INTEGER, true);
		$Cash_Count_Compliance = $this->validateParameter('Cash_Count_Compliance', $this->param['Cash_Count_Compliance'], INTEGER, true);
		$Endorsement_and_Monitoring = $this->validateParameter('Endorsement_and_Monitoring', $this->param['Endorsement_and_Monitoring'], INTEGER, true);
		$Controls_Over_Other_Cash_Items = $this->validateParameter('Controls_Over_Other_Cash_Items', $this->param['Controls_Over_Other_Cash_Items'], INTEGER, true);
		$Regulations_and_Requirements = $this->validateParameter('Regulations_and_Requirements', $this->param['Regulations_and_Requirements'], INTEGER, true);
		$Interior_and_Exterior_Store_Appearing = $this->validateParameter('Interior_and_Exterior_Store_Appearing', $this->param['Interior_and_Exterior_Store_Appearing'], INTEGER, true);
		$Customer_Service = $this->validateParameter('Customer_Service', $this->param['Customer_Service'], INTEGER, true);
		$Personnel_Complete_Uniform = $this->validateParameter('Personnel_Complete_Uniform', $this->param['Personnel_Complete_Uniform'], INTEGER, true);
		$Updating_Complete_List_of_Store_Personnel = $this->validateParameter('Updating_Complete_List_of_Store_Personnel', $this->param['Updating_Complete_List_of_Store_Personnel'], INTEGER, true);
		$Security_Guard_Sic_Compliance = $this->validateParameter('Security_Guard_Sic_Compliance', $this->param['Security_Guard_Sic_Compliance'], INTEGER, true);
		$Facilities_and_Equipment = $this->validateParameter('Facilities_and_Equipment', $this->param['Facilities_and_Equipment'], INTEGER, true);
		$Marketing_Collaterals_and_Activation = $this->validateParameter('Marketing_Collaterals_and_Activation', $this->param['Marketing_Collaterals_and_Activation'], INTEGER, true);
		$Stocks_Compliance = $this->validateParameter('Stocks_Compliance', $this->param['Stocks_Compliance'], INTEGER, true);
		$Grand_Total = $this->validateParameter('Grand_Total', $this->param['Grand_Total'], INTEGER, true);

		$responseScoring = new lstSA_CategoryScoring;
		$responseScoring->setBatchNo($Batch_No);
		$responseScoring->setUserInfo($User_Info);
		$responseScoring->setDateVisit($Date_Visit);
		$responseScoring->setBranchCode($Branch_Code);
		$responseScoring->setAccuracyAndProperHandlingOfCashFunds($Accuracy_and_Proper_Handling_Of_Cash_Funds);
		$responseScoring->setCashCountCompliance($Cash_Count_Compliance);
		$responseScoring->setEndorsementAndMonitoring($Endorsement_and_Monitoring);
		$responseScoring->setControlsOverOtherCashItems($Controls_Over_Other_Cash_Items);
		$responseScoring->setRegulationsAndRequirements($Regulations_and_Requirements);
		$responseScoring->setInteriorAndExteriorStoreAppearing($Interior_and_Exterior_Store_Appearing);
		$responseScoring->setCustomerService($Customer_Service);
		$responseScoring->setPersonnelCompleteUniform($Updating_Complete_List_of_Store_Personnel);
		$responseScoring->setUpdatingCompleteListOfStorePersonnel($Security_Guard_Sic_Compliance);
		$responseScoring->setSecurityGuardSicCompliance($Facilities_and_Equipment);
		$responseScoring->setFacilitiesAndEquipment($Marketing_Collaterals_and_Activation);
		$responseScoring->setMarketingCollateralsAndActivation($Stocks_Compliance);
		$responseScoring->setStocksCompliance($Grand_Total);
		$responseScoring->setGrandTotal($Grand_Total);

		if (!$responseScoring->insertCategoryScoring()) {
			$message = 'Failed to insert.';
		} else {
			$message = "Inserted successfully.";
		}

		$this->returnResponse(SUCCESS_RESPONSE, $message);
	}

	public function getAllResponseHeader()
	{
		if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
			$this->throwError(REQUEST_METHOD_NOT_VALID, 'Request Method is not valid. ' . $_SERVER['REQUEST_METHOD']);
		}

		$page = $this->page;
		$limit = $this->limit;
		
		$HDR = new lstSA_ResponseHDR;
		$responesHDR = $HDR->getAllResponseHeader($page,$limit);
		$response = [];

		$totalItemsQuery = $HDR->getCountOfAllItems();

		if (is_array($responesHDR) && !empty($responesHDR)) {
			// Loop through each customer record
			foreach ($responesHDR as $responesHDRs) {
				$response[] = [
					'Batch_ID' => $responesHDRs['Batch_ID'],
					'Branch_Code' => $responesHDRs['Branch_Code'],
					'Branch_Name' => $responesHDRs['Branch_Name'],
					'Branch_Address' => $responesHDRs['Branch_Address'],
					'Branch_GeoLoc' => $responesHDRs['Branch_GeoLoc'],
					'Branch_AreaOP' => $responesHDRs['Branch_AreaOP'],
					'Branch_DisSup' => $responesHDRs['Branch_DisSup'],
					'Branch_OIC' => $responesHDRs['Branch_OIC'],
					'Date_Visit' => $responesHDRs['Date_Visit'],
					'Title' => $responesHDRs['Title'],
					'User_Info' => $responesHDRs['User_Info'],
					'SIC_Signature' => $responesHDRs['SIC_Signature'],
					'Count_Yes' => $responesHDRs['Count_Yes'],
					'CountNo' => $responesHDRs['CountNo'],
					'Status' => $responesHDRs['Status'],
					'Total_Answered' => $responesHDRs['Total_Answered'],
					'Screen' => $responesHDRs['Screen']
				];
			}

		} else {
			$response['error'] = 'No customer data found';
		}

		//$this->returnResponse(SUCCESS_RESPONSE, $response);
		$totalPages = ceil($totalItemsQuery / $limit);
		$totaldataonthispage = count($responesHDR);

		header("content-type: application/json");
		$responseContent = json_encode(['response' => ['Status' => 200, 'Current page' => $page ,'Total data on this page' => $totaldataonthispage ,'Total Overall data' => $totalItemsQuery, 'Total Page' => $totalPages, 'result' => $response]]);
		echo $responseContent;
		exit;
	}

	public function getAllResponseDTL(){

		if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
			$this->throwError(REQUEST_METHOD_NOT_VALID, 'Request Method is not valid. ' . $_SERVER['REQUEST_METHOD']);
		}

		$page = $this->page;
		$limit = $this->limit;

		$DTL = new lstSA_ResponseDTL;
		$responseDTL = $DTL->getAllResponseDTL($page, $limit);
		$response = [];

		$totalItemsQuery = $DTL->getCountOfAllItems();

		if (is_array($responseDTL) && !empty($responseDTL)) {
			// Loop through each customer record
			foreach ($responseDTL as $responseDTLs) {
				$response[] = [
					'ID' => $responseDTLs['ID'],
					'Batch_No' => $responseDTLs['Batch_No'],
					'Question_ID' => $responseDTLs['Question_ID'],
					'Response_Desc' => $responseDTLs['Response_Desc'],
					'Branch_Code' => $responseDTLs['Branch_Code'],
					'User_Info' => $responseDTLs['User_Info'],
					'Res_Resk' => $responseDTLs['Res_Resk'],
					'Res_Rating' => $responseDTLs['Res_Rating'],
					'Res_Option' => $responseDTLs['Res_Option'],
					'Category' => $responseDTLs['Category'],
					'Critical_Findings' => $responseDTLs['Critical_Findings'],
					'BU_Code' => $responseDTLs['BU_Code'],
					'Created' => $responseDTLs['Created']
				];
			}
		} else {
			$response['error'] = 'No Employee data found';
		}

		$totalPages = ceil($totalItemsQuery / $limit);
		$totaldataonthispage = count($responseDTL);

		//$this->returnResponse(SUCCESS_RESPONSE, $response);

		
		header("content-type: application/json");
		$responseContent = json_encode(['response' => ['Status' => 200, 'Current page' => $page ,'Total data on this page' => $totaldataonthispage ,'Total Overall data' => $totalItemsQuery, 'Total Page' => $totalPages, 'result' => $response]]);
		echo $responseContent;
		exit;
	}
	
	public function getAllEmployee()
	{
		if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
			$this->throwError(REQUEST_METHOD_NOT_VALID, 'Request Method is not valid. ' . $_SERVER['REQUEST_METHOD']);
		}

		$page = $this->page;
		$limit = $this->limit;

	
		$emp = new lstSA_Employee;
		$responseEmployee = $emp->getAllEmployee($page, $limit);
		$response = [];

		$totalItemsQuery = $emp->getCountOfAllItems();

		if (is_array($responseEmployee) && !empty($responseEmployee)) {
			// Loop through each customer record
			foreach ($responseEmployee as $responseEmployees) {
				$response[] = [
					'ID' => $responseEmployees['ID'],
					'EMPLOYEE_NO' => $responseEmployees['EMPLOYEE_NO'],
					'FIRSTNAME' => $responseEmployees['FIRSTNAME'],
					'MIDDLENAME' => $responseEmployees['MIDDLENAME'],
					'LASTNAME' => $responseEmployees['LASTNAME'],
					'POSITION' => $responseEmployees['POSITION'],
					'UNIT' => $responseEmployees['UNIT'],
					'BU' => $responseEmployees['BU']
				];
			}
		} else {
			$response['error'] = 'No Employee data found';
		}

		//$this->returnResponse(SUCCESS_RESPONSE, $response);


		$totalPages = ceil($totalItemsQuery / $limit);
		$totaldataonthispage = count($responseEmployee);
		
		header("content-type: application/json");
		$responseContent = json_encode(['response' => ['Status' => 200, 'Current page' => $page ,'Total data on this page' => $totaldataonthispage ,'Total Overall data' => $totalItemsQuery, 'Total Page' => $totalPages, 'result' => $response]]);
		echo $responseContent;
		exit;
	}

	public function getAllStore()
	{
		if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
			$this->throwError(REQUEST_METHOD_NOT_VALID, 'Request Method is not valid. ' . $_SERVER['REQUEST_METHOD']);
		}

		$page = $this->page;
		$limit = $this->limit;

		$store = new lstSA_Store;
		$responesStore = $store->getAllStore($page,$limit);
		$response = [];

		$totalItemsQuery = $store->getCountOfAllItems();
	

		if (is_array($responesStore) && !empty($responesStore)) {
			// Loop through each customer record
			foreach ($responesStore as $responesStores) {
				$response[] = [
					'Store_ID' => $responesStores['Store_ID'],
					'Store_Code' => $responesStores['Store_Code'],
					'Store_Name' => $responesStores['Store_Name'],
					'Store_Address' => $responesStores['Store_Address'],
					'Store_GeoLoc' => $responesStores['Store_GeoLoc'],
					'Store_AreaManager' => $responesStores['Store_AreaManager'],
					'Store_DisSup' => $responesStores['Store_DisSup'],
					'Store_IC' => $responesStores['Store_IC'],
					'Store_BU' => $responesStores['Store_BU']
				];
			}

		} else {
			$response['error'] = 'No Store data found';
		}
		//$this->returnResponse(SUCCESS_RESPONSE, $response);
		$totalPages = ceil($totalItemsQuery / $limit);
		$totaldataonthispage = count($responesStore);

		header("content-type: application/json");
		$responseContent = json_encode(['response' => ['Status' => 200, 'Current page' => $page ,'Total data on this page' => $totaldataonthispage ,'Total Overall data' => $totalItemsQuery, 'Total Page' => $totalPages, 'result' => $response]]);
		echo $responseContent;
		exit;
	}

	public function getAllCategoryScoring()
	{
		if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
			$this->throwError(REQUEST_METHOD_NOT_VALID, 'Request Method is not valid. ' . $_SERVER['REQUEST_METHOD']);
		}

		$page = $this->page;
		$limit = $this->limit;

		$catScoring = new lstSA_CategoryScoring;
		$responesScoring = $catScoring->getAllCategoryScoring($page,$limit);
		$response = [];

		$totalItemsQuery = $catScoring->getCountOfAllItems();
	

		if (is_array($responesScoring) && !empty($responesScoring)) {
			// Loop through each customer record
			foreach ($responesScoring as $responesScorings) {
				$response[] = [
					'ID' => $responesScorings['ID'],
					'Batch_No' => $responesScorings['Batch_No'],
					'User_Info' => $responesScorings['User_Info'],
					'Date_Visit' => $responesScorings['Date_Visit'],
					'Branch_Code' => $responesScorings['Branch_Code'],
					'Accuracy_and_Proper_Handling_Of_Cash_Funds' => $responesScorings['Accuracy_and_Proper_Handling_Of_Cash_Funds'],
					'Cash_Count_Compliance' => $responesScorings['Cash_Count_Compliance'],
					'Endorsement_and_Monitoring' => $responesScorings['Endorsement_and_Monitoring'],
					'Controls_Over_Other_Cash_Items' => $responesScorings['Controls_Over_Other_Cash_Items'],
					'Regulations_and_Requirements' => $responesScorings['Regulations_and_Requirements'],
					'Interior_and_Exterior_Store_Appearing' => $responesScorings['Interior_and_Exterior_Store_Appearing'],
					'Customer_Service' => $responesScorings['Customer_Service'],
					'Personnel_Complete_Uniform' => $responesScorings['Personnel_Complete_Uniform'],
					'Updating_Complete_List_of_Store_Personnel' => $responesScorings['Updating_Complete_List_of_Store_Personnel'],
					'Security_Guard_Sic_Compliance' => $responesScorings['Security_Guard_Sic_Compliance'],
					'Facilities_and_Equipment' => $responesScorings['Facilities_and_Equipment'],
					'Marketing_Collaterals_and_Activation' => $responesScorings['Marketing_Collaterals_and_Activation'],
					'Stocks_Compliance' => $responesScorings['Stocks_Compliance'],
					'Grand_Total' => $responesScorings['Grand_Total']
				];
			}

		} else {
			$response['error'] = 'No Store data found';
		}
		//$this->returnResponse(SUCCESS_RESPONSE, $response);
		$totalPages = ceil($totalItemsQuery / $limit);
		$totaldataonthispage = count($responesScoring);

		header("content-type: application/json");
		$responseContent = json_encode(['response' => ['Status' => 200, 'Current page' => $page ,'Total data on this page' => $totaldataonthispage ,'Total Overall data' => $totalItemsQuery, 'Total Page' => $totalPages, 'result' => $response]]);
		echo $responseContent;
		exit;
	}

	public function getAllCategory()
	{
		if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
			$this->throwError(REQUEST_METHOD_NOT_VALID, 'Request Method is not valid. ' . $_SERVER['REQUEST_METHOD']);
		}

		$page = $this->page;
		$limit = $this->limit;

		$category = new lstSA_Category;
		$responesCategory = $category->getAllCategory($page,$limit);
		$response = [];

		$totalItemsQuery = $category->getCountOfAllItems();
	

		if (is_array($responesCategory) && !empty($responesCategory)) {
			// Loop through each customer record
			foreach ($responesCategory as $responesCategorys) {
				$response[] = [
					'cat_code' => $responesCategorys['cat_code'],
					'cat_name' => $responesCategorys['cat_name'],
					'isRating' => $responesCategorys['isRating'],
					'BU' => $responesCategorys['BU'],
					'rating' => $responesCategorys['rating']
				];
			}

		} else {
			$response['error'] = 'No Store data found';
		}
		//$this->returnResponse(SUCCESS_RESPONSE, $response);
		$totalPages = ceil($totalItemsQuery / $limit);
		$totaldataonthispage = count($responesCategory);

		header("content-type: application/json");
		$responseContent = json_encode(['response' => ['Status' => 200, 'Current page' => $page ,'Total data on this page' => $totaldataonthispage ,'Total Overall data' => $totalItemsQuery, 'Total Page' => $totalPages, 'result' => $response]]);
		echo $responseContent;
		exit;
	}

	public function getAllQuestion()
	{
		if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
			$this->throwError(REQUEST_METHOD_NOT_VALID, 'Request Method is not valid. ' . $_SERVER['REQUEST_METHOD']);
		}

		$page = $this->page;
		$limit = $this->limit;

		$category = new lstSA_Question;
		$responesQuestion = $category->getAllQuestion($page,$limit);
		$response = [];

		$totalItemsQuery = $category->getCountOfAllItems();
	

		if (is_array($responesQuestion) && !empty($responesQuestion)) {
			// Loop through each customer record
			foreach ($responesQuestion as $responesQuestions) {
				$response[] = [
					'cat_code' => $responesQuestions['cat_code'],
					'cat_name' => $responesQuestions['cat_name'],
					'isRating' => $responesQuestions['isRating'],
					'BU' => $responesQuestions['BU'],
					'rating' => $responesQuestions['rating']
				];
			}

		} else {
			$response['error'] = 'No Store data found';
		}
		//$this->returnResponse(SUCCESS_RESPONSE, $response);
		$totalPages = ceil($totalItemsQuery / $limit);
		$totaldataonthispage = count($responesQuestion);

		header("content-type: application/json");
		$responseContent = json_encode(['response' => ['Status' => 200, 'Current page' => $page ,'Total data on this page' => $totaldataonthispage ,'Total Overall data' => $totalItemsQuery, 'Total Page' => $totalPages, 'result' => $response]]);
		echo $responseContent;
		exit;
	}

	// API for rose loadwallet
	
	public function addCRGLoaEntry(){
		if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
			$this->throwError(REQUEST_METHOD_NOT_VALID, 'Request Method is not valid. ' . $_SERVER['REQUEST_METHOD']);
		}
		$corpid = $this->validateParameter('corpid', $this->param['corpid'], INTEGER,true);
		$branchcode = $this->validateParameter('branchcode', $this->param['branchcode'], STRING, true);
		$employeeid = $this->validateParameter('employeeid', $this->param['employeeid'], INTEGER, true);
		$amount = $this->validateParameter('amount', $this->param['amount'], STRING, true);
		$loaReference = $this->validateParameter('loaReference', $this->param['loaReference'], STRING, false);

		$source = 10;
		$date = date('Y-m-d');
		$time = date('H:i:s');
		$completeDatetime = date('Y-m-d H:i:s');
		$referenceid = "CRGLOA"."-".$corpid."-".$branchcode."-".$employeeid."-".$date."-".$time;
		$message = [];
		
		$loa = new CRGLoa;
		$loa->setReferenceId($referenceid);
		$loa->setCorpId($corpid);
		$loa->setBranchCode($branchcode);
		$loa->setEmployeeId($employeeid);
		$loa->setAmount($amount);
		$loa->setDateofIssue($completeDatetime);
		$loa->setLoaReference($loaReference);

		$newDateTimeTimestamp = strtotime($completeDatetime . ' +10 days');
		$newDateTime = date('Y-m-d H:i:s', $newDateTimeTimestamp);
		$loa->setDateofValidity($newDateTime);
		$loa->setSource($source);
		$result = $loa->insertLoa();
		$responseArray = json_decode($result, true);

		if ($responseArray['success'] == 1) {
			$message = $responseArray;
		} else {
			$message = $responseArray;
		}
		
		$this->returnResponse(SUCCESS_RESPONSE, $message);
	}

	public function updateCRGLoa(){

		if ($_SERVER['REQUEST_METHOD'] !== 'PATCH') 
		{
			$this->throwError(REQUEST_METHOD_NOT_VALID, 'Request Method is not valid. ' . $_SERVER['REQUEST_METHOD']);
		}

		$referenceid = $this->validateParameter('referenceid', $this->param['referenceid'], STRING, true);
		$status = $this->validateParameter('status', $this->param['status'], INTEGER, true);
		$branchCode = $this->validateParameter('branchCode', $this->param['branchCode'], STRING, true);
		$message = [];

		$loa = new CRGLoa;
		$loa->setReferenceId($referenceid);
		$loa->setStatus($status);
		$loa->setBranchCode($branchCode);
		$responseArray = json_decode($loa->updateCRGLoa(), true);
		$message = $responseArray;

		$this->returnResponse(SUCCESS_RESPONSE, $message);
	}
	
	public function addCRGPayments(){
		if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
			$this->throwError(REQUEST_METHOD_NOT_VALID, 'Request Method is not valid. ' . $_SERVER['REQUEST_METHOD']);
		}
		$corpid = $this->validateParameter('corpid', $this->param['corpID'], STRING,true);
		$amount = $this->validateParameter('amountPaid', $this->param['amountPaid'], STRING,true);
		$paymentdate =  date('Y-m-d H:i:s');
		$status = $this->validateParameter('status', $this->param['status'], STRING, true);
		$referenceid = $this->validateParameter('referenceID', $this->param['referenceID'], STRING, true);

		$crgpayments = new CRGPayments;
		$crgpayments->setCorpID($corpid);
		$crgpayments->setAmount($amount);
		$crgpayments->setDate($paymentdate);
		$crgpayments->setStatus($status);
		$crgpayments->setReferenceID($referenceid);
		$result = $crgpayments->insertCRGpayments();
		$responseArray = json_decode($result, true);
		$message = "";
		if ($responseArray['success'] == 1) {
			$message = $responseArray;
		} else {
			$message = $responseArray;
		}
		$this->returnResponse(SUCCESS_RESPONSE, $message);
	}

	public function inquiryLoa(){
		if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
			$this->throwError(REQUEST_METHOD_NOT_VALID, 'Request Method is not valid. ' . $_SERVER['REQUEST_METHOD']);
		}
		$referenceid = $this->validateParameter('referenceID', $this->param['referenceID'], STRING, true);

		$checkStatusLoa = new CRGLoa;
		$checkStatusLoa->setReferenceId($referenceid);
		$response = [];

		if (!$checkStatusLoa->checkLoaStatus()) {
			# code...
			$response['ERROR'] = "Invalid reference number.";
		} else {
			# code...
			$results = $checkStatusLoa->checkLoaStatus();
			foreach ($results as $result) {
				# code...
				$response[] = [
					'corpID' => $result['corpID'],
					'branchcode' => $result['branchCode'],
					'employeeID' => $result['employeeID'],
					'amount' => $result['amount'],
					'dateofIssue' => $result['dateofIssue'],
					'dateofValidity' => $result['dateofValidity'],
					'status' => $result['status'],
					'Source' => $result['Source'],
					'LoaReferenceID' => $result['loaReferenceID'],
				];
			}

		}
		$this->returnResponse(SUCCESS_RESPONSE, $response);
	}

	public function cancelCRGLoa(){
		if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
			$this->throwError(REQUEST_METHOD_NOT_VALID, 'Request Method is not valid. ' . $_SERVER['REQUEST_METHOD']);
		}
		$referenceid = $this->validateParameter('referenceID', $this->param['referenceID'], STRING, true);

		$checkStatusLoa = new CRGLoa;
		$checkStatusLoa->setReferenceId($referenceid);
		$response = [];

		if (!$checkStatusLoa->checkLoaStatus()) {
			# code...
			$response['ERROR'] = "Invalid reference number.";
		} else {
			# code...
			$results = $checkStatusLoa->checkLoaStatus();
			foreach ($results as $result) {
				# code...
				$response[] = [
					'corpID' => $result['corpID'],
					'branchcode' => $result['branchCode'],
					'employeeID' => $result['employeeID'],
					'amount' => $result['amount'],
					'dateofIssue' => $result['dateofIssue'],
					'dateofValidity' => $result['dateofValidity'],
					'status' => $result['status'],
					'Source' => $result['Source'],
				];
			}

		}
		$this->returnResponse(SUCCESS_RESPONSE, $response);
	}
}
