<?php

/**
	* Main database connection
	*
	* @abstract 
	* @author Avoaja Ugochukwu
	*/
class DB
{	
	public function db()
	{
		try {
			$this->pdo = new PDO('mysql:host=localhost;dbname=simpleblog', 'avoaj', 'boys2men');
			$this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$this->pdo->exec('SET NAMES "utf8"');
		}

		catch (PDOException $e) {
			$error = 'Unable to connect to database.'. $e->getMessage();
			include '../public/error.html';
			exit();
		}		

		return $this->pdo;
	}
}