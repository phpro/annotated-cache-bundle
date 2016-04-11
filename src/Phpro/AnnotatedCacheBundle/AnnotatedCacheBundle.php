<?php
declare(strict_types=1);

namespace Phpro\AnnotatedCacheBundle;

use Phpro\AnnotatedCacheBundle\DependencyInjection\Compiler;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

/**
 * Class AnnotatedCacheBundle
 *
 * @package Phpro\AnnotatedCacheBundle
 */
class AnnotatedCacheBundle extends Bundle
{
    /**
     * @param ContainerBuilder $container
     */
    public function build(ContainerBuilder $container)
    {
        $container->addCompilerPass(new Compiler\CacheEligableCompilerPass());
        $container->addCompilerPass(new Compiler\InterceptorCompilerPass());
    }
}
