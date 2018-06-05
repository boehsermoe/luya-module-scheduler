<?php

namespace luya\scheduler\frontend;

use Curl\Curl;
use luya\console\interfaces\ImportControllerInterface;
use luya\helpers\Url;
use luya\scheduler\importers\ScheduleJobImporter;
use luya\scheduler\models\Job;
use luya\scheduler\models\JobType;

/**
 * Scheduler Frontend Module.
 *
 * @author Bennet Klarhölter <boehsermoe@me.com>
 * @since 1.0.0
 */
class Module extends \luya\base\Module
{
    public $accessToken = false;
    
    /**
     * Execute all expired jobs
     */
    public function runExpiredJobs()
    {
        $this->debug("Start.");
        
        /** @var Job[] $jobs */
        $jobs = Job::find()->all();
        
        foreach ($jobs as $job) {
            if ($job->needRun()) {
                $this->log("Starting job {$job->fullName}");

                try {
                    $job->run();
                    $this->log("Finished job {$job->fullName}");
                } catch (\Throwable $ex) {
                    $this->error("Job {$job->name} failed: " . $ex->__toString());
                }
            } else {
                $this->debug("Next schedule for job {$job->id} - {$job->name} ({$job->class}) is {$job->next}");
            }
        }
        
        $this->debug("Done.");
    }
    
    public function runExpiredJobsAsync()
    {
        if (\Yii::$app->request->get('token', false) !== false) {
            return;
        }
    
        $this->log('runExpiredJobsAsync');
    
        $url = Url::toRoute(["/{$this->id}/run", 'token' => $this->accessToken], true);

        $curl = new Curl();
        $curl->setOpt(CURLOPT_TIMEOUT_MS, 100);
        $curl->get($url);
    
        $this->log('runExpiredJobsAsync');
    }
    
    public function log($string)
    {
        \Yii::info($string, 'scheduler');
        $this->logs[] = $string;
    }
    
    public function error($string)
    {
        \Yii::error($string, 'scheduler');
        $this->logs[] = $string;
    }
    
    public function debug($string)
    {
        \Yii::debug($string, 'scheduler');
        $this->logs[] = $string;
    }
    
    private $logs = [];
    
    public function getLogs()
    {
        return $this->logs;
    }
}
