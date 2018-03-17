<?php

namespace luya\scheduler;

/**
 * Scheduler Admin Module.
 *
 * File has been created with `module/create` command. 
 * 
 * @author
 * @since 1.0.0
 */
class Module extends \luya\admin\base\Module
{
	public function getMenu()
	{
		return (new \luya\admin\components\AdminMenuBuilder($this))
			->node('Scheduler', 'schedule')
			->itemRoute("Current", 'backup/scheduler/index', "poll")
//			->itemRoute("History", 'backup/scheduler/history', "poll")
			;
	}
}