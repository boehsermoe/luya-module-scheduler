<?php

namespace luya\scheduler\commands;

use luya\scheduler\models\Job;
use luya\backup\Module;
use luya\helpers\Json;

/**
 * Class SchedulerController
 *
 * @property Module $module
 * @author Bennet Klarhoelter <boehsermoe@me.com>
 */
class ListController extends \luya\console\Command
{
	public function actionIndex()
	{
		/** @var Job[] $jobs */
		$jobs = Job::find()->all();

		foreach ($jobs as $job) {
			$options = Json::encode($job->options);
			$this->output("{$job->id} - {$job->name} ({$job->class}): {$options}");
		}
	}
}