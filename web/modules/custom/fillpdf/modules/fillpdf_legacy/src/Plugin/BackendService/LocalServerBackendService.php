<?php

namespace Drupal\fillpdf_legacy\Plugin\BackendService;

use Drupal\Core\File\FileSystem;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\fillpdf\Plugin\PdfBackendManager;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Legacy LocalServer BackendService plugin.
 *
 * @BackendService(
 *   id = "local_service",
 *   label = @Translation("FillPDF LocalServer")
 * )
 *
 * @deprecated in fillpdf:8.x-4.9 and is removed from fillpdf:8.x-5.0.
 *   Use the new LocalServerPdfBackend plugin instead.
 * @see https://www.drupal.org/node/3059476
 * @see \Drupal\fillpdf\Plugin\PdfBackend\LocalServerPdfBackend
 */
class LocalServerBackendService extends PdfBackendManager implements ContainerFactoryPluginInterface {

  /**
   * The FillPDF legacy backend manager.
   *
   * @var \Drupal\fillpdf\Plugin\PdfBackendInterface
   */
  private $pdfBackend;

  /**
   * The configuration.
   *
   * @var array
   */
  protected $configuration;

  /**
   * The file system.
   *
   * @var \Drupal\Core\File\FileSystem
   */
  protected $fileSystem;

  /**
   * Constructs a \Drupal\Component\Plugin\PluginBase object.
   *
   * @param array $configuration
   *   A configuration array containing information about the plugin instance.
   * @param string $plugin_id
   *   The plugin_id for the plugin instance.
   * @param mixed $plugin_definition
   *   The plugin implementation definition.
   * @param \Drupal\Core\File\FileSystem $file_system
   *   The file system.
   * @param \Drupal\fillpdf\Plugin\PdfBackendManager $pdf_backend_manager
   *   The FillPDF legacy backend manager.
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, FileSystem $file_system, PdfBackendManager $pdf_backend_manager) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);

    $this->configuration = $configuration;
    $this->fileSystem = $file_system;
    $this->pdfBackend = $pdf_backend_manager->createInstance($configuration['backend'], $configuration);
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('file_system'),
      $container->get('plugin.manager.fillpdf.pdf_backend')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function parse($pdf_content) {
    return $this->pdfBackend->parseStream($pdf_content);
  }

  /**
   * {@inheritdoc}
   */
  public function merge($pdf_content, array $field_mappings, array $context) {
    return $this->pdfBackend->mergeStream($pdf_content, $field_mappings, $context);
  }

}
