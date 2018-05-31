<?php

namespace luya\scheduler\commands;

use luya\scheduler\models\BaseJob;
use luya\backup\Module;
use luya\helpers\Json;
use luya\scheduler\models\Job;
use yii\helpers\Console;

/**
 * Class SchedulerController
 *
 * @property Module $module
 * @author Bennet Klarhoelter <boehsermoe@me.com>
 */
class RunController extends \luya\console\Command
{
    /**
     * Execute all expired jobs
     */
    public function actionIndex()
    {
        /** @var Job[] $jobs */
        $jobs = Job::find()->all();

        foreach ($jobs as $job) {
            if ($job->needRun()) {
                $this->outputSuccess("Starting job {$job->fullName}");

                try {
                    $job->run();
                    $this->outputSuccess("Finished job {$job->fullName}");
                } catch (\Throwable $ex) {
                    $this->outputError("Job {$job->name} failed: " . $ex->__toString());
                }
            } else {
                $this->output("Next schedule for job {$job->id} - {$job->name} ({$job->class}) is {$job->next}");
            }
        }
    }

    /**
     * Execute given job.
     *
     * @param int|string $jobId Id oder name of job
     */
    public function actionNow($jobId = null)
    {
        if ($jobId === null) {
            $this->outputError('Require a jobId.');
            \Yii::$app->runAction($this->module->id . '/list');
            return;
        }

        /** @var BaseJob $job */
        $job = BaseJob::find()->where(['or',
            ['id' => $jobId],
            ['name' => $jobId]
        ])->one();

        $job->log = null;
        $job->info("Running job {$job->class}");
        $job->run();
        $job->info('Done.');
    }
}
