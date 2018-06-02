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
        'table_prefix' => '${wpdb_prefix}eddbk_'
    ]
];
