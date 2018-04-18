<?php
namespace luya\scheduler\controllers;

use luya\scheduler\models\Job;
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

    public function actionIndex($inline = false, $relation = false, $arrayIndex = false, $modelClass = false, $modelSelection = false)
    {
        $this->modelClass = Yii::$app->request->get('jobTypeClass', Job::class);

        return parent::actionIndex($inline, $relation, $arrayIndex, $modelClass, $modelSelection);
    }
}
