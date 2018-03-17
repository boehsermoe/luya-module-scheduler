<?php

namespace luya\scheduler\importers;

use Yii;
use luya\console\Importer;
use luya\scheduler\models\JobType;

/**
 * Schedule Job Importer.
 *
 * @author Bennet Klarhoelter <boehsermoe@me.com>
 */
class ScheduleJobImporter extends Importer
{
	public function run()
	{
		$this->addLog('ScheduleJobImporter have been started');

		$allJobTypes = JobType::find()->all();
		$exists = [];

		foreach ($this->getImporter()->getDirectoryFiles('schedules') as $file) {
			$exists[] = $this->saveJobType($file['ns']);
		}

		foreach (Yii::$app->packageInstaller->configs as $config) {
			foreach ($config->jobTypes as $jobType) {
				if (is_file($jobType)) {
					$exists[] = $this->saveJobTypeByPath($jobType);
				} elseif (is_dir($jobType)) {
					foreach (FileHelper::findFiles($jobType) as $jobTypeItem) {
						$exists[] = $this->saveJobTypeByPath($jobTypeItem);
					}
				}
			}
		}

		foreach ($allJobTypes as $jobType) {
			if (!in_array($jobType->id, $exists)) {
				$this->addLog('- Deleted jobType ID '.$jobType->id.' from database.');
				$jobType->delete();
			}
		}
	}

	protected function saveJobType($fullClassName)
	{
		// ensure all classes start with trailing slash class name definition like `\foo\bar\Class`
		$fullClassName = '\\'  . ltrim($fullClassName, '\\');
		$model = JobType::find()->where(['class' => $fullClassName])->one();

		if (!$model) {
			$model = new JobType();
			$model->class = $fullClassName;
			$model->save();
			$this->addLog("+ Added jobType '{$fullClassName}' to database.");
		}

		return $model->id;
	}

	/**
	 *
	 * @param unknown $path
	 * @return number|boolean
	 */
	protected function saveJobTypeByPath($path)
	{
		$info = FileHelper::classInfo($path);

		if ($info) {
			$className = $info['namespace'] . '\\' . $info['class'];

			return $this->saveJobType($className);
		}

		return false;
	}

	/**
	 *
	 * @param unknown $className
	 * @return object|mixed
	 */
	protected function createJobTypeObject($className)
	{
		return Yii::createObject(['class' => $className]);
	}
}