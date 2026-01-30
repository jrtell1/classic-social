<?php

namespace Framework\View;

use Framework\Response;

class View extends Response
{
    public function __construct(
        private string $view = '',
        private array $data = [],
        private int $statusCode = 200,
    ) {
        parent::__construct($content, $this->statusCode);
    }
}