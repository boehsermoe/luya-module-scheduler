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
class SchedulerController extends \luya\console\Command
{
	public function actionIndex()
	{
		/** @var Job[] $jobs */
		$jobs = Job::find()->all();

		foreach ($jobs as $job) {
			if ($job->needRun()) {

				$this->outputSuccess("Starting job {$job->fullName}");

				try {
					$this->module->createBackup([$job]);
					$this->outputSuccess("Finished job {$job->fullName}");
				}
				catch (\Throwable $ex) {
					$this->outputError("Job {$job->name} failed: " . $ex->getMessage());
				}

			}
			else {
				$this->output("Next schedule for job {$job->id} - {$job->name} ({$job->class}) is {$job->next}");
			}
		}
	}

	public function actionRun($jobId, $exportDir = null)
	{
		$job = Job::findOne($jobId);

		$this->module->createBackup([$job], $exportDir);
	}

	public function actionList()
	{
		/** @var Job[] $jobs */
		$jobs = Job::find()->all();

		foreach ($jobs as $job) {
			$options = Json::encode($job->options);
			$this->output("{$job->id} - {$job->name} ({$job->class}): {$options}");
		}
	}
}