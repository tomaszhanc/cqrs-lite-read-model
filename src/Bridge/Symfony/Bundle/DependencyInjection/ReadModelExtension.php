<?php
declare(strict_types=1);

namespace ReadModel\Bridge\Symfony\Bundle\DependencyInjection;

use ReadModel\Bridge\Ramsey\RamseyBinaryUuidTransformer;
use ReadModel\Walker\WalkerBuilder;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Extension\Extension;

class ReadModelExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container)
    {
        $walkerBuilder = new Definition(WalkerBuilder::class);

        if (class_exists('Ramsey\Uuid\Uuid')) {
            $walkerBuilder->addArgument(new Definition(RamseyBinaryUuidTransformer::class));
        }

        $container->setDefinition(WalkerBuilder::class, $walkerBuilder);
    }
}
