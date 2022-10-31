<?php

namespace Newism\UxComponents\models;

use craft\base\Model;
use Illuminate\Support\Collection;
use yii\base\InvalidArgumentException;

abstract class AbstractUxComponent extends Model implements UxComponentInterface
{
    public array $containerAttrs = [];

    /** @var Collection<UxComponentInterface>|null */
    public ?Collection $children = null;

    public function __toString(): string
    {
        return $this->render();
    }

    public function validate($attributeNames = null, $clearErrors = true): bool
    {
        $valid = parent::validate($attributeNames, $clearErrors);

        if (!$valid) {
            throw new InvalidArgumentException(sprintf("%s has errors: \n\n%s", $this::class, join("\n", $this->getErrorSummary(true))));
        }
        
        return $valid;
    }

    public function renderChildren(): string
    {
        if (!$this->children) {
            return '';
        }

        return $this->children
            ->map(fn(UxComponentInterface $child) => $child->render())
            ->join("\n");
    }
}