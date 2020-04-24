<?php
namespace Drupal\bfss_organizations\Plugin\Block;
use Drupal\Core\Block\BlockBase;
use Drupal\Core\Render\Markup;
/**
 * Provides a 'OrganizationSearchBlock' Block.
 *
 * @Block(
 *   id = "organization_search_block",
 *   admin_label = @Translation("Organization Search Block"),
 *   category = @Translation("Organization Search Block"),
 * )
 */
class OrganizationSearchBlock extends BlockBase {
  public function build() {
  	$form = \Drupal::formBuilder()->getForm('Drupal\bfss_organizations\Form\OrganizationSearchForm');
    return $form;
  }
}