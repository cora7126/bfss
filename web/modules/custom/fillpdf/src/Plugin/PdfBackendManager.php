<?php

namespace Drupal\fillpdf\Plugin;

use Drupal\Component\Plugin\FallbackPluginManagerInterface;
use Drupal\Core\Plugin\DefaultPluginManager;
use Drupal\Core\Cache\CacheBackendInterface;
use Drupal\Core\Extension\ModuleHandlerInterface;

/**
 * Provides the FillPDF PdfBackend plugin manager.
 */
class PdfBackendManager extends DefaultPluginManager implements FallbackPluginManagerInterface {

  /**
   * Constructs a new BackendServiceManager object.
   *
   * @param \Traversable $namespaces
   *   An object that implements \Traversable which contains the root paths
   *   keyed by the corresponding namespace to look for plugin implementations.
   * @param \Drupal\Core\Cache\CacheBackendInterface $cache_backend
   *   Cache backend instance to use.
   * @param \Drupal\Core\Extension\ModuleHandlerInterface $module_handler
   *   The module handler to invoke the alter hook with.
   */
  public function __construct(\Traversable $namespaces, CacheBackendInterface $cache_backend, ModuleHandlerInterface $module_handler) {
    parent::__construct('Plugin/PdfBackend', $namespaces, $module_handler, 'Drupal\fillpdf\Plugin\PdfBackendInterface', 'Drupal\fillpdf\Annotation\PdfBackend');

    $this->alterInfo('fillpdf_pdfbackend_info');
    $this->setCacheBackend($cache_backend, 'fillpdf_pdfbackend_plugins');
  }

  /**
   * Gets the definitions of all FillPDF backend plugins.
   *
   * @return mixed[]
   *   An associative array of plugin definitions, keyed by plugin ID and sorted
   *   by weight.
   */
  public function getDefinitions() {
    // Get all plugin definitions of this type.
    $definitions = parent::getDefinitions();

    // Unset the legacy plugin provider.
    // @todo Remove in fillpdf:8.x-5.x.
    unset($definitions['legacy_provider']);

    // Sort plugins by weight.
    uasort($definitions, function ($a, $b) {
      return $a['weight'] - $b['weight'];
    });

    return $definitions;
  }

  /**
   * {@inheritdoc}
   */
  public function getFallbackPluginId($plugin_id, array $configuration = []) {
    return 'legacy_provider';
  }

  /**
   * {@inheritdoc}
   */
  protected function handlePluginNotFound($plugin_id, array $configuration) {
    $fallback_id = $this->getFallbackPluginId($plugin_id, $configuration);
    $configuration['backend'] = $plugin_id;
    return $this->getFactory()->createInstance($fallback_id, $configuration);
  }

}
