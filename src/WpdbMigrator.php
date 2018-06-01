<?php

namespace RebelCode\EddBookings\Storage\Resource\Module;

use Dhii\Exception\CreateInvalidArgumentExceptionCapableTrait;
use Dhii\Exception\CreateOutOfRangeExceptionCapableTrait;
use Dhii\I18n\StringTranslatingTrait;
use Dhii\Util\Normalization\NormalizeIntCapableTrait;
use Dhii\Util\Normalization\NormalizeStringCapableTrait;
use Dhii\Util\String\StringableInterface as Stringable;
use RebelCode\Storage\Resource\WordPress\Wpdb\ExecuteWpdbQueryCapableTrait;
use RebelCode\Storage\Resource\WordPress\Wpdb\WpdbAwareTrait;
use wpdb;

/**
 * Performs database migrations through WPDB.
 *
 * @since [*next-version*]
 */
class WpdbMigrator
{
    /* @since [*next-version*] */
    use WpdbAwareTrait;

    /* @since [*next-version*] */
    use ExecuteWpdbQueryCapableTrait;

    /* @since [*next-version*] */
    use NormalizeIntCapableTrait;

    /* @since [*next-version*] */
    use NormalizeStringCapableTrait;

    /* @since [*next-version*] */
    use CreateInvalidArgumentExceptionCapableTrait;

    /* @since [*next-version*] */
    use CreateOutOfRangeExceptionCapableTrait;

    /* @since [*next-version*] */
    use StringTranslatingTrait;

    /**
     * The filename for "up" migrations.
     *
     * @since [*next-version*]
     */
    const UP_MIGRATION_FILENAME = 'up.sql';

    /**
     * The filename for "down" migrations.
     *
     * @since [*next-version*]
     */
    const DOWN_MIGRATION_FILENAME = 'up.sql';

    /**
     * The migrations directory path.
     *
     * @since [*next-version*]
     *
     * @var string|Stringable
     */
    protected $migrationsDir;

    /**
     * Constructor.
     *
     * @since [*next-version*]
     *
     * @param wpdb              $wpdb          The WPDB instance.
     * @param string|Stringable $migrationsDir The migrations directory path.
     */
    public function __construct($wpdb, $migrationsDir)
    {
        $this->_setWpdb($wpdb);
        $this->_setMigrationsDir($migrationsDir);
    }

    /**
     * Retrieves the migrations directory path.
     *
     * @since [*next-version*]
     *
     * @return string|Stringable The migrations directory path.
     */
    protected function _getMigrationsDir()
    {
        return $this->migrationsDir;
    }

    /**
     * Sets the migrations directory path.
     *
     * @since [*next-version*]
     *
     * @param string|Stringable $migrationsDir The migrations directory path.
     */
    protected function _setMigrationsDir($migrationsDir)
    {
        $this->migrationsDir = $this->_normalizeString($migrationsDir);
    }

    /**
     * Performs database migration.
     *
     * @since [*next-version*]
     *
     * @param int|string|Stringable $target The migration target - can be a version, state, preset, etc.
     */
    public function migrate($target)
    {
        $target = $this->_normalizeInt($target);

        switch ($target) {
            case 0:
                $targetDirPath = $this->_getMigrationsDir() . DIRECTORY_SEPARATOR . static::UP_MIGRATION_FILENAME;
                break;

            case 1:
                $targetDirPath = $this->_getMigrationsDir() . DIRECTORY_SEPARATOR . static::DOWN_MIGRATION_FILENAME;
                break;

            default:
                throw $this->_createOutOfRangeException(
                    $this->__('Invalid migration target: "%s"', [$target]), null, null, $target
                );
        }

        $this->_runMigrationFile($targetDirPath);
    }

    /**
     * Runs the migration at a given file path.
     *
     * @since [*next-version*]
     *
     * @param string|Stringable $filePath The path to the migration file.
     */
    protected function _runMigrationFile($filePath)
    {
        $sql = $this->_readSqlMigrationFile($filePath);

        $this->_getWpdb()->query($sql);
    }

    /**
     * Reads the SQL from a migration file.
     *
     * @since [*next-version*]
     *
     * @param string|Stringable $filePath The path to the migration file.
     *
     * @return string|Stringable The read SQL.
     */
    protected function _readSqlMigrationFile($filePath)
    {
        $filePath = $this->_normalizeString($filePath);

        return file_get_contents($filePath);
    }
}
