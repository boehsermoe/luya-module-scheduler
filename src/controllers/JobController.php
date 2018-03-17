<?php
namespace luya\scheduler\controllers;

use Yii;

/**
 * Job Controller.
 *
 * File has been created with `crud/create` command.
 */
class JobController extends \luya\admin\ngrest\base\Controller
{
	/**
	 * @var string The path to the model which is the provider for the rules and fields.
	 */
	public $modelClass = 'luya\scheduler\models\Job';

	public function actionIndex($jobTypeClass, $inline = false, $relation = false, $arrayIndex = false, $modelClass = false, $modelSelection = false)
	{
		$this->modelClass = $jobTypeClass;

		return parent::actionIndex($inline, $relation, $arrayIndex, $modelClass, $modelSelection);
	}
}