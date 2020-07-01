<?php

namespace Drupal\fillpdf;

/**
 * Interface FillPdfFormInterface.
 *
 * @package Drupal\fillpdf
 */
interface FillPdfFormInterface extends ExportableContentEntityInterface {

  /**
   * Returns all FillPdfFormFields associated with this FillPdfForm.
   *
   * @return \Drupal\fillpdf\FillPdfFormFieldInterface[]
   *   Associative array of FillPdfFormFields keyed by the pdf_key.
   */
  public function getFormFields();

  /**
   * Gets this FillPdfForm's storage scheme.
   *
   * @return string
   *   The storage scheme to be used for PDF files generated from this
   *   FillPdfForm.
   */
  public function getStorageScheme();

  /**
   * Gets this FillPdfForm's storage path.
   *
   * @return string
   *   The storage path to be used for PDF files generated from this
   *   FillPdfForm.
   */
  public function getStoragePath();

}
