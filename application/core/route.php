<?php

class Route{

	public static function start(){
		// контроллер и действие по умолчанию
		$controller_name = 'Main';
		$action_name = 'index';
		
		$routes = explode('/', $_SERVER['REQUEST_URI']);

		// получаем имя контроллера
		//В элементе глобального массива $_SERVER['REQUEST_URI'] 
		//содержится полный адрес по которому обратился пользователь.
		//Например: example.ru/contacts/feedback

		//С помощью функции explode производится разделение адреса на составлющие. 
		//В результате мы получаем имя контроллера, для приведенного примера, 
		//это контроллер 'contacts' и имя действия, в нашем случае — 'feedback'.

		if ( !empty($routes[1]) )
		{	
			$controller_name = $routes[1];
		}
		
		// получаем имя экшена
		if ( !empty($routes[2]) )
		{
			$action_name = $routes[2];
		}

		$action_name = htmlspecialchars($action_name);
		$controller_name = htmlspecialchars($controller_name);

		// добавляем префиксы
		$model_name = strtolower('Model_'.$controller_name);
		$controller_name = strtolower('Controller_'.$controller_name);
		$action_name = 'action_'.$action_name;

		// подцепляем файл с классом модели (файла модели может и не быть)

		$model_file = $model_name . '.php';
		$model_path = "application/models/" . $model_file;
		if(file_exists($model_path))
		{
			include "application/models/" . $model_file;
		}

		// подцепляем файл с классом контроллера
		$controller_file = $controller_name . '.php';
		$controller_path = "application/controllers/" . $controller_file;
		if(file_exists($controller_path))
		{
			include "application/controllers/" . $controller_file;
		}
		else
		{
			/*
			правильно было бы кинуть здесь исключение,
			но для упрощения сразу сделаем редирект на страницу 404
			*/
			$Route = new Route();
			$Route->ErrorPage404();
		}
		
		// создаем контроллер
		$controller = new $controller_name;
		$action = $action_name;
		
		if(method_exists($controller, $action))
		{
			// вызываем действие контроллера
			$controller->$action();
		}
		else
		{
			// здесь также разумнее было бы кинуть исключение
			$Route = new Route();
			$Route->ErrorPage404();
		}
	
	}
	
	function ErrorPage404()
	{
        $host = 'http://'.$_SERVER['HTTP_HOST'].'/';
        header('HTTP/1.1 404 Not Found');
		header("Status: 404 Not Found");
		header('Location:'.$host.'404');
    }
}