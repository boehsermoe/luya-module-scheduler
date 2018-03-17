<?php
namespace luya\scheduler\controllers;

use luya\admin\base\Controller;

class SchedulerController extends Controller
{
	public function actionIndex()
	{
		return $this->render('index', [
			'data' => [], // Data to assign into the view file `index`.
		]);
	}
}