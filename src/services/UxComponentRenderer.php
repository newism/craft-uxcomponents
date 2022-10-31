<?php

namespace Newism\UxComponents\services;

use craft\base\ComponentInterface;
use Newism\UxComponents\attributes\AsTwigComponent;
use Newism\UxComponents\events\RegisterUxComponentTypesEvent;
use Newism\UxComponents\models\UxComponentInterface;
use yii\base\Component;
use yii\base\Exception;

class UxComponentRenderer extends Component
{
    const EVENT_REGISTER_UX_COMPONENT_TYPES = 'registerUxComponents';

    /** @var string[] Array of ux component classes */
    public array $types = [];

    /** @var string[] Array of ux component classes keyed by name */
    private ?array $typesKeyedByName = null;

    public function init(): void
    {
        $event = new RegisterUxComponentTypesEvent([
            'types' => $this->types
        ]);
        $this->trigger(self::EVENT_REGISTER_UX_COMPONENT_TYPES, $event);
        $this->types = $event->types;

        $names = array_map(function (string $class) {
            $reflectionClass = new \ReflectionClass($class);
            $attributes = $reflectionClass->getAttributes(AsTwigComponent::class);
            if (empty($attributes)) {
                throw new Exception("$class is missing `#[AsTwigComponent()]` attribute");
            }
            /** @var AsTwigComponent $attribute */
            $attribute = $attributes[0]->newInstance();
            return $attribute->name;
        }, $this->types);

        $this->typesKeyedByName = array_combine($names, $this->types);
    }

    public function createUxComponent(string $name, array $config = []): UxComponentInterface
    {
        try {
            $config['class'] = $this->typesKeyedByName[$name];
        } catch (\Exception $exception) {
            throw new \InvalidArgumentException(sprintf('Unknown component "%s". The registered components are: %s', $name, implode(', ', array_keys($this->typesKeyedByName))));
        }
        
        return \Craft::createObject($config);
    }

    private function renderUxComponent(UxComponentInterface $uxComponent): string
    {
        return $uxComponent->render();
    }

    public function createAndRender(string $name, array $props = []): string
    {
        return $this->renderUxComponent($this->createUxComponent($name, $props));
    }
    
    
}