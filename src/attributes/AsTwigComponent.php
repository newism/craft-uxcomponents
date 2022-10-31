<?php

namespace  Newism\UxComponents\attributes;

use Attribute;

#[Attribute(Attribute::TARGET_CLASS)] class AsTwigComponent
{
    public function __construct(
        public string $name,
    ) {
    }

}