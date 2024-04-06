<?php

namespace Funbox\Framework\Container;

use Funbox\Framework\Container\Exceptions\ContainerException;
use Psr\Container\ContainerInterface;

class Container implements ContainerInterface
{

    private array $services = [];

    public function add(string $id, string | object $concrete = null): void
    {
        if (null === $concrete) {
            if (!class_exists($id)) {
                throw new ContainerException("Service $id could not be found!");
            }

            $concrete = $id;
        }

        $this->services[$id] = $concrete;
    }

    public function get(string $id): null | object
    {
        if (!$this->has($id)) {
            if (!class_exists($id)) {
                throw new ContainerException("Service $id could not be resolved!");
            }

            $this->add($id);
        }

        return $this->resolve($this->services[$id]);
    }

    private function resolve($class): null | object
    {
        $reflectionClass = new \ReflectionClass($class);
        $constructor = $reflectionClass->getConstructor();

        if (null === $constructor) {
            return $reflectionClass->newInstance();
        }

        $params = $constructor->getParameters();
        $classDependencies = $this->resolveDependencies($params);
        $service = $reflectionClass->newInstanceArgs($classDependencies);

        return $service;
    }

    private function resolveDependencies(array $params): array
    {
        $classDeps = [];
        foreach ($params as $param) {
            $serviceType = $param->getType();
            $service = $this->get($serviceType->getName());

            $classDeps[] = $service;
        }

        return $classDeps;
    }

    public function has(string $id): bool
    {
        return array_key_exists($id, $this->services);
    }

}
