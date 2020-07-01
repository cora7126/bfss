<?php

namespace Drupal\fillpdf_legacy\Plugin;

use Drupal\Core\Plugin\DefaultPluginManager;
use Drupal\Core\Cache\CacheBackendInterface;
use Drupal\Core\Extension\ModuleHandlerInterface;

/**
 * Provides the legacy FillPDF BackendService plugin manager.
 *
 * @deprecated in fillpdf:8.x-4.9 and is removed from fillpdf:8.x-5.0.
 *   Use PdfBackendManager and the new PdfBackend plugins instead.
 * @see https://www.drupal.org/node/3059476
 * @see \Drupal\fillpdf\Plugin\PdfBackendManager
 */
class BackendServiceManager extends DefaultPluginManager {

  /**
   * Constructs a new LegacyBackendServiceManager object.
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
    parent::__construct('Plugin/BackendService', $namespaces, $module_handler, 'Drupal\fillpdf_legacy\Plugin\BackendServiceInterface', 'Drupal\fillpdf_legacy\Annotation\BackendService');

    $this->alterInfo('fillpdf_fillpdf_backend_info');
    $this->setCacheBackend($cache_backend, 'fillpdf_fillpdf_backend_plugins');
  }

}
