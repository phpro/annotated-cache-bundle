<?php

namespace PhproTest\AnnotatedCacheBundle\Unit\DependencyInjection;

use Matthias\SymfonyDependencyInjectionTest\PhpUnit\AbstractExtensionConfigurationTestCase;
use Phpro\AnnotatedCacheBundle\DependencyInjection\AnnotatedCacheExtension;
use Phpro\AnnotatedCacheBundle\DependencyInjection\Configuration;

/**
 * Class ConfigurationTest
 *
 * @package PhproTest\AnnotatedCacheBundle\Unit\DependencyInjection
 */
class ConfigurationTest extends AbstractExtensionConfigurationTestCase
{
    /**
     * @return Configuration
     */
    protected function getConfiguration()
    {
        return new Configuration();
    }

    /**
     * @return AnnotatedCacheExtension
     */
    protected function getContainerExtension()
    {
        return new AnnotatedCacheExtension();
    }

    /**
     * @test
     */
    function it_parses_configuration()
    {
        $sources = [FIXTURE_DIR . '/config.yml'];
        $expected = [
            'key_generator' => 'phpro.annotated_cache.keygenerator.expressions',
            'proxy_config' => [
                'cache_dir' => '%kernel.cache_dir%/annotated_cache',
                'namespace' => 'AnnotatedCacheGeneratedProxy',
                'register_autoloader' => true
            ],
            'pools' => [
                'poolname' => [
                    'service' => 'pool.service.key',
                ],
            ],
        ];

        $this->assertProcessedConfigurationEquals($expected, $sources);
    }
}
