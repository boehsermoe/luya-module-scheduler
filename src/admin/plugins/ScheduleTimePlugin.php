<?php

namespace luya\scheduler\admin\plugins;

use luya\admin\base\TypesInterface;
use luya\admin\ngrest\base\Plugin;
use Yii;

class ScheduleTimePlugin extends Plugin
{
    const UNIT_MINUTE = 'minute';
    const UNIT_HOUR = 'hour';
    const UNIT_DAY = 'day';
    const UNIT_WEEK = 'week';

    public function renderList($id, $ngModel)
    {
        $this->createListTag($ngModel);
    }

    public function renderCreate($id, $ngModel)
    {
        $values = $this->createTag(TypesInterface::TYPE_NUMBER, null, [
            'var' => $this->name . '_value',
            'fieldid' => $id . '-value',
            'model' => $ngModel . '_value',
            'label' => $this->alias,
            'fieldname' => $this->name . '_value',
            'i18n' => ($this->i18n) ? 1 : '',
        ]);

        $units = $this->createTag(TypesInterface::TYPE_SELECT, null, [
            'var' => $this->name . '_unit',
            'fieldid' => $id . '-unit',
            'model' => $ngModel . '_unit',
            'label' => 'Unit',
            'fieldname' => $this->name . '_unit',
            'i18n' => ($this->i18n) ? 1 : '',
            'initValue' => self::UNIT_MINUTE,
            'options' => $this->getServiceName('units'),
        ]);

        return $values . $units;
    }

    public function renderUpdate($id, $ngModel)
    {
        return $this->renderCreate($id, $ngModel);
    }

    public function serviceData($event)
    {
        return [
            'units' => $this->getUnits(),
        ];
    }

    /**
     *
     * {@inheritDoc}
     * @see \luya\admin\ngrest\plugins\Select::getData()
     */
    public function getUnits()
    {
        return [
            [
                'value' => self::UNIT_MINUTE,
                'label' => Yii::t('app', 'Minute'),
            ],
            [
                'value' => self::UNIT_HOUR,
                'label' => Yii::t('app', 'Hour'),
            ],
            [
                'value' => self::UNIT_DAY,
                'label' => Yii::t('app', 'Day'),
            ],
            [
                'value' => self::UNIT_WEEK,
                'label' => Yii::t('app', 'Week'),
            ],
        ];
    }
}
