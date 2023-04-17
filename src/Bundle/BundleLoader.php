<?php

namespace Untek\Core\Kernel\Bundle;

use LogicException;
use Psr\Container\ContainerInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class BundleLoader
{
    /** @var array | BundleInterface[] */
    protected array $bundles = [];
    
    protected array $bundlesDefinition = [];

    private string $context;

    protected string $environment;

    public function __construct(string $environment, string $context, array $bundlesDefinition)
    {
        $this->environment = $environment;
        $this->context = $context;
        $this->bundlesDefinition = $bundlesDefinition;
    }

    public function buildContainer(ContainerBuilder $containerBuilder): void
    {
        $this->initializeBundles();
        foreach ($this->bundles as $bundle) {
            /** @var BundleInterface $bundle */
            $bundle->build($containerBuilder);
        }
    }

    public function boot(ContainerInterface $container): void {
        $this->initializeBundles();
        foreach ($this->bundles as $bundle) {
            /** @var BundleInterface $bundle */
            if(method_exists($bundle, 'boot')) {
                $bundle->boot($container);
            }
        }
    }

//    public function registerEvents(EventDispatcherInterface $eventDispatcher, ContainerInterface $container): void {
//        $this->initializeBundles();
//        foreach ($this->bundles as $bundle) {
//            /** @var BundleInterface $bundle */
//            if(method_exists($bundle, 'registerEvents')) {
//                $bundle->registerEvents($eventDispatcher, $container);
//            }
//        }
//    }

    /**
     * Initializes bundles.
     *
     * @return void
     * @throws LogicException if two bundles share a common name
     *
     */
    protected function initializeBundles() :void
    {
        if(!empty($this->bundles)) {
            return;
        }
        $this->bundles = [];
        foreach ($this->bundlesDefinition as $class => $options) {
            if ($this->isAllowBundle($class, $options)) {
                /** @var BundleInterface $bundle */
                $bundle = $this->createBundleInstance($class, $options);
                $name = $bundle->getName();
                $this->checkBundleForDefined($name);
                $this->bundles[$name] = $bundle;
            }
        }
    }

    protected function checkBundleForDefined(string $name)
    {
        if (isset($this->bundles[$name])) {
            throw new LogicException(sprintf('Trying to register two bundles with the same name "%s".', $name));
        }
    }

    protected function createBundleInstance(string $class, array $options): BundleInterface
    {
        return new $class();
    }

    protected function isAllowBundle(string $class, array $options): bool
    {
        $envs = $options;
        $isAllowEnv = $envs[$this->environment] ?? $envs['all'] ?? false;
        $isAllowContext = true;
        return $isAllowEnv && $isAllowContext;
    }
}
