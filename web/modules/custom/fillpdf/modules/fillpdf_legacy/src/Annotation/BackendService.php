<?php

namespace Drupal\fillpdf_legacy\Annotation;

use Drupal\Component\Annotation\Plugin;

/**
 * Defines a FillPDF BackendService item annotation object.
 *
 * @deprecated in fillpdf:8.x-4.9 and is removed from fillpdf:8.x-5.0.
 *   Use the PdfBackend plugin type instead.
 * @see https://www.drupal.org/node/3059476
 * @see \Drupal\fillpdf\Annotation\PdfBackend
 *
 * @Annotation
 */
class BackendService extends Plugin {

  /**
   * The plugin ID.
   *
   * @var string
   */
  public $id;

  /**
   * The label of the plugin.
   *
   * @var \Drupal\Core\Annotation\Translation
   *
   * @ingroup plugin_translatable
   */
  public $label;

}
