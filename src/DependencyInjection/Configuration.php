<?php

namespace FormBuilderBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('form_builder');

        $rootNode
            ->children()

                ->arrayNode('area')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->arrayNode('presets')
                            ->prototype('scalar')->end()
                        ->end()
                    ->end()
                ->end()

                ->arrayNode('form')
                    ->addDefaultsIfNotSet()
                    ->children()

                        ->arrayNode('templates')
                            ->useAttributeAsKey('name')
                            ->prototype('array')
                                ->children()
                                    ->scalarNode('label')->isRequired()->end()
                                    ->scalarNode('value')->isRequired()->end()
                                    ->booleanNode('default')->isRequired()->end()
                                ->end()
                                ->canBeUnset()
                                ->canBeDisabled()
                                ->treatNullLike(['enabled' => FALSE])
                                ->validate()
                                    ->ifTrue(function($v) { return $v['enabled'] === FALSE; })
                                    ->thenUnset()
                                ->end()
                            ->end()
                        ->end()

                        ->arrayNode('field')
                            ->addDefaultsIfNotSet()
                            ->children()
                                ->arrayNode('templates')
                                    ->useAttributeAsKey('name')
                                    ->prototype('array')
                                        ->children()
                                            ->scalarNode('label')->isRequired()->end()
                                            ->scalarNode('value')->isRequired()->end()
                                            ->booleanNode('default')->isRequired()->end()
                                        ->end()
                                        ->canBeUnset()
                                        ->canBeDisabled()
                                        ->treatNullLike(['enabled' => FALSE])
                                        ->validate()
                                            ->ifTrue(function($v) { return $v['enabled'] === FALSE; })
                                            ->thenUnset()
                                        ->end()
                                    ->end()
                                ->end()
                            ->end()
                        ->end()

                    ->end()
                ->end()

                ->arrayNode('admin')
                    ->children()
                        ->arrayNode('active_elements')
                            ->addDefaultsIfNotSet()
                            ->children()
                                ->arrayNode('fields')
                                ->prototype('scalar')->end()
                                ->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()

                ->arrayNode('backend_base_field_type_groups')
                    ->useAttributeAsKey('name')
                    ->prototype('array')
                        ->children()
                            ->scalarNode('id')->end()
                            ->scalarNode('label')->end()
                            ->scalarNode('icon_class')->end()
                        ->end()
                        ->canBeUnset()
                        ->canBeDisabled()
                        ->treatNullLike(['enabled' => FALSE])
                        ->validate()
                            ->ifTrue(function($v) { return $v['enabled'] === FALSE; })
                            ->thenUnset()
                        ->end()
                    ->end()
                ->end()

                ->arrayNode('backend_base_field_type_config')
                    ->children()
                        ->arrayNode('tabs')
                            ->useAttributeAsKey('name')
                            ->prototype('array')
                                ->children()
                                    ->scalarNode('label')->isRequired()->end()
                                ->end()
                            ->end()
                        ->end()
                        ->arrayNode('display_groups')
                            ->useAttributeAsKey('name')
                            ->prototype('array')
                                ->children()
                                    ->scalarNode('tab_id')->isRequired()->end()
                                    ->scalarNode('label')->isRequired()->end()
                                    ->booleanNode('collapsed')->defaultFalse()->end()
                                ->end()
                            ->end()
                        ->end()
                        ->arrayNode('fields')
                            ->useAttributeAsKey('name')
                            ->prototype('array')
                                ->children()
                                    ->scalarNode('display_group_id')
                                        ->isRequired()
                                        ->validate()
                                            ->ifInArray(['display_name', 'type', 'template', 'order', 'options'])
                                            ->thenInvalid('%s is a reserved field type id.')
                                        ->end()
                                    ->end()
                                    ->scalarNode('type')->isRequired()->end()
                                    ->scalarNode('label')->isRequired()->end()
                                    ->variableNode('config')->end()
                                ->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()

                ->arrayNode('backend_field_type_config')
                    ->useAttributeAsKey('name')
                    ->prototype('array')
                        ->children()
                            ->scalarNode('form_type_group')->isRequired()->end()
                            ->scalarNode('label')->isRequired()->end()
                            ->scalarNode('icon_class')->end()
                            ->arrayNode('tabs')
                                ->useAttributeAsKey('name')
                                ->prototype('array')
                                    ->children()
                                        ->scalarNode('label')->isRequired()->end()
                                    ->end()
                                ->end()
                            ->end()
                            ->arrayNode('display_groups')
                                ->useAttributeAsKey('name')
                                ->prototype('array')
                                    ->children()
                                        ->scalarNode('tab_id')->isRequired()->end()
                                        ->scalarNode('label')->isRequired()->end()
                                        ->booleanNode('collapsed')->defaultFalse()->end()
                                    ->end()
                                ->end()
                            ->end()
                            ->arrayNode('fields')
                                ->useAttributeAsKey('name')
                                ->prototype('array')
                                    ->children()
                                        ->scalarNode('display_group_id')
                                            ->isRequired()
                                            ->validate()
                                                ->ifInArray(['display_name', 'type', 'template', 'order', 'options'])
                                                ->thenInvalid('%s is a reserved field type id.')
                                            ->end()
                                        ->end()
                                        ->scalarNode('type')->isRequired()->end()
                                        ->scalarNode('label')->isRequired()->end()
                                        ->variableNode('config')->end()
                                    ->end()
                                    ->canBeUnset()
                                    ->canBeDisabled()
                                    ->treatNullLike(['enabled' => FALSE])
                                    ->beforeNormalization()
                                        ->ifNull()
                                        ->then(function ($v) {
                                            $v = ['display_group_id' => NULL, 'type' => NULL, 'label' => NULL, 'enabled' => FALSE];
                                            return $v;
                                        })
                                    ->end()
                                    ->validate()
                                        ->ifTrue(function($v) { return $v['enabled'] === FALSE; })
                                        ->then(function($v) { return FALSE;})
                                    ->end()
                                ->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end();

        return $treeBuilder;
    }
}