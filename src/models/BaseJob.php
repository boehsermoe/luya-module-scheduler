<?php

namespace luya\scheduler\models;

use app\modules\backup\aws\ExecuteJobActiveWindow;
use luya\admin\aws\DetailViewActiveWindow;
use luya\console\Controller;
use Psr\Log\LoggerTrait;
use Psr\Log\LogLevel;
use Yii;
use luya\admin\ngrest\base\NgRestModel;
use yii\base\ErrorException;
use yii\db\ActiveQuery;
use yii\helpers\Console;
use yii\helpers\Json;
use yii\helpers\VarDumper;

/**
 * Job.
 * 
 * File has been created with `crud/create` command. 
 *
 * @property integer $id
 * @property string $name
 * @property string $class
 * @property string $schedule
 * @property string $last_run
 * @property string $log
 * @property array $options Json
 *
 * @property string $fullName
 */
abstract class BaseJob extends NgRestModel
{
	use LoggerTrait;

	const EVERY_MINUTE = '1minute';
	const EVERY_HOUR = '1hour';
	const EVERY_DAY = '1day';
	const EVERY_MONTH = '1month';

	protected static $loadAllClasses = false;

	/**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'scheduler_job';
    }

    public static function label()
    {
	    return \yii\helpers\StringHelper::basename(static::class);
    }

	/**
	 * @inheritdoc
	 */
	public static function ngRestApiEndpoint()
	{
		return 'api-scheduler-job-' . self::label();
	}

	public static function instantiate($row)
	{
		$class = $row['class'];
		return new $class();
	}

	public static function ngRestFind()
	{
		$query = parent::ngRestFind();

		if (!static::$loadAllClasses) {
			$query->andWhere([
				'class' => static::class
			]);
		}

		return $query;
	}

	/**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'name' => Yii::t('app', 'Name'),
	        'class' => Yii::t('app', 'Class'),
	        'schedule' => Yii::t('app', 'Schedule'),
            'options' => Yii::t('app', 'Options'),
        ];
    }

	/**
     * @inheritdoc
     */
    public function rules()
    {
	    return [
		    [['schedule'], 'string'],
		    [['name', 'class'], 'string', 'max' => 255],
		    [['name', 'schedule'], 'required'],
		    [['name'], 'unique'],
		    [$this->extraFields(), 'safe'],
	    ];
    }

	/**
     * @inheritdoc
     */
    public function genericSearchFields()
    {
        return ['name', 'class', 'schedule', 'options'];
    }

	/**
     * @inheritdoc
     */
    public function ngRestAttributeTypes()
    {
        return [
            'name' => 'text',
            'class' => ['selectArray', 'data' => [FileJob::class => 'File Backup', DbJob::class => 'Database Backup']],
	        'schedule' => ['selectArray', 'data' => [
		        self::EVERY_MINUTE => Yii::t('app', 'Every minute'),
		        self::EVERY_HOUR => Yii::t('app', 'Every hour'),
		        self::EVERY_DAY => Yii::t('app', 'Every day'),
		        self::EVERY_MONTH => Yii::t('app', 'Every month'),
	        ]]
        ];
    }

	/**
	 * @inheritdoc
	 */
	public function ngRestActiveWindows()
	{
		return [
			[
				'class' => DetailViewActiveWindow::class,
//				'attributes' => $this->attributes()
			],
		];
	}

	/**
     * @inheritdoc
     */
    public function ngRestScopes()
    {
    	$options = array_merge(['name', 'schedule'], $this->extraFields());

        return [
            ['list', $options],
	        [['create'], $options],
	        [['update'], $options],
            ['delete', false],
        ];
    }

	public function __toString()
	{
		return Json::encode($this);
	}

	public function getFullName()
	{
		return "{$this->name} - {$this->id} ({$this->class}";
	}

	public function beforeSave($insert)
	{
		$this->class = static::class;

		$this->options = Json::encode($this->getAttributes($this->extraFields()));

		return parent::beforeSave($insert);
	}

	public function afterFind()
	{
		$this->options = Json::decode($this->options);
		$this->setAttributes($this->options);

		return parent::afterFind();
	}

	public function attributes()
	{
		return array_merge(parent::attributes(), $this->extraFields());
	}

	public function setAttribute($name, $value)
	{
		if (in_array($name, $this->extraFields())) {
//			$this->$name = $value;
		}
		else {
			parent::setAttribute($name, $value);
		}
	}

	abstract public function run();

	public function needRun()
	{
		$nextSchedule = $this->getNextSchedule();

		return $nextSchedule <= time();
	}

	private $nextSchedule = null;

	/**
	 * @return int Timestamp
	 */
	public function getNextSchedule()
	{
		if ($this->nextSchedule == null) {
			if (!is_null($this->last_run)) {
				$schedule = $this->schedule;
				$lastRun = strtotime($this->last_run);
				$nextSchedule = strtotime("+" . $schedule, $lastRun);
				$this->nextSchedule = ceil($nextSchedule/ 60) * 60;
			}
			else {
				$this->nextSchedule = time();
			}
		}

		return $this->nextSchedule;
	}

	public function log($level, $message, array $context = array())
	{
		$format = [];
		switch ($level) {
			case LogLevel::EMERGENCY:
			case LogLevel::ALERT:
			case LogLevel::CRITICAL:
			case LogLevel::ERROR:
				$format[] = Console::FG_RED;
				break;
			case LogLevel::WARNING:
			case LogLevel::NOTICE:
				$format[] = Console::FG_YELLOW;
				break;
			case LogLevel::INFO:
				$format[] = Console::FG_BLUE;
				break;
			case LogLevel::DEBUG:
				break;
		}

		$datetime = date("Y-m-d H:i:s");
		$formattedMessage = "\r{$datetime} [{$this->name}] [$level]\t" . Console::ansiFormat($message, $format)  . "\n";

		/** @var BaseJob $job */
		$job = $this;
		$job->log .= $formattedMessage;
		echo $formattedMessage;
	}
}