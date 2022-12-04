<?php

namespace Newism\UxComponents\twig;

use Newism\UxComponents\models\UxComponentInterface;
use Newism\UxComponents\Plugin;
use Newism\UxComponents\services\UxComponentRenderer;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class TwigExtension extends AbstractExtension
{
    public function getFunctions(): array
    {
        return [
            new TwigFunction('uxcomponent', [$this, 'createAndRender'], ['is_safe' => ['all']]),
        ];
    }

    public function createAndRender(string $name, array $config = []): UxComponentInterface
    {
        $plugin = Plugin::getInstance();
        /** @var UxComponentRenderer $componentRenderer */
        $componentRenderer = $plugin->get('uxComponentRenderer');
        return $componentRenderer->createUxComponent($name, $config);
    }
}