<?php

namespace luya\scheduler\admin;

use luya\console\interfaces\ImportControllerInterface;
use luya\scheduler\admin\importers\ScheduleJobImporter;
use luya\scheduler\models\JobType;

/**
 * Scheduler Admin Module.
 *
 * @author Bennet Klarhölter <boehsermoe@me.com>
 * @since 1.0.0
 */
class Module extends \luya\admin\base\Module
{
    public $apis = [
        'api-scheduler-job-job' => 'luya\scheduler\admin\apis\JobController',
        'api-scheduler-jobtype' => 'luya\scheduler\admin\apis\JobTypeController',
//		'api-scheduler-execute' => 'luya\scheduler\admin\apis\ExecuteJobController',
    ];

    /** @var JobType[] $jobTypes */
    private $jobTypes = [];

    public function init()
    {
        parent::init();

//        if (\Yii::$app->db->getTableSchema(JobType::tableName())) {
//            /** @var JobType[] $jobTypes */
//            $this->jobTypes = JobType::find()->all();
//            foreach ($this->jobTypes as $jobType) {
//                $this->apis['api-scheduler-job-' . strtolower($jobType->name)] = 'luya\scheduler\admin\apis\JobController';
//            }
//        }
    }

    public function getMenu()
    {
        $adminMenuBuilder = (new \luya\admin\components\AdminMenuBuilder($this))
            ->node('Scheduler', 'schedule')
            ->group('Jobs')
            ->itemApi('All jobs', $this->uniqueId . '/job/index', 'label', 'api-scheduler-job-job');

        
        foreach ($this->jobTypes as $jobType) {
            $adminMenuBuilder->itemApi(
                $jobType->name,
                $this->uniqueId . '/job/index?jobTypeClass=' . $jobType->class, 'label',
                'api-scheduler-job-' . strtolower($jobType->name)
            );
        }


        //			->itemRoute("History", 'backup/scheduler/history', "poll")

        return $adminMenuBuilder;
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
