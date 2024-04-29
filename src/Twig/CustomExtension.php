<?php

namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class CustomExtension extends AbstractExtension
{ public function getFunctions(): array
    {
        return [
            new TwigFunction('sin', [$this, 'sinFunction']),
            new TwigFunction('cos', [$this, 'cosFunction']),
        ];
    }

    public function sinFunction($angle)
    {
        return sin($angle);
    }

    public function cosFunction($angle)
    {
        return cos($angle);
    }
}
