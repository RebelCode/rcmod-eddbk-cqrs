<?php

/**
 * This file contains the configuration for the EDD Bookings WordPress CQRS Module.
 *
 * @since [*next-version*]
 */

/*
 * Modifies the table prefix to also include a plugin-specific prefix.
 *
 * @since [*next-version*]
 */
$cfg['cqrs']['table_prefix'] = '${wpdb_prefix}eddbk_';
