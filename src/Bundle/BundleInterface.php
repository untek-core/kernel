<?php

namespace Untek\Core\Kernel\Bundle;

use Psr\Container\ContainerInterface;

interface BundleInterface
{

    public function getName(): string;

    public function boot(): void;

    public function setContainer(ContainerInterface $container);

    public function dependecies(): array;
}
