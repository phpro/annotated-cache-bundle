<?php

namespace Phpro\AnnotatedCacheBundle\Factory;

use ProxyManager\Configuration;
use Symfony\Component\DependencyInjection\Container;

/**
 * Class ProxyConfigurationFactory
 *
 * @package Phpro\AnnotatedCacheBundle\Factory
 */
class ProxyConfigurationFactory
{
    /**
     * @param Container $container
     */
    public static function create(Container $container)
    {
        $proxyTargetDir = $container->getParameter('phpro.annotated_cache.params.proxies_target_dir');
        $proxyNamespace = $container->getParameter('phpro.annotated_cache.params.proxies_namespace');
        $shouldAutoload = $container->getParameter('phpro.annotated_cache.params.proxies_register_autoloader');

        // Make sure to touch the filesystem.
        $container->get('filesystem')->mkdir($proxyTargetDir);

        $configuration = new Configuration();
        $configuration->setProxiesTargetDir($proxyTargetDir);
        $configuration->setProxiesNamespace($proxyNamespace);

        if ($shouldAutoload) {
            spl_autoload_register($configuration->getProxyAutoloader());
        }
    }
}
