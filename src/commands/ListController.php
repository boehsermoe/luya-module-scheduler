<?php

namespace luya\scheduler\commands;

use luya\scheduler\models\Job;
use luya\backup\Module;
use luya\helpers\Json;
use yii\helpers\Console;

/**
 * Class SchedulerController
 *
 * @property Module $module
 * @author Bennet Klarhoelter <boehsermoe@me.com>
 */
class ListController extends \luya\console\Command
{
	/**
	 * Listing all available jobs
	 */
	public function actionIndex()
	{
		$this->outputInfo('Available jobs:');

		/** @var Job[] $jobs */
		$jobs = Job::find()->all();

		foreach ($jobs as $job) {
			$options = Json::encode($job->options);
			$jobName = $this->ansiFormat($job->name, Console::FG_GREEN);
			$this->output("{$job->id}: {$jobName} ({$job->class})");
		}
	}
}