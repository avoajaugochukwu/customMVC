<?php
	class Controller {

		public function model($model)
		{
			require_once MODEL . $model . EXT;
			return new $model();
		}

		public function viewPages($view, $data = [])
		{
			require_once VIEW_PAGES . $view . EXT;
		}

		public function viewTemplates($view, $data = [])
		{
			require_once VIEW_TEMPLATES . $view . EXT;
		}
	}