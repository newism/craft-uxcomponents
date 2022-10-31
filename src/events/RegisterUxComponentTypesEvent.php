<?php

namespace  Newism\UxComponents\events;

use yii\base\Event;

class RegisterUxComponentTypesEvent extends Event
{
    /**
     * @var string[] List of registered ux component classes.
     */
    public array $types = [];
}