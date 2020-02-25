<?php

namespace Drupal\bfss_assessment\Entity\Controller;

use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Entity\EntityListBuilder;
use Drupal\Core\Link;

/**
 * Defines a class to build a listing of Bank transaction entity entities.
 *
 * @ingroup ie_bank_transactions
 */
class PaymentsListBuilder extends EntityListBuilder {


  /**
   * {@inheritdoc}
   */
  public function buildHeader() {
    $fields = $this->getAllFields();
    // $header['id'] = $this->t('ID');
    foreach ($fields as $key => $value) {
      $header[$key] = $this->t($value);
    }
    return $header + parent::buildHeader();
  }

  /**
   * {@inheritdoc}
   */
  public function buildRow(EntityInterface $entity) {
    /* @var $entity \Drupal\bfss_assessment\Entity\BfssPayments */
    $fields = $this->getAllFields();
    // $row['id'] = $entity->id();
    foreach ($fields as $key => $value) {
      $value = $entity->get($key)->value;
      if ($key == 'assessment_title') {
        $value .= "(".$entity->get('assessment')->value.")";
      }elseif ($key == 'first_name') {
        $value .= " ".$entity->get('last_name')->value;
        $value .= $this->addOtherValues('phone', $entity);
        $value .= $this->addOtherValues('email', $entity);
      }elseif ($key == 'address_1') {
        $value .= $this->addOtherValues('address_2', $entity);
        $value .= $this->addOtherValues('city', $entity);
        $value .= $this->addOtherValues('state', $entity);
        $value .= $this->addOtherValues('zip', $entity);
        $value .= $this->addOtherValues('country', $entity);
      }elseif ($key == 'notes') {
        $value .= " ".$entity->get('extra')->value;
      }elseif ($key == 'user_name') {
        $value .= " (".$entity->get('user_id')->value.")";
      }
      if ($key ==  'created' || $key == 'time') {
        if ($value) {
          $value = date('D, M d Y H:i A', $value);
        }
      }
      $row[$key] = $value;
    }
    
    /*$row['name'] = Link::createFromRoute(
      $entity->label(),
      'entity.bfsspayments.canonical',
      ['payment_id' => $entity->id()]
    );*/
    return $row + parent::buildRow($entity);
  }

  public function getAllFields(){
    return  [
      'id' => 'ID',
      // 'uuid' => 'User UUID',
      // 'user_id' => 'User Id',
      'user_name' => 'User(ID)',
      // 'assessment' => 'Assessment ID',
      'assessment_title' => 'Assessment Title(ID)',
      'service' => 'Price',
      'time' => 'Schedule Time',
      'until' => 'Duration(in minutes)',
      'first_name' => 'Customer Details',
      // 'last_name' => 'Last Name',
      // 'phone' => 'Phone',
      // 'email' => 'Email',
      'name_on_card' => 'Name on Card',
      // 'credit_card_number' => 'Card Number',
      // 'expiration_month' => 'string',
      // 'expiration_year' => 'string',
      // 'cvv' => 'string',
      'address_1' => 'Billing Address',
      // 'address_2' => 'Address 2',
      // 'city' => 'City',
      // 'state' => 'State',
      // 'zip' => 'ZIP',
      // 'country' => 'Country',
      // 'payment_status' => 'Status',
      // 'extra' => 'Extra',
      'created' => 'Created on',
      'notes' => 'Notes',
    ];
  }

  public function addOtherValues($key, $entity){
    if ($entity->get('country')->value) {
      return " / ".$entity->get('country')->value;
    }
    return null;
  }
}
