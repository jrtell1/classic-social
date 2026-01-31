<?php

namespace Framework\Templating;

class Templater
{
    public function __construct() {}

    private array $templates = [];

    public function render(string $template, array $data = []): string
    {
        return $this->templates[$template](...$data);
    }

    public function loadTemplates(): void
    {
        foreach (glob(__DIR__ . '/../../app/views/*.php') as $viewFile)
        {
            $templateName = str_replace('.php', '', basename($viewFile));
            $this->templates[$templateName] = require $viewFile;
        }
    }
}
