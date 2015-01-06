<?php
	//  url -> /controller/method/parameter
	define(DS,   DIRECTORY_SEPARATOR);
	define(ROOT, dirname(__FILE__) . DS);
	define(APP, ROOT .'app' . DS);
	define(CORE,  APP. 'core' . DS);
	define(CONTROLLER, APP . 'controllers' . DS);
	define(MODEL, APP . 'models' . DS);
	define(VIEW_PAGES, ROOT . 'public/pages' . DS);
	define(VIEW_TEMPLATES, ROOT . 'public/templates' . DS);
	define(EXT, '.php');




	require_once(CORE . DS . 'App' . EXT);
	require_once(CORE . DS . 'Controller' . EXT);
	require_once(CORE . DS . 'Fetch' . EXT);
	require_once(CORE . DS . 'Model' . EXT);
	require_once(CORE . DS . 'View' . EXT);



	$app = new App;