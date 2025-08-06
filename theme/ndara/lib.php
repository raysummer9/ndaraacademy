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

/**
 * Extends the core navigation to provide additional functionality.
 *
 * @param global_navigation $navigation An object representing the navigation tree
 */
function theme_ndara_extend_navigation(global_navigation $navigation) {
    // Add custom navigation functionality here if needed.
}

/**
 * Extends the global navigation for the theme.
 *
 * @param global_navigation $navigation An object representing the navigation tree
 */
function theme_ndara_extend_global_navigation(global_navigation $navigation) {
    // Add custom global navigation functionality here if needed.
}

/**
 * Extends the settings navigation with the theme settings.
 *
 * @param settings_navigation $settingsnav The settings navigation object
 * @param navigation_node $ndaranode The node to add the settings to
 */
function theme_ndara_extend_settings_navigation(settings_navigation $settingsnav, navigation_node $ndaranode = null) {
    // Add custom settings navigation functionality here if needed.
}

/**
 * Serves any files associated with the theme settings.
 *
 * @param stdClass $course Course object
 * @param stdClass $cm Course module object
 * @param context $context Context
 * @param string $filearea File area
 * @param array $args Extra arguments
 * @param bool $forcedownload Whether or not force download
 * @param array $options Additional options affecting the file serving
 * @return bool|null False if file not found, does not return anything if found - just send the file
 */
function theme_ndara_pluginfile($course, $cm, $context, $filearea, $args, $forcedownload, array $options = array()) {
    if ($context->contextlevel == CONTEXT_SYSTEM) {
        $theme = theme_config::load('ndara');
        // By default, theme files must be cache-able by both browsers and proxies.
        if (!array_key_exists('cacheability', $options)) {
            $options['cacheability'] = 'public';
        }
        return $theme->setting_file_serve($filearea, $args, $forcedownload, $options);
    } else {
        send_file_not_found();
    }
}

/**
 * Process CSS before it is output.
 *
 * @param string $css The CSS
 * @param theme_config $theme The theme config object.
 * @return string The processed CSS
 */
function theme_ndara_process_css($css, $theme) {
    // Define custom CSS variables for the theme
    $css = theme_ndara_set_customcss($theme, $css);
    return $css;
}

/**
 * Adds any custom CSS to the CSS before it is cached.
 *
 * @param theme_config $theme The theme config object.
 * @param string $css The CSS
 * @return string The processed CSS
 */
function theme_ndara_set_customcss($theme, $css) {
    $customcss = $theme->settings->customcss ?? '';
    if (!empty($customcss)) {
        $css .= "\n/* Custom CSS */\n" . $customcss;
    }
    return $css;
} 