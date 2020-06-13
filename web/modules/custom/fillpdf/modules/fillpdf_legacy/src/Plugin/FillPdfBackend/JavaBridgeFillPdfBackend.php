<?php

namespace Drupal\fillpdf_legacy\Plugin\FillPdfBackend;

use Drupal\Core\File\FileSystem;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\Core\Plugin\PluginBase;
use Drupal\file\Entity\File;
use Drupal\fillpdf\FillPdfBackendPluginInterface;
use Drupal\fillpdf\FillPdfFormInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Legacy JavaBridge FillPdfBackend plugin.
 *
 * @Plugin(
 *   id = "local"
 * )
 *
 * @deprecated in fillpdf:8.x-4.9 and is removed from fillpdf:8.x-5.0.
 *   Use FillPDF LocalServer with the new LocalServerPdfBackend plugin instead.
 * @see https://www.drupal.org/node/3059476
 * @see \Drupal\fillpdf\Plugin\PdfBackend\LocalServerPdfBackend
 */
class JavaBridgeFillPdfBackend extends PluginBase implements FillPdfBackendPluginInterface, ContainerFactoryPluginInterface {

  /**
   * The file system.
   *
   * @var \Drupal\Core\File\FileSystem
   */
  protected $fileSystem;

  /**
   * Constructs a LocalFillPdfBackend plugin object.
   *
   * @param array $configuration
   *   A configuration array containing information about the plugin instance.
   * @param string $plugin_id
   *   The plugin_id for the plugin instance.
   * @param array $plugin_definition
   *   The plugin implementation definition.
   * @param \Drupal\Core\File\FileSystem $file_system
   *   The file system.
   */
  public function __construct(array $configuration, $plugin_id, array $plugin_definition, FileSystem $file_system) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->fileSystem = $file_system;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('file_system')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function parse(FillPdfFormInterface $fillpdf_form) {
    /** @var \Drupal\file\FileInterface $file */
    $file = File::load($fillpdf_form->file->target_id);
    $content = file_get_contents($file->getFileUri());

    $require = drupal_get_path('module', 'fillpdf') . '/lib/JavaBridge/java/Java.inc';
    require_once DRUPAL_ROOT . '/' . $require;
    try {
      $fillpdf = new \java('com.ocdevel.FillpdfService', base64_encode($content), 'bytes');
      $fields = java_values($fillpdf->parse());
    }
    catch (\JavaException $e) {
      $this->messenger()->addError(java_truncate((string) $e));
    }

    return $fields;
  }

  /**
   * {@inheritdoc}
   */
  public function populateWithFieldData(FillPdfFormInterface $fillpdf_form, array $field_mapping, array $context) {
    /** @var \Drupal\file\FileInterface $original_file */
    $original_file = File::load($fillpdf_form->file->target_id);
    $pdf_data = file_get_contents($original_file->getFileUri());
    $fields = $field_mapping['fields'];

    $require = drupal_get_path('module', 'fillpdf') . '/lib/JavaBridge/java/Java.inc';
    require_once DRUPAL_ROOT . '/' . $require;
    try {
      $fillpdf = new \java('com.ocdevel.FillpdfService', base64_encode($pdf_data), 'bytes');
      foreach ($fields as $key => $field) {
        if (substr($field, 0, 7) == '{image}') {
          // Remove {image} marker.
          $image_filepath = substr($field, 7);
          $image_realpath = $this->fileSystem->realpath($image_filepath);
          $fillpdf->image($key, $image_realpath, 'file');
        }
        else {
          $fillpdf->text($key, $field);
        }
      }
    }
    catch (\JavaException $e) {
      $this->messenger()->addError(java_truncate((string) $e));
      return NULL;
    }
    try {
      if ($context['flatten']) {
        $populated_pdf = java_values(base64_decode($fillpdf->toByteArray()));
      }
      else {
        $populated_pdf = java_values(base64_decode($fillpdf->toByteArrayUnflattened()));
      }
    }
    catch (\JavaException $e) {
      $this->messenger()->addError(java_truncate((string) $e));
      return NULL;
    }

    return $populated_pdf;
  }

}
