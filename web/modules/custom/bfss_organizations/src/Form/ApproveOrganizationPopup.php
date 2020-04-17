<?php

namespace Drupal\bfss_organizations\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Ajax\AjaxResponse;
use Drupal\Core\Ajax\AlertCommand;
use \Drupal\node\Entity\Node;
use Drupal\node\NodeInterface;
Use Drupal\paragraphs\Entity\Paragraph;
/**
 * Class ApproveOrganizationPopup.
 */
class ApproveOrganizationPopup extends FormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'approve_organization_popup';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {

    $form['resident'] = [
      '#type' => 'container',
      '#attributes' => ['id' => 'resident-details'],
    ];

    $form['resident'] = [
          '#type' => 'fieldgroup',
          '#title' => 'APPROVE ORGANIZATION',
          // '#attributes' => ['id' => 'edit-resident'],
    ];
    $form['resident']['html_links'] = [
     '#type' => 'markup',
     '#markup' => '<div><p>You are about to approve this organization and add it to the</p>
                  <p>system, which CAN NOT be undone. Are you sure you have</p>
                  <p>checked the spelling and did a search for this organization in</p>
                  <p>the system before continuing?</p></div>',
    ];
    $form['resident']['actions'] = [
      '#type' => 'actions',
    ];

    $form['resident']['actions']['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Save'),
      '#attributes' => [
        'class' => ['btn button--primary'],
      ]
    ];
    return $form;
     
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
   
        
  }

}