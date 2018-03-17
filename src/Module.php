<?php

namespace luya\scheduler;

use luya\console\interfaces\ImportControllerInterface;
use luya\scheduler\importers\ScheduleJobImporter;

/**
 * Scheduler Admin Module.
 * 
 * @author Bennet Klarhölter <boehsermoe@me.com>
 * @since 1.0.0
 */
class Module extends \luya\admin\base\Module
{
	public $apis = [
		'api-scheduler-job' => 'luya\scheduler\apis\JobController',
		'api-scheduler-jobtype' => 'luya\scheduler\apis\JobTypeController',
//		'api-scheduler-execute' => 'luya\scheduler\apis\ExecuteJobController',
	];

	public function getMenu()
	{
		return (new \luya\admin\components\AdminMenuBuilder($this))
			->node('Scheduler', 'schedule')
			->itemApi('Jobs', $this->uniqueId . '/job/index', 'label', 'api-scheduler-job')
			->itemApi('JobType', 'scheduler/job-type/index', 'label', 'api-scheduler-jobtype')

//			->itemRoute("History", 'backup/scheduler/history', "poll")
			;
	}

	/**
	 * @inheritdoc
	 */
	public function import(ImportControllerInterface $importer)
	{
		return [
			ScheduleJobImporter::class,
		];
	}
}