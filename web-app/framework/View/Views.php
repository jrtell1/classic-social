<?php

namespace Framework\View;

use Exception;

class Views
{
    public function __construct() {}

    private array $templates = [];

    public function render(string $template, array $data = []): string
    {
//        $path = $this->path . '/' . $template . '.php';
//
//        if (!file_exists($path)) {
//            throw new Exception("Template $template does not exist");
//        }

//        // Todo: Register and load views from filesystem on server start
//        $templateFunc = require $path;

        print_r($this->templates);

        return $this->templates[$template](...$data);
    }

    public function loadTemplates(): void
    {
        foreach (glob(__DIR__ . '/../../app/views/*.php') as $viewFile)
        {
            $templateName = str_replace('.php', '', basename($viewFile));
            print_r($templateName);
            $this->templates[$templateName] = require $viewFile;
        }
    }
}
