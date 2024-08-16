<?php

namespace Untek\Core\Kernel\Bundle;

use Psr\Container\ContainerInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

\Untek\Core\Code\Helpers\DeprecateHelper::hardThrow();

interface BundleInterface
{
    public function getName(): string;

    public function build(ContainerBuilder $containerBuilder);

    public function boot(ContainerInterface $container): void;
}
