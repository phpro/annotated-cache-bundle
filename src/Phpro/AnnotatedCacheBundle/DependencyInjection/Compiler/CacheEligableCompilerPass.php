<?php
declare(strict_types=1);

namespace Phpro\AnnotatedCacheBundle\DependencyInjection\Compiler;

use ProxyManager\Proxy\AccessInterceptorInterface;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;

/**
 * Class CacheEligableCompilerPass
 *
 * @package Phpro\AnnotatedCacheBundle\DependencyInjection\Compiler
 */
class CacheEligableCompilerPass implements CompilerPassInterface
{
    const TAG_NAME = 'annotated_cache.eligable';

    /**
     * @param ContainerBuilder $container
     */
    public function process(ContainerBuilder $container)
    {
        $taggedServices = $container->findTaggedServiceIds(self::TAG_NAME);
        foreach ($taggedServices as $service => $tags) {
            $this->swapServiceWithProxy($container, $service);
        }
    }

    /**
     * @param ContainerBuilder $container
     * @param string           $service
     */
    private function swapServiceWithProxy(ContainerBuilder $container, string $service)
    {
        $instanceId = sprintf('phpro.annotated_cache.eligable_instance.%s', sha1($service));
        $instance = $container->findDefinition($service);
        $instance->setPublic(false);

        $proxy = new Definition(AccessInterceptorInterface::class);
        $proxy->setFactory([new Reference('phpro.annotation_cache.proxy.generator'), 'generate']);
        $proxy->setArguments([
            new Reference($instanceId)
        ]);

        // Make sure that the proxy gets all instance tags:
        $proxy->setTags($instance->getTags());
        $instance->clearTags();

        $container->setDefinition($instanceId, $instance);
        $container->setDefinition($service, $proxy);
    }
}
