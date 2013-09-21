<?php
/**
 * Plugin Name: Symlink Fixer
 * Plugin URI: http://github.com/danielchatfield/wordpress-symlink-fixer
 * Description: Fixes plugins that are symlinked into the plugins directory.
 * Author: Daniel Chatfield
 * Author URI: http://www.danielchatfield.com
 * Version 0.0.0
 */

# Create a map of dirname( $plugin ) -> dirname( realpath( $plugin ) )
global $plugin_dir_map;// I Know, another shitty global
$plugin_dir_map = array();

foreach (wp_get_active_and_valid_plugins() as $plugin) {
    $plugin_dir_map[dirname(realpath($plugin))] = dirname($plugin);
}

add_filter('plugins_url', 'dc_plugins_url', 10, 3);

function dc_plugins_url($url, $path, $plugin) {
    if (empty($plugin) || !is_string($plugin)) {
        return $url;
    }

    $plugin = dc_plugins_file_fixer($plugin);

    if ( !empty($plugin) && 0 === strpos($plugin, $mu_plugin_dir) )
        $url = WPMU_PLUGIN_URL;
    else
        $url = WP_PLUGIN_URL;


    $url = set_url_scheme( $url );

    if ( !empty($plugin) && is_string($plugin) ) {
        $folder = dirname(plugin_basename($plugin));
        if ( '.' != $folder )
            $url .= '/' . ltrim($folder, '/');
    }

    if ( $path && is_string( $path ) )
        $url .= '/' . ltrim($path, '/');

    return $url;
}

function dc_plugins_file_fixer($plugin) {
    foreach ($plugin_dir_map as $broken => $fixed) {
        $plugin = preg_replace('#^' . preg_quote( $broken, '#') . '/#', $fixed, $plugin);
    }

    return $path;
}

function dc_path_fixer($path) {
    $path = str_replace('\\', '/', $path);
    $path = preg_replace('|/+|', '/', $path);
    return $path;
}
