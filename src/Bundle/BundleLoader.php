<?php

namespace Untek\Core\Kernel\Bundle;

use LogicException;
use Psr\Container\ContainerInterface;

class BundleLoader
{

    /** @var array | BundleInterface[] */
    protected array $bundles = [];
    private string $context;
    protected string $environment;

    public function __construct(private ContainerInterface $container, string $environment, string $context)
    {
        $this->environment = $environment;
        $this->context = $context;
    }

    /**
     * @param array $bundlesDefinition
     * @throws LogicException if two bundles share a common name
     */
    public function boot(array $bundlesDefinition): void
    {
        $this->initializeBundles($bundlesDefinition);
        $this->bootBundles();
    }

    protected function bootBundles(): void
    {
        foreach ($this->bundles as $bundle) {
            $bundle->setContainer($this->container);
            $bundle->boot();
        }
    }

    /**
     * Initializes bundles.
     *
     * @return void
     * @throws LogicException if two bundles share a common name
     *
     */
    protected function initializeBundles(array $bundlesDefinition)
    {
        $this->bundles = [];
        foreach ($this->registerBundles($bundlesDefinition) as $bundle) {
            /** @var BundleInterface $bundle */
            $name = $bundle->getName();
            if (isset($this->bundles[$name])) {
                throw new LogicException(sprintf('Trying to register two bundles with the same name "%s".', $name));
            }
            if (!$bundle instanceof BundleInterface) {
                throw new LogicException(
                    sprintf(
                        'The "%s" bundle class must implement the "%s" interface.',
                        get_class($bundle),
                        BundleInterface::class
                    )
                );
            }
            $this->bundles[$name] = $bundle;
        }
    }

    protected function registerBundles(array $bundlesDefinition): iterable
    {
        foreach ($bundlesDefinition as $class => $envs) {
            if ($envs[$this->environment] ?? $envs['all'] ?? false) {
                yield new $class();
            }
        }
    }
}
