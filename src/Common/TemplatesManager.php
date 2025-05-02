<?php

namespace InfyOm\Generator\Common;

class TemplatesManager
{
    protected $useLocale = false;

    public function isUsingLocale(): bool
    {
        return $this->useLocale;
    }

    public function setUseLocale(bool $useLocale): void
    {
        $this->useLocale = $useLocale;
    }
}
