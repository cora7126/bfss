<?php  
/**
 * @file
 * Contains \Drupal\bfss_assessment\Form\MonthSelectForm.
 */

namespace Drupal\bfss_assessment\Form;

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
class MonthSelectForm extends FormBase {
  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'month_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    global $base_url;
    $current_path = \Drupal::service('path.current')->getPath();

    $year = date("Y");
    $month = [];
    for ($i = 1; $i < 12; $i++) {
      $key = date('m/Y', strtotime("+$i month"));
      $val = date('F, Y', strtotime("+$i month"));
      $month[$key] = $val; 
    }

    if($current_path == '/upcoming-group-assessments'){
       $month =  array('m/Y'=>'Select Month',date('m/Y') => date('F, Y')) + $month;
    }else{
       $month =  array(date('m/Y') => date('F, Y')) + $month;
    }
   
  
    $current_date = date("Y/m/d");
    $date_arr = explode('/',$current_date);
    if(isset($_GET['showdate'])){
     $exp = explode("/",$_GET['showdate']);
      $M =  $exp[0];
      $Y = $exp[1];
      $month_crr = $M.'/'.$Y;  
    }else{
      $M = $date_arr[1];
      $Y =  $date_arr[0];
      $month_crr = $M.'/'.$Y;  
    }
	
	

	$monthcrr = str_replace("/", "-", $month_crr);
	$prev_month_ts = strtotime('01-'.$monthcrr.' -1 month');
	$next_month_ts = strtotime('01-'.$monthcrr.' +1 month');
	$getlastmonth=date('m/Y',$prev_month_ts);
	$getnextmonth=date('m/Y',$next_month_ts);
	
	if(isset($_GET['showdate'])){
		$valueinfo=$_GET['showdate'];
	

		$monthcrr = str_replace("/", "-", $month_crr);
		$prev_month_ts = strtotime('01-'.$monthcrr.' -1 month');
		$next_month_ts = strtotime('01-'.$monthcrr.' +1 month');
		$getlastmonth=date('m/Y',$prev_month_ts);
		$getnextmonth=date('m/Y',$next_month_ts);

		$getlastval='<a href="'.$base_url.'/upcoming-group-assessments?showdate='.$getlastmonth.'"><i class="fal fa-angle-left mr-2"></i></a>';
		$getnextval='<a href="'.$base_url.'/upcoming-group-assessments?showdate='.$getnextmonth.'"><i class="fal fa-angle-right ml-2"></i></a>';
	}else{
		$getlastval='<a href="'.$base_url.'/upcoming-group-assessments?showdate='.$getlastmonth.'"><i class="fal fa-angle-left mr-2"></i></a>';
		$getnextval='<a href="'.$base_url.'/upcoming-group-assessments?showdate='.$getnextmonth.'"><i class="fal fa-angle-right ml-2"></i></a>';
	}

  if($current_path == '/upcoming-group-assessments' && empty($_GET['showdate']) && !isset($_GET['showdate'])){
    $month_crr = "";
  }elseif($_GET['showdate']== 'm/Y'){
    $month_crr = "";
  }
  else{
    $month_crr = $month_crr;
  }

    $form['showdate'] = [
      '#type' => 'select',
      '#options' => $month,
      '#default_value' => $month_crr,

      '#prefix' => '<div class="box niceselect"><span id="dateofshow">',
      '#suffix' => '</span></div>',
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
     if ($selectedValue = $form_state->getValue('showdate')) {
      $selectedText = $form['showdate']['#options'][$selectedValue];
      $response = new \Drupal\Core\Ajax\AjaxResponse();
      $url = $base_url.$current_path.'?showdate='.$form_state->getValue('showdate');
      $response->addCommand(new RedirectCommand($url));  
    }
    return $response;
  }

}// class close 