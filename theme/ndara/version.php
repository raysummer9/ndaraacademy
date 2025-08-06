<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * Ndara Academy custom theme.
 *
 * @package    theme_ndara
 * @copyright  2024 Ndara Academy
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

$plugin->version   = 2024080600;        // The current plugin version (Date: YYYYMMDDXX).
$plugin->requires  = 2024042200;        // Requires this Moodle version.
$plugin->component = 'theme_ndara';     // Full name of the plugin (used for diagnostics).
$plugin->dependencies = [
    'theme_boost' => 2024042200,
]; 