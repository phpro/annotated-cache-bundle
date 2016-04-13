<?php


namespace PhproTest\AnnotatedCacheBundle\Unit;

use Phpro\AnnotatedCacheBundle\AnnotatedCacheBundle;
use Phpro\AnnotatedCacheBundle\DependencyInjection\Compiler\CacheEligableCompilerPass;
use Phpro\AnnotatedCacheBundle\DependencyInjection\Compiler\InterceptorCompilerPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

/**
 * Class AnnotatedCacheBundleTest
 *
 * @package PhproTest\AnnotatedCacheBundle\Unit
 */
class AnnotatedCacheBundleTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    function it_is_a_symfony_bundle()
    {
        $this->assertInstanceOf(Bundle::class, new AnnotatedCacheBundle());
    }

    /**
     * @test
     */
    function it_registers_compilers()
    {
        $container = $this->getMockBuilder(ContainerBuilder::class)->getMock();
        $container
            ->expects($this->exactly(2))
            ->method('addCompilerPass')
            ->withConsecutive(
                [$this->isInstanceOf(CacheEligableCompilerPass::class)],
                [$this->isInstanceOf(InterceptorCompilerPass::class)]
        );

        $bundle = new AnnotatedCacheBundle();
        $bundle->build($container);
    }
}
