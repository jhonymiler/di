<?php


namespace App;

use Closure;
use Exception;
use ReflectionClass;
use ReflectionFunction;
use ReflectionNamedType;
use ReflectionParameter;

class Container
{
    private static ?self $instance = null;
    protected $bindings = [];
    protected $instances = [];

    public function __construct()
    {
    }

    public static function getInstance(): self
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function bind(string $class, Closure $closure = null)
    {
        $this->bindings[$class] = $closure ?? $class;
    }

    public function get($key)
    {
        if (isset($this->instances[$key])) {
            return $this->instances[$key];
        }

        if (isset($this->bindings[$key])) {
            $concrete = $this->bindings[$key];
            $object = null;

            if ($concrete instanceof Closure) {
                $object = $concrete($this);
            } else {
                $object = $this->build($concrete);
            }

            $this->instances[$key] = $object;

            return $object;
        }

        $concrete = $this->resolveConcrete($key);
        if ($concrete !== null) {
            $object = $this->build($concrete);
            $this->instances[$key] = $object;
            return $object;
        }

        throw new Exception("Class {$key} not found in container.");
    }

    public function call(Closure $closure)
    {
        $reflector = new ReflectionFunction($closure);
        $parameters = $reflector->getParameters();
        $dependencies = $this->resolveDependencies($parameters);

        return $reflector->invokeArgs($dependencies);
    }

    protected function build(string $class)
    {
        $reflector = new ReflectionClass($class);
        $constructor = $reflector->getConstructor();

        if ($constructor === null) {
            return new $class();
        }

        $parameters = $constructor->getParameters();
        $dependencies = $this->resolveDependencies($parameters);

        return $reflector->newInstanceArgs($dependencies);
    }

    protected function resolveConcrete(string $key)
    {
        $concrete = $key;
        if (isset($this->bindings[$key])) {
            return $this->bindings[$key];
        } elseif (class_exists($concrete)) {
            return $concrete;
        }

        return null;
    }

    protected function resolveDependencies(array $parameters)
    {
        $dependencies = [];

        foreach ($parameters as $parameter) {
            $type = $parameter->getType();

            if ($type === null || $type instanceof ReflectionNamedType && $type->isBuiltin()) {
                throw new Exception("Unable to resolve non-class dependency.");
            }

            $dependencyClass = $type->getName();
            $dependencies[] = $this->get($dependencyClass);
        }

        return $dependencies;
    }
}
