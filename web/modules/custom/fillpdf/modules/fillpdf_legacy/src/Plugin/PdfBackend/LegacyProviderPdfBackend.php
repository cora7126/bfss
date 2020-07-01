<?php

namespace Drupal\fillpdf_legacy\Plugin\PdfBackend;

use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\file\FileInterface;
use Drupal\fillpdf\Entity\FillPdfForm;
use Drupal\fillpdf\FieldMapping\ImageFieldMapping;
use Drupal\fillpdf\FieldMapping\TextFieldMapping;
use Drupal\fillpdf_legacy\Plugin\FillPdfBackendManager;
use Drupal\fillpdf\Plugin\PdfBackendBase;
use Drupal\fillpdf\FillPdfBackendPluginInterface;
use Drupal\fillpdf\FillPdfFormInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Legacy provider PdfBackend plugin.
 *
 * Provides backwards compatibility with legacy FillPdfBackend plugins.
 *
 * @PdfBackend(
 *   id = "legacy_provider",
 * )
 *
 * @deprecated in fillpdf:8.x-4.9 and is removed from fillpdf:8.x-5.0. This is
 *   only a BC wrapper. Once you turned your legacy FillPdfBackend plugins into
 *   new PdfBackend plugins, this wrapper will not be needed anymore.
 * @see https://www.drupal.org/node/3059476
 */
final class LegacyProviderPdfBackend extends PdfBackendBase implements ContainerFactoryPluginInterface, FillPdfBackendPluginInterface {

  /**
   * The FillPDF legacy backend.
   *
   * @var \Drupal\fillpdf\FillPdfBackendPluginInterface
   */
  private $legacyBackend;

  /**
   * Constructs a LegacyProviderPdfBackend plugin object.
   *
   * @param array $configuration
   *   A configuration array containing information about the plugin instance.
   * @param string $plugin_id
   *   The plugin_id for the plugin instance.
   * @param array $plugin_definition
   *   The plugin implementation definition.
   * @param \Drupal\fillpdf_legacy\Plugin\FillPdfBackendManager $legacy_backend_manager
   *   The FillPDF legacy backend manager.
   */
  public function __construct(array $configuration, $plugin_id, array $plugin_definition, FillPdfBackendManager $legacy_backend_manager) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->legacyBackend = $legacy_backend_manager->createInstance($configuration['backend'], $configuration);
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('plugin.manager.fillpdf_backend')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function parse(FillPdfFormInterface $fillpdf_form) {
    return $this->legacyBackend->parse($fillpdf_form);
  }

  /**
   * {@inheritdoc}
   */
  public function parseFile(FileInterface $template_file) {
    $fillpdf_form = FillPdfForm::create([
      'file' => $template_file,
    ]);
    return $this->parse($fillpdf_form);
  }

  /**
   * {@inheritdoc}
   */
  public function parseStream($pdf_content) {
    $template_file = file_save_data($pdf_content);
    return $this->parseFile($template_file);
  }

  /**
   * {@inheritdoc}
   */
  public function populateWithFieldData(FillPdfFormInterface $fillpdf_form, array $field_mapping, array $context) {
    return $this->legacyBackend->populateWithFieldData($fillpdf_form, $field_mapping, $context);
  }

  /**
   * {@inheritdoc}
   */
  public function mergeFile(FileInterface $template_file, array $field_mappings, array $context) {
    $legacy_mapping = [];
    foreach ($field_mappings as $pdf_key => $mapping) {
      if ($mapping instanceof TextFieldMapping) {
        $legacy_mapping['fields'][$pdf_key] = (string) $mapping->getData();
      }
      elseif ($mapping instanceof ImageFieldMapping) {
        $uri = (string) $mapping->getUri();
        if ($uri) {
          $legacy_mapping['fields'][$pdf_key] = "{image}{$uri}";
          $image_path_info = pathinfo($uri);
          $legacy_mapping['images'][$pdf_key] = [
            'data' => base64_encode($mapping->getData()),
            'filenamehash' => md5($image_path_info['filename']) . '.' . $image_path_info['extension'],
          ];
        }
      }
    }

    $fillpdf_form = FillPdfForm::create([
      'file' => $template_file,
    ]);
    return $this->legacyBackend->populateWithFieldData($fillpdf_form, $legacy_mapping, $context);
  }

  /**
   * {@inheritdoc}
   */
  public function mergeStream($pdf_content, array $field_mappings, array $context) {
    $template_file = file_save_data($pdf_content);
    return $this->mergeFile($template_file);
  }

}
