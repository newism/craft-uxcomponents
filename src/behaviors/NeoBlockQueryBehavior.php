<?php

namespace  Newism\UxComponents\behaviors;

use benf\neo\elements\Block;
use benf\neo\elements\db\BlockQuery;
use Illuminate\Support\Collection;
use Newism\UxComponents\models\UxComponentInterface;
use site\templates\_components\AbstractComponent;
use yii\base\Behavior;

class NeoBlockQueryBehavior extends Behavior
{
    /**
     * @var BlockQuery
     */
    public $owner;

    /**
     * Renders all blocks in query as components
     * 
     * @return string
     */
    public function renderComponents(): string
    {
        return $this->createComponents()
            ->map(fn(UxComponentInterface $component) => $component->render())
            ->join("\n");
    }
    
    /**
     * Creates a collection of components from blocks query
     * 
     * @return Collection<UxComponentInterface>
     */
    public function createComponents(): Collection
    {
        return $this->owner
            ->collect()
            ->map(fn(Block|NeoBlockBehavior $block) => $block->createComponent());
    }
}