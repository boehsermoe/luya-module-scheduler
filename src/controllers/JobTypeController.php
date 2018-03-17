<?php

namespace luya\scheduler\controllers;

/**
 * Job Type Controller.
 * 
 * File has been created with `crud/create` command. 
 */
class JobTypeController extends \luya\admin\ngrest\base\Controller
{
    /**
     * @var string The path to the model which is the provider for the rules and fields.
     */
    public $modelClass = 'luya\scheduler\models\JobType';
}