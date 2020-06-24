<?php

class Route{

	public static function start()
	{
		//контроллер, действие и ID действия по умолчанию

		$controller_name = 'Main';
		$action_name = 'index';
		$action_id = NULL;
		
		$routes = explode('/', $_SERVER['REQUEST_URI'], 4);

		//имя контроллера
		if (!empty($routes[1]))
		{	
			$controller_name = preg_replace('/[^a-zA-Z0-9]/', '', $routes[1]);
		}
		
		//имя действия
		if (!empty($routes[2]))
		{
			$action_name = preg_replace('/[^a-zA-Z0-9]/', '', $routes[2]);
		}

		//ID действия
        if (!empty($routes[3]))
        {
            $action_name = preg_replace('/[^a-zA-Z0-9]/', '', $routes[3]);
        }

		//добавляем префиксы
		$model_name = 'Model_'.$controller_name;
		$controller_name = 'Controller_'.$controller_name;
		$action_name = 'action_'.$action_name;

		//подцепляем файл с классом модели, если есть

		$model_file = strtolower($model_name).'.php';
		$model_path = "application/models/".$model_file;
		if(file_exists($model_path)){
			include_once  "application/models/".$model_file;
		}

		// подцепляем файл с классом контроллера
		$controller_file = strtolower($controller_name).'.php';
		$controller_path = "application/controllers/".$controller_file;
		if(file_exists($controller_path)){
			include_once  "application/controllers/".$controller_file;
		}
		else{
			/*
			правильно было бы кинуть здесь исключение,
			но для упрощения сразу сделаем редирект на страницу 404
			*/
			Route::ErrorPage404();
		}
		
		// создаем контроллер
		$controller = new $controller_name;
		$action = $action_name;


		if(method_exists($controller, $action)){
			// вызываем действие контроллера
			$controller->$action($action_id);
		}
		else{
			// здесь также разумнее было бы кинуть исключение
			Route::ErrorPage404();
		}
	
	}
	
	public static function ErrorPage404(){
        $host = 'http://'.$_SERVER['HTTP_HOST'].'/';
        header('HTTP/1.1 404 Not Found');
		header("Status: 404 Not Found");
		header('Location:'.$host.'404');
    }
}