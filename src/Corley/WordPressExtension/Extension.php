<?php
/**
 * @license MIT
 */

namespace Corley\WordPressExtension;

use Behat\Behat\Extension\ExtensionInterface;

use Symfony\Component\Config\FileLocator,
    Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition,
    Symfony\Component\DependencyInjection\ContainerBuilder,
    Symfony\Component\DependencyInjection\Loader\XmlFileLoader;

class Extension implements ExtensionInterface
{

    /**
     * {@inheritdoc}
     */
    public function getConfig(ArrayNodeDefinition $builder)
    {
        $builder
            ->addDefaultsIfNotSet()
            ->children()
                ->scalarNode('path')
                    ->defaultValue(__DIR__)
                ->end()
            ->end();
    }

    public function load(array $config, ContainerBuilder $container)
    {
        $loader = new XmlFileLoader($container, new FileLocator(__DIR__.'/services'));
        $loader->load('core.xml');

        $container->setParameter('behat.wordpress.path', $config['path']);
    }

    /**
     * Returns compiler passes used by mink extension.
     *
     * @return array
     */
    public function getCompilerPasses()
    {
        return array(
        );
    }
}
