<?php

namespace Newism\UxComponents\models;

use craft\config\BaseConfig;

class SettingsModel extends BaseConfig
{
    /** @var array Array of component classes */
    public array $types = [];
    
    public $blockToUxComponentTransformer = null;
}