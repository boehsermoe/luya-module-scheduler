<?php

namespace luya\scheduler\apis;

/**
 * Job Controller.
 * 
 * File has been created with `crud/create` command. 
 */
class JobController extends \luya\admin\ngrest\base\Api
{
    /**
     * @var string The path to the model which is the provider for the rules and fields.
     */
    public $modelClass = 'luya\scheduler\models\Job';

	/**
	 * @inheritdoc
	 */
	public function actions()
	{
		$actions = parent::actions();
		unset($actions['create']);
		unset($actions['update']);

		return $actions;
	}
}