<?php

namespace luya\scheduler\admin\apis;

/**
 * Job Type Controller.
 *
 * File has been created with `crud/create` command.
 */
class JobTypeController extends \luya\admin\ngrest\base\Api
{
    /**
     * @var string The path to the model which is the provider for the rules and fields.
     */
    public $modelClass = 'luya\scheduler\models\JobType';
}
