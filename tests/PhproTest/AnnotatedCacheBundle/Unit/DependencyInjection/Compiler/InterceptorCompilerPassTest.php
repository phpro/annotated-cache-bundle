<?php

namespace PhproTest\AnnotatedCache\Unit\DependencyInjection\Compiler;

use Matthias\SymfonyDependencyInjectionTest\PhpUnit\AbstractCompilerPassTestCase;
use Phpro\AnnotatedCacheBundle\DependencyInjection\Compiler\InterceptorCompilerPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;

/**
 * Class InterceptorCompilerPassTest
 *
 * @package PhproTest\AnnotatedCache\Unit\DependencyInjection\Compiler
 */
class InterceptorCompilerPassTest extends AbstractCompilerPassTestCase
{
    /**
     * @param ContainerBuilder $container
     */
    protected function registerCompilerPass(ContainerBuilder $container)
    {
        $container->addCompilerPass(new InterceptorCompilerPass());
    }

    /**
     * @test
     */
    public function it_swaps_your_service_with_a_proxy()
    {
        $cacheHandler = new Definition();
        $this->setDefinition('phpro.annotation_cache.cache.handler', $cacheHandler);

        $myInterceptor = new Definition();
        $myInterceptor->addTag(InterceptorCompilerPass::TAG_NAME);
        $this->setDefinition('my_interceptor', $myInterceptor);

        $this->compile();

        $this->assertContainerBuilderHasServiceDefinitionWithMethodCall(
            'phpro.annotation_cache.cache.handler',
            'addInterceptor',
            array(
                new Reference('my_interceptor')
            )
        );
    }
}
