<?php

namespace Drupal\bfss_assessment\Entity;

use Drupal\Core\Entity\ContentEntityBase;
use Drupal\Core\Field\BaseFieldDefinition;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\Core\Entity\ContentEntityInterface;

/**
 * Defines the bfsspayments entity.
 *
 * @ingroup bfsspayments
 *
 * @ContentEntityType(
 *   id = "bfsspayments",
 *   label = @Translation("Payments"),
 *   handlers = {
 *     "view_builder" = "Drupal\Core\Entity\EntityViewBuilder",
 *     "list_builder" = "Drupal\bfss_assessment\Entity\Controller\PaymentsListBuilder",
 *     "access" = "Drupal\bfss_assessment\Entity\Controller\PaymentsAccessControlHandler",
 *   },
 *   base_table = "bfsspayments",
 *   entity_keys = {
 *     "id" = "id",
 *     "uuid" = "uuid",
 *   },
 *   links = {
 *     "canonical" = "/bfsspayments/view/{id}",
 *     "collection" = "/bfsspayments/list",
 *   },
 * )
 */

class BfssPayments extends ContentEntityBase implements ContentEntityInterface {

  public static function baseFieldDefinitions(EntityTypeInterface $entity_type) {
    $fields = [];
    $fieldData = [
      'id' => 'integer',
      'uuid' => 'uuid',
      'user_id' => 'string',
      'user_name' => 'string',
      'assessment' => 'string',
      'assessment_title' => 'string',
      'service' => 'string',
      'time' => 'string',
      'first_name' => 'string',
      'last_name' => 'string',
      'phone' => 'string',
      'email' => 'string',
      'name_on_card' => 'string',
      'credit_card_number' => 'string',
      'expiration_month' => 'string',
      'expiration_year' => 'string',
      'cvv' => 'string',
      'address_1' => 'string',
      'address_2' => 'string',
      'city' => 'string',
      'state' => 'string',
      'zip' => 'string',
      'country' => 'string',
      'payment_status' => 'string',
      'extra' => 'string',
      'notes' => 'string',
      'until' => 'string',
      'created' => 'created',
    ];
    foreach ($fieldData as $key => $value) {
      $fields[$key] = self::createField($key, $value);
    }
    return $fields;
  }

  public static function createField($fieldName = null, $fieldType){
    $fieldName = strtoupper(str_replace('_', ' ', $fieldName));
    $def = BaseFieldDefinition::create($fieldType)->setLabel(t($fieldName))->setDescription(t($fieldName.' of the Payment entity.'));
    if (in_array(strtolower($fieldName), ['id', 'uuid'])) {
      $def->setReadOnly(TRUE);
    }
    return $def;
  }
}
