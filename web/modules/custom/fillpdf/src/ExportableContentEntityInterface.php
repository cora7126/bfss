<?php

namespace Drupal\fillpdf;

use Drupal\Core\Entity\ContentEntityInterface;

/**
 * Interface ExportableContentEntityInterface.
 *
 * @package Drupal\fillpdf
 */
interface ExportableContentEntityInterface extends ContentEntityInterface {

  /**
   * Gets the content entity properties to export if declared on the annotation.
   *
   * @return array|null
   *   The properties to export or NULL if they can not be determined.
   */
  public function getPropertiesToExport();

}
