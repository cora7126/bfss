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

use Drupal\Core\Ajax\HtmlCommand;
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
    $param = \Drupal::request()->query->all();
    $nid = $param['nid'];
    //Permissions
  $permissions_service = \Drupal::service('bfss_admin.bfss_admin_permissions');
  $rel = $permissions_service->bfss_admin_permissions();
  $pending_approval =  unserialize($rel['pending_approval']);
   $form['#attached']['library'][] = 'bfss_organizations/add_organization'; //here can add library
    if($pending_approval['create']==1 || $pending_approval['admin']==1){
       $form['left_section_start'] = [
              '#type' => 'markup',
              '#markup' => '<div class="left_section popup_left_section">
                <div class="athlete_left">
                                <h3><div class="toggle_icon">
                                    <i class="fa fa-minus"></i><i class="fa fa-plus hide"></i>
                                  </div>APPROVE ORGANIZATION
                                </h3>
                          <div class="items_div">',
          ];
      $form['resident'] = [
        '#type' => 'container',
        '#attributes' => ['id' => 'resident-details'],
      ];
      $form['message'] = [
                '#type' => 'markup',
                '#markup' => '<div class="result_message"></div>',
              ];
    
      $form['resident']['html_links'] = [
       '#type' => 'markup',
       '#markup' => '<div><p>You are about to approve this organization and add it to the</p>
                    <p>system, which CAN NOT be undone. Are you sure you have</p>
                    <p>checked the spelling and did a search for this organization in</p>
                    <p>the system before continuing?</p></div>',
      ];
      $form['actions'] = [
        '#type' => 'actions',
      ];

      // $form['actions']['submit'] = [
      //   '#type' => 'submit',
      //   '#value' => $this->t('YES, APPROVE'),
      //   '#attributes' => [
      //     'class' => ['btn button--primary'],
      //   ]
      // ];
       $form['actions']['submit'] = [
                '#type' => 'submit',
                '#value' => $this->t('Yes approved'),
                '#button_type' => 'primary',
                 '#ajax' => [
                    'callback' => '::submitForm', // don't forget :: when calling a class method.
                    //'callback' => [$this, 'myAjaxCallback'], //alternative notation
                    'disable-refocus' => FALSE, // Or TRUE to prevent re-focusing on the triggering element.
                    'event' => 'click',
                    'wrapper' => 'edit-output', // This element is updated with this AJAX callback.
                    'progress' => [
                      'type' => 'throbber',
                      'message' => $this->t('Verifying entry...'),
                    ],
                  ]
      ];
       $form['left_section_end'] = [
              '#type' => 'markup',
              '#markup' => '</div>
              </div></div>',
      ];
 
  }else{
          $form['access_message'] = [ //for custom message "like: ajax msgs"
              '#type' => 'markup',
              '#markup' => '<p>we are sorry. you can not access this page.</p>',
          ];
  }

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
    $param = \Drupal::request()->query->all();
    if(isset($param['nid'])){
      $node = Node::load($param['nid']); 
      $node->setPublished(TRUE); 
      $node->save();

      // for success message show
      $message = 'Successfully Approved!';
      $response = new AjaxResponse();
      $response->addCommand(
        new HtmlCommand(
          '.result_message',
          '<div class="success_message">'.$message.'</div>'
        )
      );
      return $response;
    }            
  }

}