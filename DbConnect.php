<?php 
	require '../vendor/autoload.php';

	class DbConnect {
		// private $server;
		// private $dbname;
		// private $user;
		// private $pass;

		private $server = 'database';
		private $dbname = 'STORE_AUDIT';
		private $user = 'root';
		private $pass = 'pass321';
		private $port = 3306; // Define the port
		
		// private $server = 'sqlsrv:Server=RPIITDL0037\SQLEXPRESS';
		// private $dbname = 'CHARGEDB';

		// public function __contruct(){

		// 	  // Load the .env file
		// 	  $dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
		// 	  $dotenv->load();

		// 	    // Assign the environment variables to class properties
		// 		$this->server = $_ENV['MYSQL_SERVER'];
		// 		$this->dbname = $_ENV['MYSQL_DATABASE'];
		// 		$this->user = $_ENV['MYSQL_USER'];
		// 		$this->pass = $_ENV['pass123'];
				
		// }

		public function connect() {
			try {
				// $conn = new PDO('mysql:host=' .$this->server .';dbname=' . $this->dbname, $this->user, $this->pass);
				$conn = new PDO('mysql:host=' . $this->server . ';dbname=' . $this->dbname . ';port=' . $this->port, $this->user, $this->pass);

				// $conn = new PDO(
				// 	"sqlsrv:Server=" . $this->server . ";Database=" . $this->dbname,
				// 	$this->user,
				// 	$this->pass
				// );
				$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
				return $conn;
			} catch (\Exception $e) {
				echo "Database Error: " . $e->getMessage();
			}
		}
	}
 ?>