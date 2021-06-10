<?php

require_once(__DIR__ . '/form/SouinConfiguration.php');
require_once(__DIR__ . '/install.php');

/**
 * Plugin Name: Souin Cache system Plugin
 * Plugin URI: https://github.com/darkweak/souin
 * Description: High performance caching solution using Souin.
 * Version: 1.0.0
 * Author: darkweak
 * Author URI: https://github.com/darkweak
 **/
const PLUGIN = 'souinPlugin';
const SECTION = 'souin_configuration_section';
const OPT_NAME = 'souin_values';

function toObject($array)
{
    $obj = new stdClass;
    foreach ($array as $k => $v) {
        if (strlen($k)) {
            if (is_array($v)) {
                $obj->{$k} = toObject($v);
            } else {
                $obj->{$k} = $v;
            }
        }
    }
    return $obj;
}

/**
 * @param $configuration
 * @return string
 */
function parseToYAML($configuration)
{
    $yml = '';

    $enabledSecurity = (bool)$configuration->api->security->enable;
    $enabledSouin = (bool)$configuration->api->souin->enable;
    $isSecureSouin = (bool)$configuration->api->souin->security;
    $yml .= <<<YAML
api:
  basepath: {$configuration->api->basepath}
  security:
    basepath: {$configuration->api->security->basepath}
    enable: $enabledSecurity
    secret: {$configuration->api->security->secret}

YAML;

    if (\count($configuration->api->security->users) > 0) {
        $yml .= <<<YAML
    users:

YAML;

        foreach ($configuration->api->security->users as $user) {
            if (!$user->username || !$user->password) {
                continue;
            }
            $yml .= <<<YAML
    - username: {$user->username}
      password: {$user->password}
YAML;
        }
    }

    $yml .= <<<YAML

  souin:
    basepath: {$configuration->api->souin->basepath}
    enable: $enabledSouin
    security: $isSecureSouin
default_cache:
  port:
    web: {$configuration->default_cache->port->web}
    tls: {$configuration->default_cache->port->tls}
  regex:
    exclude: {$configuration->default_cache->regex->exclude}
  ttl: {$configuration->default_cache->ttl}
log_level: {$configuration->log_level}
reverse_proxy_url: {$configuration->reverse_proxy_url}

YAML;

    if (\count($configuration->ykeys) > 0) {
        $yml .= <<<YAML
ykeys:

YAML;
        foreach ($configuration->ykeys as $ykey) {
            if (!$ykey->name) {
                continue;
            }
            $yml .= <<<YAML
  {$ykey->name}:
    url: {$ykey->url}
YAML;
        }
    }

    return $yml;
}

function parseRepeatableFields($haystack, $length = 2)
{
  $stack = [];
    for ($i = 0; $i < \count($haystack) / $length; $i++) {
        $stack[] = (object)array_merge(
            $haystack[($i * $length)],
            $haystack[($i * $length) + 1] ?: []
        );
    }
  return $stack;
}

function souin_admin_menu()
{
    global $wpdb;

    if (isset($_POST['configuration'])) {
        $table_name = $wpdb->prefix . 'souin';
        $c = $_POST['configuration'];

        $c['api']['security']['users'] = parseRepeatableFields($c['api']['security']['users']);
        $c['ykeys'] = parseRepeatableFields($c['ykeys']);
        $souin = toObject($c);
        $configuration = new SouinConfiguration($souin);
        \file_put_contents('/var/www/html/souin.yml', parseToYAML($souin));

        $wpdb->update(
            $table_name,
            [
                'configuration' => \serialize($configuration),
            ],
            ['id' => 1]
        );
    }

    add_action('admin_enqueue_scripts', 'wpse_repeatable_scripts');

    register_setting(PLUGIN, OPT_NAME);
    $souinTable = $wpdb->prefix . 'souin';
    add_options_page(
        'Souin Cache Plugin',
        'Souin Cache Plugin',
        'manage_options',
        'page_slug',
        'souin_options_page'
    );
    $configuration = \unserialize($wpdb->get_results("SELECT configuration FROM $souinTable WHERE id = 1")[0]->configuration);
    if (!$configuration) {
        $configuration = new SouinConfiguration();
    }
    $configuration->register('', '');
    $configuration->renderField();
}

function wpse_repeatable_scripts()
{
    wp_enqueue_script(
        'jquery-repeatable',
        '/wp-content/plugins/souin/js/repeatable-fields.js',
        ['jquery', 'jquery-ui-core', 'jquery-ui-sortable'],
        '20120206',
        true
    );
    wp_enqueue_script(
        'jquery-repeatable-init',
        '/wp-content/plugins/souin/js/repeatable-init.js',
        ['jquery', 'jquery-ui-core', 'jquery-ui-sortable'],
        '20120206',
        true
    );
}

function souin_options_page()
{
    ?>
  <form method='post'>
    <h2>Sitepoint Settings API Admin Page</h2>

      <?php
      settings_fields(PLUGIN);
      do_settings_sections(PLUGIN);
      submit_button();
      ?>

  </form>
    <?php
}

add_action('admin_menu', 'souin_admin_menu');
