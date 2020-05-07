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
    $year = date("Y");
    $month = [
            '' =>'Month View',
            '01/'.$year.'' =>'January '.$year.'',
            '02/'.$year.'' =>'February '.$year.'',
            '03/'.$year.'' =>'March '.$year.'',
            '04/'.$year.'' =>'April '.$year.'',
            '05/'.$year.'' =>'May '.$year.'',
            '06/'.$year.'' =>'June '.$year.'',
            '07/'.$year.'' =>'July '.$year.'',
            '08/'.$year.'' =>'August '.$year.'',
            '09/'.$year.'' =>'September '.$year.'',
            '10/'.$year.'' =>'October '.$year.'',
            '11/'.$year.'' =>'November '.$year.'',
            '12/'.$year.'' =>'December '.$year.'',
          ];
    $current_date = date("Y/m/d");
    $date_arr = explode('/',$current_date);
    if(isset($_GET['MonthView'])){
     $exp = explode("/",$_GET['MonthView']);
      $M =  $exp[0];
      $Y = $exp[1];
      $month_crr = $M.'/'.$Y;  
    }
    // else{
    //   $M = $date_arr[1];
    //   $Y =  $date_arr[0];
    //   $month_crr = $M.'/'.$Y;  
    // }
	
	global $base_url;

	$monthcrr = str_replace("/", "-", $month_crr);
	$prev_month_ts = strtotime('01-'.$monthcrr.' -1 month');
	$next_month_ts = strtotime('01-'.$monthcrr.' +1 month');
	$getlastmonth=date('m/Y',$prev_month_ts);
	$getnextmonth=date('m/Y',$next_month_ts);
	
	if(isset($_GET['MonthView'])){
		$valueinfo=$_GET['MonthView'];
		global $base_url;
		//print '01/'.$month_crr;die;
		$monthcrr = str_replace("/", "-", $month_crr);
		$prev_month_ts = strtotime('01-'.$monthcrr.' -1 month');
		$next_month_ts = strtotime('01-'.$monthcrr.' +1 month');
		$getlastmonth=date('m/Y',$prev_month_ts);
		$getnextmonth=date('m/Y',$next_month_ts);
		//echo $getnextmonth;die;
		
		//print $getlastmonth;die;
		$getlastval='<a href="'.$base_url.'/upcoming-group-assessments?MonthView='.$getlastmonth.'"><i class="fal fa-angle-left mr-2"></i></a>';
		$getnextval='<a href="'.$base_url.'/upcoming-group-assessments?MonthView='.$getnextmonth.'"><i class="fal fa-angle-right ml-2"></i></a>';
	}else{
		$getlastval='<a href="'.$base_url.'/upcoming-group-assessments?MonthView='.$getlastmonth.'"><i class="fal fa-angle-left mr-2"></i></a>';
		$getnextval='<a href="'.$base_url.'/upcoming-group-assessments?MonthView='.$getnextmonth.'"><i class="fal fa-angle-right ml-2"></i></a>';
	}
    $form['MonthView'] = [
      '#type' => 'select',
      '#options' => $month,
      '#default_value' => $month_crr,
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


  // public function get_assessment_data(){
  //     $s =  array( 
  //         array(
  //         'title' => 'All Day Event1',
  //         'start' => '2020-02-01'
  //       ),
  //     array(
  //       'title' => 'Long Event1',
  //       'start' => '2020-02-07', 
  //     ),
  //     array(
  //       'title' => 'Repeating Event1111',
  //       'start' => '2020-02-09'
  //     ),
  //     array(
  //       'title' => 'Repeating Event',
  //       'start' => '2020-02-16'
  //     ),
  //   );
  //     // $title = [];
  //     // foreach ($arr_variable as $key => $value) {
  //     //   $title[] = [
  //     //     'title' => $value,
  //     //   ];
  //     // }
  //     $data = json_encode($s);
  //     return $data;
  // }

}// class close 