<?php

/**
 * This file is part of the eZ Publish Kernel package.
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace EzSystems\PlatformInstallerBundle\Installer;

/**
 * Interface Installer
 *
 * A SQL centric installer interface, in the future we will most likly introduce an updated interface that implies use of
 * our API or SPI and
 *
 * @since 6.1
 */
interface SQLInstaller
{
    /**
     * Schema file in ISO SQL format for additional tables needed for the given install.
     *
     * The database file appends and modifies the schema available in clean data (todo: need spec for clean schema, probably also reduce it further).
     *
     * Example:
     * ```php
     * public function additionalSchemaFile()
     * {
     *     return __DIR__ . '/../Resources/installer/sql/app_schema.sql';
     * }
     * ```
     *
     * @return string|null
     */
    public function additionalSchemaFile();

    /**
     * Database dump file in ISO SQL format for additional tables needed for the given install.
     *
     * The database file appends and modifies the data set available in clean data (todo: need spec for clean data, probably also reduce it further).
     *
     * Parameter $varDir is provided to be able to replace hardcoded path with the actual one, example:
     *      * Example:
     * ```php
     * public function additionalSchemaFile()
     * {
     *     return __DIR__ . '/../Resources/installer/sql/app_data.sql';
     * }
     * ```
     *
     * @param string $varDir
     * @return string|null
     */
    public function additionalDataFile($varDir);

    public function additionalBinaryDir();
}
