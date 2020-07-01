<?php

namespace Drupal\fillpdf;

/**
 * Class EntityHelper.
 *
 * @package Drupal\fillpdf
 * @deprecated in fillpdf:8.x-4.7 and is removed from fillpdf:8.x-5.0. Applies
 * to this class and the 'fillpdf.entity_helper' service. The getFormFields()
 * method lives within the FillPdfForm entity now.
 * @see https://www.drupal.org/project/fillpdf/issues/3046257
 * @see \Drupal\fillpdf\Entity\FillPdfForm::getFormFields()
 */
class EntityHelper implements EntityHelperInterface {

  /**
   * Gets all FillPdfFormFields associated with a FillPdfForm.
   *
   * @param \Drupal\fillpdf\FillPdfFormInterface $fillpdf_form
   *   The FillPdfForm.
   *
   * @return \Drupal\fillpdf\FillPdfFormFieldInterface[]
   *   Array of all associated FillPdfFormFields.
   */
  public function getFormFields(FillPdfFormInterface $fillpdf_form) {
    return $fillpdf_form->getFormFields();
  }

}
