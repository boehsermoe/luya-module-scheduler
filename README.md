# Scheduler Module

## Installation

`composer require boehsermoe/luya-module-scheduler:"~1.0.0"`

In order to add the modules to your project go into the modules section of your config:

```php
return [
    'modules' => [
        // ...
        'scheduler' => [
            'class' => 'luya\scheduler\Module',
        ],
        // ...
    ],
];
```
```shell
docker-compose exec luya_php luya migrate
docker-compose exec luya_php luya import
```

![screen](scheduler-screen.png)

## Start jobs

Start all expired jobs manual:
```shell
./luya scheduler/run
```

Execute one job:
```shell
./luya scheduler/run/now
```

## Cron

Start all expired jobs every minute via cron:
```shell
* * * * * ./luya scheduler/run
```

## CommandJob: Execute commands

For example you can flush the cache every hour by add a new CommandJob (Scheduler -> CommandJobs -> Add) and insert "cache/flush-all" as command.

![commandjob-screen](commandjob-screen.png)


## Custom Jobs

You can also write your own jobs classes in the path "{appBasePath}/schedulers" or "{moduleBasePath}/schedulers". Every job have to inherite from BaseJob.


Example for a job with a text field as option.

```php
class FileJob extends BaseJob
{
    public $text;

    public function rules()
    {
        return array_merge(parent::rules(), [
            [['text'], 'required']
        ]);
    }


    public function extraFields()
    {
        return [
            'text'
        ];
    }

    public function ngrestExtraAttributeTypes()
    {
        return [
            'text' => 'text',
        ];
    }

    public function run()
    {
        // Do your job
	//
	// echo $this->text;
    }
}
```

### More examples
For file backups: https://github.com/boehsermoe/luya-module-backup/blob/master/src/schedules/FileBackupJob.php
For database backups: https://github.com/boehsermoe/luya-module-backup/blob/master/src/schedules/DatanbaseBackupJob.php
