<?php
	class blogController extends Controller {


		public function __construct()
		{
			echo 'Blog';
		}
		public function index()
		{
			$this->model('blogModel');
			

			$this->viewPages('blog');
		}
	}