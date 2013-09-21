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
global $dc_plugin_dir_map;// I Know, another shitty global
$dc_plugin_dir_map = array();

foreach (wp_get_active_and_valid_plugins() as $tmp) {
    $fake = dc_path_fixer(dirname($tmp));
    $real = dc_path_fixer(dirname(realpath($tmp)));
    if ($fake !== $real) {
        $dc_plugin_dir_map[$fake] = $real;
    }
}
unset($tmp);

add_filter('plugins_url', 'dc_plugins_url', 0, 3);

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
    global $dc_plugin_dir_map;
    foreach ($dc_plugin_dir_map as $fake => $real) {
        $plugin = preg_replace('#^' . preg_quote( $real, '#') . '#', $fake, $plugin);
    }

    return $plugin;
}

function dc_path_fixer($path) {
    $path = str_replace('\\', '/', $path);
    $path = preg_replace('|/+|', '/', $path);
    return $path;
}
