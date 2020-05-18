<?php  
/**
 * @file
 * Contains \Drupal\bfss_month_view\Form\MonthSelectForm.
 */

namespace Drupal\bfss_month_view\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Ajax\AjaxResponse;
use Drupal\Core\Ajax\AlertCommand;
use \Drupal\node\Entity\Node;
use Drupal\node\NodeInterface;
Use Drupal\paragraphs\Entity\Paragraph;
use Drupal\Core\Render\Markup;
use Drupal\Core\Ajax\InvokeCommand;
use \Drupal\user\Entity\User;
use Drupal\Core\Ajax\RedirectCommand;

/**
 * Contribute form.
 */
class MonthViewForm extends FormBase {
  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'month_view_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {

    $month = [
            'Posterboard' =>'Posterboard',
            'MonthView' =>'Month View',
          ];

    $default_value = isset($_GET['MonthView'])?$_GET['MonthView']:'Posterboard';

	
  	global $base_url;

  	$monthcrr = str_replace("/", "-", $month_crr);
  	$prev_month_ts = strtotime('01-'.$monthcrr.' -1 month');
  	$next_month_ts = strtotime('01-'.$monthcrr.' +1 month');
  	$getlastmonth=date('m/Y',$prev_month_ts);
  	$getnextmonth=date('m/Y',$next_month_ts);
	

    $form['MonthView'] = [
      '#type' => 'select',
      '#options' => $month,
      '#default_value' => $default_value,
      //'#required' => TRUE,
      //'#title' => $this->t('Date of Show:'),
      '#prefix' => '<div class="box niceselect">',
      '#suffix' => '</div>',
      '#ajax' => [
              'callback' => '::myAjaxCallback', // don't forget :: when calling a class method.
              //'callback' => [$this, 'myAjaxCallback'], //alternative notation
              'disable-refocus' => FALSE, // Or TRUE to prevent re-focusing on the triggering element.
              'event' => 'change',
              'wrapper' => 'edit-output', // This element is updated with this AJAX callback.
              'progress' => [
                'type' => 'throbber',
                'message' => $this->t('Verifying entry...'),
          ],
        ],
      ];



    // $form['#method'] = 'get'; 
    // $form['actions']['#type'] = 'actions';
    // $form['actions']['submit'] = array(
    //   '#type' => 'submit',
    //   '#value' => $this->t('Filter'),
    //   '#button_type' => 'primary',
    //    '#prefix' => '<div class="filter_btn">',
    //   '#suffix' => '</div>',
    // );
    

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

    public function validateEmailAjax(array &$form, FormStateInterface $form_state) {
      
    }

    public function myAjaxCallback(array &$form, FormStateInterface $form_state){
      global $base_url;   
      $current_path = \Drupal::service('path.current')->getPath();
      $response = new \Drupal\Core\Ajax\AjaxResponse();
      $url = $base_url.$current_path.'?MonthView='.$form_state->getValue('MonthView').'&EventData=data';
      $response->addCommand(new RedirectCommand($url));  
      return $response;
  }




}// class close 