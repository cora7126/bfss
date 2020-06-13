<?php

/**
 * @file
 * Provides form to sync Freshdesk Contacts with Drupal users.
 * @author Randall Box <rb@randallbox.com>
 *
 * @todo Add some more options
 */

namespace Drupal\freshdesk_integration\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Sync contacts.
 */
class FreshdeskIntegrationSyncContactsForm extends FormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'freshdesk_integration_sync_contacts';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $unsynced_users = _freshdesk_integration_get_number_of_unsynced_users();
    
    $form['#markup'] = '<div>There are currently ' . $unsynced_users . ' unsynchronized users.</div>';
    
    $form['actions']['submit'] = array(
      '#type' => 'submit',
      '#value' => $this->t('Sync Now'),
    );
    
    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $batch = array(
     'title' => t('Synchronizing users to contacts'),
     'operations' => array(array('_freshdesk_integration_batch_sync', array())),
     'finished' => '_freshdesk_integration_batch_sync_finished',
    );
    
    batch_set($batch);
  }
}
