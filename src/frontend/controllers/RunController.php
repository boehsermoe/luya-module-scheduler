<?php

namespace luya\scheduler\frontend\controllers;

use luya\scheduler\frontend\Module;
use luya\rest\Controller;
use yii\web\Response;
use yii\web\UnauthorizedHttpException;

/**
 * Class RunController
 *
 * @author Bennet Klarhoelter <boehsermoe@me.com>
 *
 * @property Module $module
 */
class RunController extends Controller
{
    /**
     * Execute all expired jobs
     *
     * @param $token
     * @throws UnauthorizedHttpException
     */
    public function actionIndex($token)
    {
        \Yii::$app->response->format = Response::FORMAT_JSON;
        
        if ($this->module->accessToken !== $token) {
            throw new UnauthorizedHttpException();
        }
        
        $this->module->runExpiredJobs();
        
        return $this->module->getLogs();
    }
}