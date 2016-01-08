<?php

namespace App;

use Nette;
use Nette\Application\Routers\RouteList;
use Nette\Application\Routers\Route;


class RouterFactory
{

	/**
	 * @return Nette\Application\IRouter
	 */
	public static function createRouter()
	{
		$router = new RouteList;
		$router[] = new Route('Course/<action>/<raceid>/<courseid>', 'Course:list');
		$router[] = new Route('Category/<action>/<raceid>/<catid>', 'Category:list');
		$router[] = new Route('<presenter>/<action>[/<raceid>]', 'Main:default');
		return $router;
	}

}
