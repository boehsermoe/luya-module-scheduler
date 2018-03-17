<?php

use yii\db\Migration;

/**
 * Class m180317_170227_ScheduleJobTypes
 */
class m180317_170227_ScheduleJobType extends Migration
{
	const TABLENAME = 'scheduler_job_type';

	/**
	 * @inheritdoc
	 */
	public function safeUp()
	{
		$this->createTable(self::TABLENAME, [
			'id' => $this->primaryKey(),
			'name' => $this->string()->unique()->notNull(),
			'class' => $this->string()->notNull(),
		]);
	}

	/**
	 * @inheritdoc
	 */
	public function safeDown()
	{
		$this->dropTable(self::TABLENAME);

		return true;
	}
}
