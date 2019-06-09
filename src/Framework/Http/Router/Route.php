<?php
namespace Framework\Http\Router;

class Route
{
    public $name;
    public $handler;
    public $pattern;
    public $tokens;
    public $methods;

    public function __construct($name, $pattern, $handler, array $methods = [], array $tokens = [])
    {
        $this->name = $name;
        $this->handler = $handler;
        $this->pattern = $pattern;
        $this->methods = $methods;
        $this->tokens = $tokens;
    }

}