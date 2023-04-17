<?php

namespace Untek\Core\Kernel\Bundle;

use Symfony\Component\DependencyInjection\ContainerBuilder;

interface BundleInterface
{
    public function getName(): string;

    public function build(ContainerBuilder $containerBuilder);
}
