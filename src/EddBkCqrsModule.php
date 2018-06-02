<?php

namespace RebelCode\EddBookings\Storage\Resource\Module;

use Dhii\Config\ConfigFactoryInterface;
use Dhii\Data\Container\ContainerFactoryInterface;
use Dhii\Event\EventFactoryInterface;
use Dhii\Exception\InternalException;
use Dhii\Util\String\StringableInterface as Stringable;
use Exception;
use mysqli;
use Psr\Container\ContainerInterface;
use Psr\EventManager\EventManagerInterface;
use RebelCode\Modular\Module\AbstractBaseModule;

class EddBkCqrsModule extends AbstractBaseModule
{
    /**
     * The module version.
     *
     * @since [*next-version*]
     */
    const MODULE_VERSION = '0.1';

    /**
     * The database version.
     *
     * @since [*next-version*]
     */
    const DB_VERSION = 1;

    /**
     * Constructor.
     *
     * @since [*next-version*]
     *
     * @param string|Stringable         $key                  The module key.
     * @param string[]|Stringable[]     $dependencies         The module dependencies.
     * @param ConfigFactoryInterface    $configFactory        The config factory.
     * @param ContainerFactoryInterface $containerFactory     The container factory.
     * @param ContainerFactoryInterface $compContainerFactory The composite container factory.
     * @param EventManagerInterface     $eventManager         The event manager.
     * @param EventFactoryInterface     $eventFactory         The event factory.
     */
    public function __construct(
        $key,
        $dependencies,
        ConfigFactoryInterface $configFactory,
        ContainerFactoryInterface $containerFactory,
        ContainerFactoryInterface $compContainerFactory,
        $eventManager,
        $eventFactory
    ) {
        $this->_initModule($key, $dependencies, $configFactory, $containerFactory, $compContainerFactory);
        $this->_initModuleEvents($eventManager, $eventFactory);
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
            $this->_loadPhpConfigFile(RC_EDDBK_CQRS_MODULE_CONFIG_FILE),
            [
                'eddbk_mysqli'   => function (ContainerInterface $c) {
                    return new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
                },
                'eddbk_migrator' => function (ContainerInterface $c) {
                    return new Migrator(
                        $c->get('eddbk_mysqli'),
                        RC_EDDBK_CQRS_MODULE_MIGRATIONS_DIR,
                        \get_option($c->get('migrations/db_version_option_name'), 0)
                    );
                },
            ]
        );
    }

    /**
     * {@inheritdoc}
     *
     * @since [*next-version*]
     */
    public function run(ContainerInterface $c = null)
    {
        // Handler to migrate to the latest DB version
        $this->_attach('init', function () use ($c) {
            $target   = static::DB_VERSION;
            $migrator = $c->get('eddbk_migrator');

            try {
                // Migrate
                $migrator->migrate($target);
                // Update DB version on success
                \update_option($c->get('migrations/db_version_option_name'), $target);
            } catch (Exception $exception) {}
        });
    }
}
