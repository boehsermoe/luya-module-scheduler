<?php

use yii\db\Migration;

/**
 * Class m180123_070338_Job
 */
class m180123_070338_SchedulerJob extends Migration
{
	const TABLENAME = 'scheduler_job';

	/**
     * @inheritdoc
     */
    public function safeUp()
    {
    	$this->createTable(self::TABLENAME, [
    		'id' => $this->primaryKey(),
		    'name' => $this->string()->unique()->notNull(),
		    'class' => $this->string()->notNull(),
		    'schedule' => $this->string()->notNull(),
		    'last_run' => $this->timestamp(),
		    'last_run' => $this->text(),
		    'options' => $this->text(),
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
