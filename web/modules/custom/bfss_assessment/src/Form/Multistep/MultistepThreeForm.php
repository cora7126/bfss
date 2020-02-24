<?php
/**
 * Detials selection
 * @file
 * Contains \Drupal\bfss_assessment\Form\Multistep\MultistepThreeForm.
 */

namespace Drupal\bfss_assessment\Form\Multistep;

use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Url;
use Drupal\user\Entity\User;

class MultistepThreeForm extends MultistepFormBase {

  /**
   * {@inheritdoc}.
   */
  public function getFormId() {
    return 'multistep_form_three';
  }

  /**
   * {@inheritdoc}.
   */
  public function buildForm(array $form, FormStateInterface $form_state) {

    $form = parent::buildForm($form, $form_state);
    #if not avail
    if(!$this->store->get('assessment') || !$this->store->get('service') || !$this->store->get('time')) {
      $this->assessmentService->notAvailableMessage();
      $form['actions']['submit']['#access'] = false;
      return $form;
    }
    #attach library for styling
    $form['#attached']['library'][] = 'bfss_assessment/assessment_mulitform_lib';
    #add status bar class
    $form['heading']['#prefix'] = '<div class="three">';
    $form['heading']['#suffix'] = '</div>';
    #current user details
    $user = User::load(\Drupal::currentUser()->id());
    $name = $user->get('name')->value;
    $mail = $user->get('mail')->value;

    #add container class to form
    $form['#attributes']['class'][] = 'container';
    $form['first_name'] = array(
      '#type' => 'textfield',
      '#placeholder' => $this->t('First Name'),
      '#default_value' => $this->store->get('first_name') ? $this->store->get('first_name') : '',
      '#required' => true,
    );
    if ($name) {
      $form['first_name']['#disabled'] = false;
      $form['first_name']['#default_value'] = $name;
    }
    $form['last_name'] = array(
      '#type' => 'textfield',
      '#placeholder' => $this->t('Last Name'),
      '#default_value' => $this->store->get('last_name') ? $this->store->get('last_name') : '',
    );
    $form['phone'] = array(
      '#type' => 'textfield',
      '#placeholder' => $this->t('Telephone'),
      '#default_value' => $this->store->get('phone') ? $this->store->get('phone') : '',
      '#required' => true,
    );
    $form['email'] = array(
      '#type' => 'email',
      '#placeholder' => $this->t('Email'),
      '#default_value' => $this->store->get('email') ? $this->store->get('email') : '',
      '#required' => true,
    );
    if ($mail) {
      $form['email']['#default_value'] = $mail;
    }

    $form['actions']['previous'] = array(
      '#type' => 'link',
      '#title' => $this->t('Back'),
      '#attributes' => array(
        'class' => array('button'),
      ),
      '#weight' => 0,
      '#url' => Url::fromRoute('bfss_assessment.multistep_two'),
    );
    $form['actions']['submit']['#value'] = $this->t('Next');

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $this->store->set('first_name', $form_state->getValue('first_name'));
    $this->store->set('last_name', $form_state->getValue('last_name'));
    $this->store->set('phone', $form_state->getValue('phone'));
    $this->store->set('email', $form_state->getValue('email'));
    $form_state->setRedirect('bfss_assessment.multistep_four');
  }
}