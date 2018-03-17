<?php

namespace luya\scheduler\apis;
use luya\scheduler\models\JobType;

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

	public function init()
	{
		$jobTypeName = end(explode('-', $this->id));
		$jobType = JobType::findOne(['name' => $jobTypeName]);

		$this->modelClass = $jobType->class;

		parent::init();
	}

	/**
	 * @inheritdoc
	 */
	public function actions()
	{
		$actions = parent::actions();

		return $actions;
	}
}