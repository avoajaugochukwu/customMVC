<?php
	class blogModel extends Model {


		public function __construct()
		{
			parent::__construct();
			echo 'blogModel<br>';
			
			// $connect = new Fetch;
			$result = $this->pdo->recentPost('blog_posts', 1, DESC, 1, 2);

			foreach ($result as $row) {
				$recents[] = array(
											'postTitle' => $row['postTitle'],
											'postUrl'	 => $row['postUrl']
					);
			}
			var_export($result);
			// return $recents;
		}

	}