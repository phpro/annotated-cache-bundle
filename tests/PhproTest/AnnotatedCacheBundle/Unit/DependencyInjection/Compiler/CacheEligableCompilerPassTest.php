<?php

namespace PhproTest\AnnotatedCache\Unit\DependencyInjection\Compiler;

use Matthias\SymfonyDependencyInjectionTest\PhpUnit\AbstractCompilerPassTestCase;
use Phpro\AnnotatedCacheBundle\DependencyInjection\Compiler\CacheEligableCompilerPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;

/**
 * Class CacheEligableCompilerPassTest
 *
 * @package PhproTest\AnnotatedCache\Unit\DependencyInjection\Compiler
 */
class CacheEligableCompilerPassTest extends AbstractCompilerPassTestCase
{
    /**
     * @param ContainerBuilder $container
     */
    protected function registerCompilerPass(ContainerBuilder $container)
    {
        $container->addCompilerPass(new CacheEligableCompilerPass());
    }

    /**
     * @test
     */
    public function it_swaps_your_service_with_a_proxy()
    {
        $myService = new Definition();
        $myService->setArguments([true]);
        $myService->addTag(CacheEligableCompilerPass::TAG_NAME);
        $myService->addTag('sometag');

        $serviceName = 'my_service';
        $newServiceKey = sprintf('phpro.annotated_cache.eligable_instance.%s', sha1('my_service'));

        $this->setDefinition($serviceName, $myService);
        $this->compile();

        // Validate instance:
        $this->assertContainerBuilderHasServiceDefinitionWithArgument($newServiceKey, 0, true);

        // Validate proxy:
        $this->assertContainerBuilderHasServiceDefinitionWithArgument($serviceName, 0, new Reference($newServiceKey));
        $this->assertContainerBuilderHasServiceDefinitionWithTag($serviceName, CacheEligableCompilerPass::TAG_NAME);
        $this->assertContainerBuilderHasServiceDefinitionWithTag($serviceName, 'sometag');
    }
}
