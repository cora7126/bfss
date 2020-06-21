<?php

/**
 * @file
 * Drush integration for the pdftk module.
 */

/**
 * Implements hook_drush_command().
 */
function pdftk_drush_command() {
  $items['pdftk-dl'] = array(
    'description' => dt('Downloads the required PHP PDFTK Toolkit library from github.com.'),
    'options' => array(
      '--path' => dt('Optional. A path to the download folder. If omitted, Drush will use the default location
(sites/all/libraries/php-pdtfk-toolkit-master).'),
    ),
  );
  return $items;
}

/**
 * Download the pdftk library from github.
 */
function drush_pdftk_dl() {
  $path = drush_get_option('path', drush_get_context('DRUSH_DRUPAL_ROOT') . '/sites/all/libraries/php-pdtfk-toolkit-master');
  if (drush_shell_exec('git clone http://github.com/bensquire/php-pdtfk-toolkit.git ' . $path)) {
    drush_log(dt('PHP PDFTK Toolkit has been downloaded to @path.', array('@path' => $path)), 'success');
  }
  else {
    drush_log(dt('Drush was unable to download the PHP PDFTK Toolkit to @path.', array('@path' => $path)), 'error');
  }
}

/**
 * Implements drush_MODULE_post_COMMAND().
 */
function drush_pdftk_post_pm_enable() {
  $modules = func_get_args();
  if (in_array('pdftk', $modules)) {
    drush_pdftk_dl();
  }
}
