<?php

namespace Framework\Templating;

use Framework\Response;

class View extends Response
{
    public function __construct(
        private string $view = '',
        private array $data = [],
        private int $statusCode = 200,
    ) {
        parent::__construct('', $this->statusCode);
    }

    public function getView(): string
    {
        return $this->view;
    }

    public function getData(): array
    {
        return $this->data;
    }
}