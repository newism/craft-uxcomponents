<?php

namespace Newism\UxComponents\twig;

use Newism\UxComponents\Plugin;
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

    public function createAndRender(string $name, array $config = []): string
    {
        return Plugin::getInstance()->uxComponentRenderer->createAndRender($name, $config);
    }
}