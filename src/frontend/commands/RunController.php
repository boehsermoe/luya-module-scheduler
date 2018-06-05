<?php

namespace luya\scheduler\frontend\commands;

use luya\scheduler\frontend\Module;
use luya\scheduler\models\BaseJob;
use luya\scheduler\models\Job;

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
        $this->module->runExpiredJobs();
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
        $job = BaseJob::find()->where([
            'or',
            ['id' => $jobId],
            ['name' => $jobId]
        ])->one();

        $job->log = null;
        $job->info("Running job {$job->class}");
        $job->run();
        $job->info('Done.');
    }
}
