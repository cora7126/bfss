<?php

namespace Drupal\fillpdf\Plugin;

use Drupal\Component\Plugin\PluginBase;
use Drupal\fillpdf_legacy\Plugin\BackendServiceInterface;

/**
 * Base class for FillPDF BackendService plugins.
 *
 * @deprecated in fillpdf:8.x-4.9 and is removed from fillpdf:8.x-5.0.
 *   Extend PdfBackendBase instead.
 * @see https://www.drupal.org/node/3059476
 * @see \Drupal\fillpdf\Plugin\PdfBackendBase
 */
abstract class BackendServiceBase extends PluginBase implements BackendServiceInterface {

}
