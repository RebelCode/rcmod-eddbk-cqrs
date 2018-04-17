<?php

namespace RebelCode\EddBookings\Storage\Resource\Module;

use Dhii\Data\Container\ContainerFactoryInterface;
use Dhii\Exception\InternalException;
use Dhii\Util\String\StringableInterface as Stringable;
use Psr\Container\ContainerInterface;
use RebelCode\Modular\Module\AbstractBaseModule;

class EddBkCqrsModule extends AbstractBaseModule
{
    /**
     * Constructor.
     *
     * @since [*next-version*]
     *
     * @param string|Stringable         $key                  The module key.
     * @param string[]|Stringable[]     $dependencies         The module dependencies.
     * @param ContainerFactoryInterface $containerFactory     The container factory.
     * @param ContainerFactoryInterface $configFactory        The config factory.
     * @param ContainerFactoryInterface $compContainerFactory The composite container factory.
     */
    public function __construct(
        $key,
        $dependencies = [],
        ContainerFactoryInterface $containerFactory,
        ContainerFactoryInterface $configFactory,
        ContainerFactoryInterface $compContainerFactory
    ) {
        $this->_initModule(
            $key,
            $dependencies,
            $containerFactory,
            $configFactory,
            $compContainerFactory
        );
    }


    /**
     * {@inheritdoc}
     *
     * @since [*next-version*]
     *
     * @throws InternalException If an error occurred while reading from the config file.
     */
    public function setup()
    {
        return $this->_setupContainer(
            $this->_loadPhpConfigFile(RC_WP_BOOKINGS_CQRS_MODULE_CONFIG_FILE),
            []
        );
    }

    /**
     * {@inheritdoc}
     *
     * @since [*next-version*]
     */
    public function run(ContainerInterface $c = null)
    {
    }
}
