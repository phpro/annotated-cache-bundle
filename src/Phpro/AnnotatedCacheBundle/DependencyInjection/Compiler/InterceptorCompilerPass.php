<?php
declare(strict_types=1);

namespace Phpro\AnnotatedCacheBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

/**
 * Class InterceptorCompilerPass
 *
 * @package Phpro\AnnotatedCacheBundle\DependencyInjection\Compiler
 */
class InterceptorCompilerPass implements CompilerPassInterface
{
    const TAG_NAME = 'annotated_cache.interceptor';

    /**
     * @param ContainerBuilder $container
     */
    public function process(ContainerBuilder $container)
    {
        $cacheHandler = $container->findDefinition('phpro.annotation_cache.cache.handler');
        $taggedServices = $container->findTaggedServiceIds(self::TAG_NAME);

        foreach ($taggedServices as $service => $tags) {
            $cacheHandler->addMethodCall('addInterceptor', [new Reference($service)]);
        }
    }
}
