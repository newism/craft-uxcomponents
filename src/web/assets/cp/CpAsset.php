<?php

namespace  Newism\UxComponents\web\assets\cp;

use craft\web\AssetBundle;
use craft\web\assets\cp\CpAsset as CraftCpAsset;
use craft\web\assets\prismjs\PrismJsAsset;

class CpAsset extends AssetBundle
{
    public $depends = [
        CraftCpAsset::class,
        PrismJsAsset::class,
    ];

    public $sourcePath = __DIR__ . '/dist';
}