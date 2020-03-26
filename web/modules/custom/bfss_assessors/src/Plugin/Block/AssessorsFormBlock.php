<?php


namespace Drupal\bfss_assessors\Plugin\Block;

use Drupal\Core\Block\BlockBase;

/**
 * Provides a 'Assessors Form Block' Block.
 *
 * @Block(
 *   id = "hello_block",
 *   admin_label = @Translation("Assessors Form Block"),
 *   category = @Translation("Assessors Form Block"),
 * )
 */
class AssessorsFormBlock extends BlockBase {

  /**IndividualEleteAssessment.php
   * {@inheritdoc}
   */
  public function build() {
    $form = \Drupal::formBuilder()->getForm('Drupal\bfss_assessors\Form\IndividualEleteAssessment');
    return $form;
  }

}