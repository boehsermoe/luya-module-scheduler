<?php

namespace luya\scheduler\schedules;

use luya\scheduler\models\BaseJob;

class CommandJob extends BaseJob
{
	/**
	 * @inheritdoc
	 */
	public static function ngRestApiEndpoint()
	{
		return 'api-backup-file-job';
	}

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
			'command' => 'text',
		];
	}

	public function run()
	{
		echo "Not yet implemented.";
	}
}