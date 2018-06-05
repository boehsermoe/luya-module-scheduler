<?php

namespace luya\scheduler\admin\schedules;

use luya\scheduler\models\BaseJob;

class CommandJob extends BaseJob
{
    public $command;

    public function rules()
    {
        return array_merge(parent::rules(), [
            [['command'], 'required']
        ]);
    }


    public function extraFields()
    {
        return [
            'command'
        ];
    }

    public function ngrestExtraAttributeTypes()
    {
        return [
            'command' => ['text', 'placeholder' => './vendor/bin/luya {command}'],
        ];
    }

    public function run()
    {
        \Yii::$app->runAction($this->command);
    }
}
