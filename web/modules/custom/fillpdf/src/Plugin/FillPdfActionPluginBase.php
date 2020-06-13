<?php

namespace Drupal\fillpdf\Plugin;

use Drupal\Component\Plugin\PluginBase;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Base class for FillPDF action plugins.
 */
abstract class FillPdfActionPluginBase extends PluginBase implements FillPdfActionPluginInterface {

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static($configuration, $plugin_id, $plugin_definition);
  }

  /**
   * {@inheritdoc}
   */
  public function calculateDependencies() {
    return [];
  }

  /**
   * Gets this plugin's configuration.
   *
   * @return array
   *   An array of this plugin's configuration.
   *
   * @todo Remove superfluous docs once drupal:8.6.x is no longer supported.
   * @deprecated in fillpdf:8.x-4.8 and is removed from fillpdf:8.x-5.0. Custom
   *   FillPdfActionPlugins may implement ConfigurableInterface instead.
   * @see https://www.drupal.org/project/fillpdf/issues/3058862
   * @see \Drupal\Component\Plugin\ConfigurableInterface::getConfiguration()
   */
  public function getConfiguration() {
    @trigger_error('FillPdfActionPluginInterface::getConfiguration() is deprecated in fillpdf:8.x-4.8 and is removed from fillpdf:8.x-5.0. Custom FillPdfActionPlugins may extend ConfigurableInterface instead. See https://www.drupal.org/project/fillpdf/issues/3058862', E_USER_DEPRECATED);
    return $this->configuration;
  }

  /**
   * Sets the configuration for this plugin instance.
   *
   * @param array $configuration
   *   An associative array containing the plugin's configuration.
   *
   * @todo Remove superfluous docs once drupal:8.6.x is no longer supported.
   * @deprecated in fillpdf:8.x-4.8 and is removed from fillpdf:8.x-5.0. Custom
   *   FillPdfActionPlugins may implement ConfigurableInterface instead.
   * @see https://www.drupal.org/project/fillpdf/issues/3058862
   * @see \Drupal\Component\Plugin\ConfigurableInterface::setConfiguration()
   */
  public function setConfiguration(array $configuration) {
    @trigger_error('FillPdfActionPluginInterface::setConfiguration() is deprecated in fillpdf:8.x-4.8 and is removed from fillpdf:8.x-5.0. Custom FillPdfActionPlugins may extend ConfigurableInterface instead. See https://www.drupal.org/project/fillpdf/issues/3058862', E_USER_DEPRECATED);
    $this->configuration = $configuration;
  }

  /**
   * Gets default configuration for this plugin.
   *
   * @return array
   *   An associative array with the default configuration.
   *
   * @todo Remove superfluous docs once drupal:8.6.x is no longer supported.
   * @deprecated in fillpdf:8.x-4.8 and is removed from fillpdf:8.x-5.0. Custom
   *   FillPdfActionPlugins may implement ConfigurableInterface instead.
   * @see https://www.drupal.org/project/fillpdf/issues/3058862
   * @see \Drupal\Component\Plugin\ConfigurableInterface::defaultConfiguration()
   */
  public function defaultConfiguration() {
    @trigger_error('FillPdfActionPluginInterface::defaultConfiguration() is deprecated in fillpdf:8.x-4.8 and is removed from fillpdf:8.x-5.0. Custom FillPdfActionPlugins may extend ConfigurableInterface instead. See https://www.drupal.org/project/fillpdf/issues/3058862', E_USER_DEPRECATED);
    return [];
  }

  /**
   * {@inheritdoc}
   *
   * @todo Remove once drupal:8.6.x is no longer supported.
   * @see https://www.drupal.org/project/fillpdf/issues/3058862
   */
  public function isConfigurable() {
    @trigger_error('FillPdfActionPluginBase::isConfigurable() is deprecated in fillpdf:8.x-4.8 and is removed from fillpdf:8.x-5.0. Custom FillPdfActionPlugins may extend ConfigurableInterface instead. See https://www.drupal.org/project/fillpdf/issues/3058862', E_USER_DEPRECATED);
    return TRUE;
  }

}
