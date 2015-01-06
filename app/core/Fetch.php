<?php
	/**
	* @author Avoaja Ugochukwu Charles
	* @abstract For select queries
	*/
	require_once 'Db' . EXT;

	/* set default time zone */
	date_default_timezone_set('Africa/Lagos');


	class Fetch
	{
		public $pdo;

		public function __construct()
		{			
			$this->pdo = new PDO('mysql:host=localhost;dbname=simpleblog', 'avoaj', 'boys2men');
			$this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$this->pdo->exec('SET NAMES "utf8"');

			echo 'Data base ready';
			return $this->pdo;
			
		}



		public function select($table, $order_by = 1, $asc = 'ASC', $limit = 100)
		{					
			$sql = "SELECT * FROM {$table} ORDER BY {$order_by} {$asc} LIMIT {$limit}";
			$this->stmt = $this->pdo->prepare($sql);
			$this->stmt->execute();
					
			return $this->stmt->fetchAll();
		}



		public function fetchHomePage($table1, $table2, $order_by = 1, $asc = 'ASC', $limit = 100)
		{		
			$sql = "SELECT * FROM {$table1}, {$table2} ORDER BY {$order_by} {$asc} LIMIT {$limit}";
			$this->stmt = $this->pdo->prepare($sql);
			$this->stmt->execute();
				
			return $this->stmt->fetchAll();
		}


		public function recentPost($table, $order_by = 1, $asc = 'ASC', $limit1, $limit2)
		{		
			$sql = "SELECT * FROM {$table} ORDER BY {$order_by} {$asc} LIMIT {$limit1}, {$limit2}";
			$this->stmt = $this->pdo->prepare($sql);
			$this->stmt->execute();
				
			return $this->stmt->fetchAll();
		}


		public function fetchWhere($table1, $table2, $where_field, $where_value)
		{			
			$sql = "SELECT * FROM {$table1},  {$table2} WHERE $where_field = :toBindValue AND postAuthor = username";
			$this->stmt = $this->pdo->prepare($sql);
			$this->stmt->bindValue(':toBindValue', $where_value);
			$this->stmt->execute();
			
			return $this->stmt->fetchAll();
		}

		public function fetchWhereSingle($table1, $where_field, $where_value)
		{			
			$sql = "SELECT * FROM {$table1} WHERE $where_field = :toBindValue";
			$this->stmt = $this->pdo->prepare($sql);
			$this->stmt->bindValue(':toBindValue', $where_value);
			$this->stmt->execute();
			
			return $this->stmt->fetchAll();
		}

		public function trimPost($post)
		{		
				$words = explode(" ", $post, 51);
				if(count($words) == 51) {
					$words[50] = "&#10162;";
				}
				$string = implode(" ", $words);
				echo $string;
		}

		public function postDate($date)
		{
			$phpdate       = strtotime($date);
			$formattedDate =  date('j M Y', $phpdate);
			echo $formattedDate;
		}


		public function generateUrl($s) 
		{
			$from = explode (',', "ç,æ,œ,á,é,í,ó,ú,à,è,ì,ò,ù,ä,ë,ï,ö,ü,ÿ,â,ê,î,ô,û,å,e,i,ø,u,(,),[,],'");
			$to = explode (',', 'c,ae,oe,a,e,i,o,u,a,e,i,o,u,a,e,i,o,u,y,a,e,i,o,u,a,e,i,o,u,,,,,,');
			$s = preg_replace ('~[^\w\d]+~', '-', str_replace ($from, $to, trim ($s)));
			return strtolower (preg_replace ('/^-/', '', preg_replace ('/-$/', '', $s)));
		}










		public function paginate($itemsPerPage)
		{	
			define(PARTOFLINK, '&nbsp; <a href="'.$_SERVER['PHP_SELF'].'?pn=');
			define(ENDOFLINK,  '</a> &nbsp;');
			define(ENDANCHOR,  '">');
			
			$this->stmt = $this->pdo->query("SELECT * FROM blog_posts");					
			$numberOfRows = $this->stmt->rowCount();
			/*clean url for non-Number characters*/
			if (isset($_GET['pn'])) {$pageNumber = preg_replace('#[^0-9]#i', '', $_GET['pn']);} else {$pageNumber = 1;}

			
			$lastPage = ceil($numberOfRows / $itemsPerPage);
			/*--Force pageNumber to be within range--*/
			if ($pageNumber < 1) {$pageNumber = 1;} elseif ($pageNumber > $lastPage) {$pageNumber = $lastPage;}

			/*--Create clickable value for ?pn=$1 --*/
			$centerPages = "";
			$sub1 = $pageNumber - 1;
			$sub2 = $pageNumber - 2;
			$add1 = $pageNumber + 1;
			$add2 = $pageNumber + 2;


			define(PAGEACTIVE, '&nbsp; <span class="pagNumActive">'. $pageNumber . '</span>&nbsp;');


			if ($pageNumber == 1) {
				$centerPages .= PAGEACTIVE;
				$centerPages .= PARTOFLINK.$add1.ENDANCHOR.$add1.ENDOFLINK;
			}elseif ($pageNumber == $lastPage) {
				$centerPages .= PARTOFLINK.$sub1.ENDANCHOR.$sub1.ENDOFLINK;
				$centerPages .= PAGEACTIVE;
			}
			elseif ($pageNumber > 2 && $pageNumber < ($lastPage - 1)) {
				$centerPages .= PARTOFLINK.$sub2.ENDANCHOR.$sub2.ENDOFLINK;
				$centerPages .= PARTOFLINK.$sub1.ENDANCHOR.$sub1.ENDOFLINK;
				$centerPages .= PAGEACTIVE;
				$centerPages .= PARTOFLINK.$add1.ENDANCHOR.$add2.ENDOFLINK;
				$centerPages .= PARTOFLINK.$add1.ENDANCHOR.$add1.ENDOFLINK;
			}
			elseif ($pageNumber > 1 && $pageNumber < $lastPage) {
				$centerPages .= PARTOFLINK.$sub1.ENDANCHOR.$sub1.ENDOFLINK;
				$centerPages .= PAGEACTIVE;
				$centerPages .= PARTOFLINK.$add1.ENDANCHOR.$add1.ENDOFLINK;	
			}
			$limit = 'LIMIT ' . ($pageNumber - 1) * $itemsPerPage . ',' . $itemsPerPage;

			$this->stmt = $this->pdo->query("SELECT * FROM blog_posts ORDER BY postID DESC $limit");

			/* ----- Rendering -----*/
			$paginationDisplay = "";
			
			if ($lastPage != 1) {
				$paginationDisplay .= 'Page {<strong>'.$pageNumber.'</strong> of '.$lastPage . '}';
				if ($pageNumber != 1) {
					$previous = $pageNumber - 1;
					$paginationDisplay .= PARTOFLINK . $previous.'"> Back</a>';
				}
				$paginationDisplay .= '<span class="paginationNumbers">'.$centerPages.'</span>';
			}
			if ($pageNumber != $lastPage) {
				$nextPage = $pageNumber + 1;
				$paginationDisplay .= PARTOFLINK .$nextPage.'"> Next</a>';
			}		
			
			echo $paginationDisplay;
			return $this->stmt;
		}
}
?>