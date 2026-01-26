<?php

namespace Framework;

class Container
{
    private array $objectCache = [];

    public function setArg(string $class, string $argument, mixed $value) {
        if (!isset($this->primitiveArguments[$class])) {
            $this->primitiveArguments[$class] = [];
        }
        $this->primitiveArguments[$class][$argument] = $value;
    }

    public function build(string $class)
    {
        // If the class does not exist, throw
        if (!class_exists($class)) {
            throw new \RuntimeException("Class $class does not exist.");
        }

        // Check if an instance of this class already exists in the cache
        if (isset($this->objectCache[$class])) {
            return $this->objectCache[$class];
        }

        $ref = new \ReflectionClass($class);
        $constructor = $ref->getConstructor();

        // No constructor, just instantiate
        if (!$constructor || $constructor->getNumberOfParameters() === 0) {
            return new $class();
        }

        $params = $constructor->getParameters();
        $dependencies = [];

        foreach ($params as $param) {
            $type = $param->getType();

            if (!$type || $type->isBuiltin()) {
                $argName = $param->getName();
                if (
                    !isset($this->primitiveArguments[$class])
                    || !array_key_exists($argName, $this->primitiveArguments[$class])
                ) {
                    throw new \RuntimeException("No primitive argument value set for '\${$argName}' in class {$class}.");
                }
                $dependencies[] = $this->primitiveArguments[$class][$argName];
                continue;
            }

            $depClass = $type->getName();
            $dependencies[] = $this->build($depClass); // Recursively resolve
        }

        $object = $ref->newInstanceArgs($dependencies);
        $this->objectCache[$class] = $object;

        return $object;
    }
}
