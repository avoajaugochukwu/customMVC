<?php

	class App {

		protected $home;

		protected $controller = 'homeController';

		protected $method = 'index';


		public function __construct() {
			$url = $_GET['url'];

			echo 'URL #>>> ';
			echo $url;

			$url = explode('/', rtrim($url, '/'));



			// unset($url[0]);
			// echo '<pre>';
			// print_r($url);
			// echo '</pre>';
			echo '<hr><br>';

			if (empty($url[0])) {
				$this->controller = 'homeController';
				require CONTROLLER . $this->controller . EXT;
				// require 'public/pages/home.php'; // home page things
			}
			// $this->controller = new $this->controller;


			if ($url[0] != '') {
				$this->controller = $url[0] . 'Controller';
				if (is_readable(CONTROLLER . $this->controller . EXT)) {
					require CONTROLLER . $this->controller . EXT;
					// $this->controller = new $this->controller;  /** call controller class */
				}
			}

			$this->controller = new $this->controller;

			echo '<hr><br>';

			$this->method = 'index';
			if ($url[1] != '') {
					if (method_exists($this->controller, $url[1])) {
					unset($url[0]);
					$this->method = $url[1];
					unset($url[1]);
				}
			}

			call_user_func_array([$this->controller, $this->method], $url);

		}



	}