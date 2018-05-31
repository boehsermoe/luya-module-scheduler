<?php

namespace luya\scheduler\models;

use Yii;
use luya\admin\ngrest\base\NgRestModel;

/**
 * Job Type.
 *
 * File has been created with `crud/create` command.
 *
 * @property integer $id
 * @property string $name
 * @property string $class
 */
class JobType extends NgRestModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'scheduler_job_type';
    }

    /**
     * @inheritdoc
     */
    public static function ngRestApiEndpoint()
    {
        return 'api-scheduler-jobtype';
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'class' => Yii::t('app', 'Class'),
            'name' => Yii::t('app', 'Name'),
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['class', 'name'], 'required'],
            [['class'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function genericSearchFields()
    {
        return ['class', 'name'];
    }

    /**
     * @inheritdoc
     */
    public function ngRestAttributeTypes()
    {
        return [
            'class' => 'text',
            'name' => 'text',
        ];
    }

    /**
     * @inheritdoc
     */
    public function ngRestScopes()
    {
        return [
            ['list', ['name', 'class']],
            [['create', 'update'], ['name', 'class']],
            ['delete', false],
        ];
    }
}
