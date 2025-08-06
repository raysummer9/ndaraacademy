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

if ($ADMIN->fulltree) {
    // Create a new category for Ndara Academy settings
    $settings = new theme_boost_admin_settingspage_tabs('themesettingndara', get_string('configtitle', 'theme_ndara'));

    // General settings tab
    $page = new admin_settingpage('theme_ndara_general', get_string('generalsettings', 'theme_ndara'));

    // Logo setting
    $name = 'theme_ndara/logo';
    $title = get_string('logo', 'theme_ndara');
    $description = get_string('logodesc', 'theme_ndara');
    $setting = new admin_setting_configstoredfile($name, $title, $description, 'logo');
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    // Primary color setting
    $name = 'theme_ndara/primarycolor';
    $title = get_string('primarycolor', 'theme_ndara');
    $description = get_string('primarycolordesc', 'theme_ndara');
    $default = '#2563eb';
    $setting = new admin_setting_configcolourpicker($name, $title, $description, $default);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    // Secondary color setting
    $name = 'theme_ndara/secondarycolor';
    $title = get_string('secondarycolor', 'theme_ndara');
    $description = get_string('secondarycolordesc', 'theme_ndara');
    $default = '#64748b';
    $setting = new admin_setting_configcolourpicker($name, $title, $description, $default);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    // Accent color setting
    $name = 'theme_ndara/accentcolor';
    $title = get_string('accentcolor', 'theme_ndara');
    $description = get_string('accentcolordesc', 'theme_ndara');
    $default = '#7c3aed';
    $setting = new admin_setting_configcolourpicker($name, $title, $description, $default);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    // Custom CSS setting
    $name = 'theme_ndara/customcss';
    $title = get_string('customcss', 'theme_ndara');
    $description = get_string('customcssdesc', 'theme_ndara');
    $default = '';
    $setting = new admin_setting_configtextarea($name, $title, $description, $default);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    $settings->add($page);

    // Advanced settings tab
    $page = new admin_settingpage('theme_ndara_advanced', get_string('advancedsettings', 'theme_ndara'));

    // Raw SCSS setting
    $name = 'theme_ndara/scss';
    $title = get_string('scss', 'theme_ndara');
    $description = get_string('scssdesc', 'theme_ndara');
    $default = '';
    $setting = new admin_setting_scsscode($name, $title, $description, $default, PARAM_RAW);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    // Raw SCSS pre setting
    $name = 'theme_ndara/scsspre';
    $title = get_string('scsspre', 'theme_ndara');
    $description = get_string('scsspredesc', 'theme_ndara');
    $default = '';
    $setting = new admin_setting_scsscode($name, $title, $description, $default, PARAM_RAW);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    $settings->add($page);
} 