<?php

/**
 * @file
 * Provides admin settings form for Freshdesk Integration.
 * @author Randall Box <rb@randallbox.com>
 *
 * @todo Add checkbox to identify primary company, since API splits primary and secondary companies
 */

namespace Drupal\freshdesk_integration\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Configure Freshdesk Integration settings for this site.
 */
class FreshdeskIntegrationAdminSettingsForm extends ConfigFormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'freshdesk_integration_admin_settings';
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return ['freshdesk_integration.settings'];
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = $this->config('freshdesk_integration.settings');
    $image_styles = image_style_options(FALSE);
    
    $form['general'] = [
      '#type' => 'fieldset',
      '#title' => $this->t('General settings'),
    ];
    
    $form['general']['freshdesk_integration_push'] = array(
      '#type' => 'checkbox',
      '#title' => t('Push users to Freshdesk account'),
      '#description' => t('Enables pushing of users to Freshdesk account.  Please ensure that configuration options below are populated with the account credentials.'),
      '#default_value' => $config->get('push'),
    );
    
    $form['general']['freshdesk_integration_url'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Your Freshdesk URL subdomain'),
      '#default_value' => $config->get('url'),
      '#required' => TRUE,
      '#maxlength' => 256,
      '#description' => $this->t('Provide the URL subdomain for your Freshdesk account. (http://<strong>domain</strong>.freshdesk.com)'),
    ];
    
    $note = '<div>';
    $note .= t('Click on your Profile Picture on the top right of your Freshdesk dashboard and select "Profile Settings"<br />');
    $note .= t('In the sidebar on the right, you will find the API Key');
    $note .= '</div>';

    $form['general']['freshdesk_integration_apikey'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Your Freshdesk API key'),
      '#default_value' => $config->get('apikey'),
      '#required' => TRUE,
      '#maxlength' => 256,
      '#description' => $this->t('Provide the API key from your Freshdesk account.') . $note,
    ];
    
    $form['general']['freshdesk_integration_image_style'] = [
      '#title' => t('Image style for Freshdesk user profile'),
      '#type' => 'select',
      '#default_value' => $config->get('image_style'),
      '#empty_option' => t('None (original image)'),
      '#options' => $image_styles,
      '#description' => $this->t('Select the image style to be pushed to Freshdesk for user profile.'),
    ];
    
    $form['rolecompanymapping'] = [
      '#type' => 'fieldset',
      '#title' => $this->t('Map roles to Freshdesk companies'),
    ];
    
    $form['rolecompanymapping']['#markup'] = '<div>(Currently only one should be defined since API splits primary and secondary companies fields.)</div>';
    
    // Show and update role to company mapping
    $form['rolecompanymapping']['freshdesk_integration_roles_companies'] = array(
      '#type' => 'table',
      '#header' => array(
        'role' => $this->t('Role'),
        'company' => $this->t('Company ID'),
      ),
      '#empty' => $this->t('No role/company mapping yet.'),
    );
    
    // Get all roles and show them in a list
    $roles = user_roles(TRUE);
    $config_roles = $config->get('roles_companies');
    
    foreach ($roles as $role => $role_obj) {
      // Main table structure
      $row = array(
        'role' => ['#markup' => $role],
      );
      
      $row['company'] = array(
        '#type' => 'textfield',
        '#default_value' => isset($config_roles[$role]['company']) ? $config_roles[$role]['company'] : '',
        '#required' => FALSE,
        '#maxlength' => 256,
        '#description' => $this->t('Provide the Freshdesk company ID to map to this role.'),
      );
      
      $form['rolecompanymapping']['freshdesk_integration_roles_companies'][$role] = $row;
    }
    
    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $config = $this->config('freshdesk_integration.settings');
    
    if ($form_state->hasValue(['freshdesk_integration_roles_companies'])) {
      foreach ($form_state->getValue(['freshdesk_integration_roles_companies']) as $role => $settings) {
        if ($settings['company'] != '') {
          $config_roles[$role]['company'] = $settings['company'];
        }
      }
      $config->set('roles_companies', $config_roles);
    }
    
    $config
      ->set('push', $form_state->getValue('freshdesk_integration_push'))
      ->set('url', $form_state->getValue('freshdesk_integration_url'))
      ->set('apikey', $form_state->getValue('freshdesk_integration_apikey'))
      ->set('image_style', $form_state->getValue('freshdesk_integration_image_style'))
      ->save();

    parent::submitForm($form, $form_state);
  }
}
