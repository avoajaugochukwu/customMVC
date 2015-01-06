<?php
	class HomeController extends Controller {

		public function index()
		{
			$this->model('homeModel');
			

			$this->viewPages('home');
		}
	}