<?php
declare(strict_types=1);

namespace Phpro\AnnotatedCacheBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * Class Configuration
 *
 * @package Phpro\AnnotatedCacheBundle\DependencyInjection
 */
class Configuration implements ConfigurationInterface
{
    /**
     * @return TreeBuilder
     */
    public function getConfigTreeBuilder() : TreeBuilder
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('annotated_cache');

        $this->addKeyGenerator($rootNode);
        $this->addProxyCongiruation($rootNode);
        $this->addCachePools($rootNode);

        return $treeBuilder;
    }

    /**
     * @param ArrayNodeDefinition $node
     */
    private function addKeyGenerator(ArrayNodeDefinition $node)
    {
        $node->children()->scalarNode('key_generator')->defaultValue('phpro.annotated_cache.keygenerator.expressions');
    }

    /**
     * @param ArrayNodeDefinition $node
     */
    private function addProxyCongiruation(ArrayNodeDefinition $node)
    {
        $config = $node->children()->arrayNode('proxy_config')->addDefaultsIfNotSet();
        $config->children()
            ->scalarNode('cache_dir')->defaultValue('%kernel.cache_dir%/annotated_cache')->end()
            ->scalarNode('namespace')->defaultValue('AnnotatedCacheGeneratedProxy')->end()
            ->booleanNode('register_autoloader')->defaultTrue()->end()
        ;
    }

    /**
     * @param ArrayNodeDefinition $node
     */
    private function addCachePools(ArrayNodeDefinition $node)
    {
        $config = $node->children()->arrayNode('pools');
        $config->useAttributeAsKey('name');
        $config->example(['poolname' => ['service' => 'service.key']]);

        $items = $config->prototype('array');
        $items->children()
            ->scalarNode('service')->isRequired()->cannotBeEmpty()->end()
        ;
    }
}
