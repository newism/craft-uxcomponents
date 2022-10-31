<?php

namespace  Newism\UxComponents\behaviors;

use benf\neo\elements\Block;
use Craft;
use Newism\UxComponents\models\SettingsModel;
use Newism\UxComponents\models\UxComponentInterface;
use Newism\UxComponents\Plugin;
use yii\base\Behavior;

class NeoBlockBehavior extends Behavior
{
    /** @var Block */
    public $owner;

    public function createComponent(): ?UxComponentInterface
    {
        $block = $this->owner;
        $field = Craft::$app->getFields()->getFieldById($block->fieldId);
        
        /** @var SettingsModel $pluginSettings */
        $pluginSettings = Plugin::getInstance()->getSettings();
        $transformer = $pluginSettings->blockToUxComponentTransformer;

        if(!is_callable($transformer)) {
            throw new \Exception('blockToUxComponentTransformer must be a callable defined in plugin settings');
        }
        
        return call_user_func($transformer, $block, $field);
    }
}