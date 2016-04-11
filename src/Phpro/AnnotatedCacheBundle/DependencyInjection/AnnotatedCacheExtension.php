<?php
declare(strict_types=1);

namespace Phpro\AnnotatedCacheBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Processor;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

/**
 * Class AnnotatedCacheExtension
 *
 * @package Phpro\AnnotatedCacheBundle\DependencyInjection
 */
class AnnotatedCacheExtension extends Extension
{
    /**
     * {@inheritdoc}
     */
    public function load(array $config, ContainerBuilder $container)
    {
        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
        $loader->load('services.yml');

        $config = $this->parseConfiguration($config);

        $this->applyKeyGenerator($config, $container);
        $this->applyProxyCongiruation($config, $container);
        $this->applyCachePools($config, $container);
    }

    /**
     * @param array $config
     *
     * @return array
     */
    private function parseConfiguration(array $config) : array
    {
        $processor = new Processor();
        $configuration = new Configuration();

        return $processor->processConfiguration($configuration, $config);
    }

    /**
     * @param array            $config
     * @param ContainerBuilder $container
     */
    private function applyKeyGenerator(array $config, ContainerBuilder $container)
    {
        $keyGenerator = $config['key_generator'];
        $container->setAlias('phpro.annotated_cache.keygenerator.default', $keyGenerator);
    }

    /**
     * @param array            $config
     * @param ContainerBuilder $container
     */
    private function applyProxyCongiruation(array $config, ContainerBuilder $container)
    {
        $proxyConfig = $config['proxy_config'];
        $container->setParameter('phpro.annotated_cache.params.proxies_target_dir', $proxyConfig['cache_dir']);
        $container->setParameter('phpro.annotated_cache.params.proxies_namespace', $proxyConfig['namespace']);
        $container->setParameter(
            'phpro.annotated_cache.params.proxies_register_autoloader',
            $proxyConfig['register_autoloader']
        );
    }

    /**
     * @param array            $config
     * @param ContainerBuilder $container
     */
    private function applyCachePools(array $config, ContainerBuilder $container)
    {
        $poolsConfig = $config['pools'];
        $definition = $container->findDefinition('phpro.annotation_cache.cache.pool_manager');

        foreach ($poolsConfig as $poolName => $poolConfig) {
            $definition->addMethodCall('addPool', [
                $poolName, new Reference($poolConfig['service'])
            ]);
        }
    }
}
