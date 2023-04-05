<?php

namespace Untek\Core\Kernel\Bundle;

use Psr\Container\ContainerInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

interface BundleInterface
{

    public function getName(): string;

    public function boot(): void;

    public function setContainer(ContainerInterface $container);

    public function build(ContainerBuilder $containerBuilder);
}
