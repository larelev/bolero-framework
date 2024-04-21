<?php

namespace Bolero\Framework\Container;

use Bolero\Framework\Container\Exceptions\ContainerException;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;
use ReflectionClass;

class Container implements ContainerInterface
{

    private array $services = [];

    public function get(string $id): null|object
    {
        if (!$this->has($id)) {
            if (!class_exists($id)) {
                throw new ContainerException("Service $id could not be resolved!");
            }

            $this->add($id);
        }

        return $this->resolve($this->services[$id]);
    }

    public function has(string $id): bool
    {
        return array_key_exists($id, $this->services);
    }

    /**
     * @throws ContainerException
     */
    public function add(string $id, string|object $concrete = null): void
    {
        if (null === $concrete) {
            if (!class_exists($id)) {
                throw new ContainerException("Service $id could not be found!");
            }

            $concrete = $id;
        }

        $this->services[$id] = $concrete;
    }

    /**
     * @throws \ReflectionException
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    private function resolve($class): null|object
    {
        $reflectionClass = new ReflectionClass($class);
        $constructor = $reflectionClass->getConstructor();

        if (null === $constructor) {
            return $reflectionClass->newInstance();
        }

        $params = $constructor->getParameters();
        $classDependencies = $this->resolveDependencies($params);
        $service = $reflectionClass->newInstanceArgs($classDependencies);

        return $service;
    }

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
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

}
