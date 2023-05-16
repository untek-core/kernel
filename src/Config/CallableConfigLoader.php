<?php

namespace Untek\Core\Kernel\Config;

use Untek\Core\Instance\Libs\Resolvers\ArgumentMetadataResolver;

class CallableConfigLoader
{

    public function __construct(private ArgumentMetadataResolver $argumentMetadataResolver)
    {
    }

    public function boot($configuratorCallback, array $availableArguments = []): void
    {
        if (is_callable($configuratorCallback)) {
            $parameters = $this->argumentMetadataResolver->resolve($configuratorCallback, $availableArguments);
            call_user_func_array($configuratorCallback, $parameters);
        }
    }
}
