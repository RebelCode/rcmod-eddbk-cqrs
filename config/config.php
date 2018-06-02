<?php

/**
 * This file contains the configuration for the EDD Bookings WordPress CQRS Module.
 *
 * @since [*next-version*]
 */

return [
    'cqrs' => [
        /*
         * Modifies the table prefix to also include a plugin-specific prefix.
         *
         * @since [*next-version*]
         */
        'table_prefix' => '${wpdb_prefix}eddbk_',
    ],

    'migrations' => [
        /**
         * The name of the WP Option where the current database version is stored.
         *
         * @since [*next-version*]
         */
        'db_version_option_name' => 'eddbk_db_version',
    ]
];
