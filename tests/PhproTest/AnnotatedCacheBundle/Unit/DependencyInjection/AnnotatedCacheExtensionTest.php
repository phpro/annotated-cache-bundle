<?php

namespace PhproTest\AnnotatedCacheBundle\Unit\DependencyInjection;

use Matthias\SymfonyDependencyInjectionTest\PhpUnit\AbstractExtensionTestCase;
use Phpro\AnnotatedCacheBundle\DependencyInjection\AnnotatedCacheExtension;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\Yaml\Yaml;

/**
 * Class AnnotatedCacheExtensionTest
 *
 * @package PhproTest\AnnotatedCacheBundle\Unit\DependencyInjection
 */
class AnnotatedCacheExtensionTest extends AbstractExtensionTestCase
{
    /**
     * @var array
     */
    protected $config;

    /**
     * @return array
     */
    protected function getContainerExtensions()
    {
        return [
            new AnnotatedCacheExtension()
        ];
    }


    protected function setUp()
    {
        parent::setUp();

        $yaml = file_get_contents(FIXTURE_DIR . '/config.yml');
        $parsed = Yaml::parse($yaml);
        $this->config = $parsed['annotated_cache'];
    }

    /**
     * @test
     */
    function it_should_create_key_generator_alias()
    {
        $this->load($this->config);

        $this->assertContainerBuilderHasAlias(
            'phpro.annotated_cache.keygenerator.default',
            'phpro.annotated_cache.keygenerator.expressions'
        );
    }

    /**
     * @test
     */
    function it_should_set_proxy_parameters()
    {
        $this->load($this->config);

        $this->assertContainerBuilderHasParameter(
            'phpro.annotated_cache.params.proxies_target_dir',
            '%kernel.cache_dir%/annotated_cache'
        );
        $this->assertContainerBuilderHasParameter(
            'phpro.annotated_cache.params.proxies_namespace',
            'AnnotatedCacheGeneratedProxy'
        );
        $this->assertContainerBuilderHasParameter(
            'phpro.annotated_cache.params.proxies_register_autoloader',
            true
        );
    }

    /**
     * @test
     */
    function it_should_add_pools_to_the_manager()
    {
        $this->load($this->config);

        $this->assertContainerBuilderHasServiceDefinitionWithMethodCall(
            'phpro.annotation_cache.cache.pool_manager',
            'addPool',
            ['poolname', new Reference('pool.service.key')]
        );
    }
}
