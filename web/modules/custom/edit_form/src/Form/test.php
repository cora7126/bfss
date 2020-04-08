<?php
/**
 * @file
 * Contains \Drupal\edit_form\Form\ContributeForm.
 */

namespace Drupal\edit_form\Form;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Component\Utility\UrlHelper;
use Drupal\Core\Database\Database;
use Drupal\file\Entity\File;

/**
 * Contribute form.
 */
class test extends FormBase {
  /**
   * {@inheritdoc}
   */
  public function getFormId() {
     return 'edit_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
  	$current_user = \Drupal::currentUser()->id();
    $roles_user = \Drupal::currentUser()->getRoles();
    $query18 = \Drupal::database()->select('mydata', 'md');
    $query18->fields('md');
    $query18->condition('uid', $current_user, '=');
    $results18 = $query18->execute()->fetchAssoc();
    //print_r($results18);

    $conn = Database::getConnection();
    $query1 = \Drupal::database()->select('user__field_first_name', 'ufln');
        $query1->addField('ufln', 'field_first_name_value');
        $query1->condition('entity_id', $current_user,'=');
        $results1 = $query1->execute()->fetchAssoc(); 
	$query2 = \Drupal::database()->select('user__field_last_name', 'ufln2');
        $query2->addField('ufln2', 'field_last_name_value');
        $query2->condition('entity_id', $current_user,'=');
        $results2 = $query2->execute()->fetchAssoc();
	$query3 = \Drupal::database()->select('user__field_date', 'ufln3');
        $query3->addField('ufln3', 'field_date_value');
        $query3->condition('entity_id', $current_user,'=');
        $results3 = $query3->execute()->fetchAssoc();
	$query4 = \Drupal::database()->select('users_field_data', 'ufln4');
        $query4->fields('ufln4');
        $query4->condition('uid', $current_user,'=');
        $results4 = $query4->execute()->fetchAssoc();
	$query5 = \Drupal::database()->select('user__field_mobile', 'ufm');
        $query5->addField('ufm', 'field_mobile_value');
        $query5->condition('entity_id', $current_user,'=');
        $results5 = $query5->execute()->fetchAssoc();
	$query6 = \Drupal::database()->select('user__field_mobile_2', 'ufm2');
        $query6->addField('ufm2', 'field_mobile_2_value');
        $query6->condition('entity_id', $current_user,'=');
        $results6 = $query6->execute()->fetchAssoc();
	$query_img = \Drupal::database()->select('user__user_picture', 'n');
        $query_img->addField('n', 'user_picture_target_id');
        $query_img->condition('entity_id', $current_user,'=');
        $results = $query_img->execute()->fetchAssoc();
	$img_id = $results['user_picture_target_id'];
        $file = File::load($img_id);
		if(!empty($file)){
			$url = $file->url();
		}


  

     //  $form['#prefix'] = '<div class="bfssAthleteProfile athlete_edit_class">';
     // $form['#suffix'] = '</div>';
    $form['username'] = array(
      '#type' => 'textfield',
      '#required' => TRUE,
      '#default_value' => $results4['name'],
	  '#prefix'=>'<div class="left_section popup_left_section"><div class="athlete_left"><h3><div class="toggle_icon"><i class="fa fa-minus"></i><i class="fa fa-plus hide"></i></div>Login Information</h3><div class=items_div>',
	  '#attributes' => array('disabled'=>true),
      );
    if(in_array('assessors', $roles_user)){
      $hd_title = "ASSESSORS&#39;s Information";
    }elseif(in_array('coach', $roles_user)){
      $hd_title = "COACHES&#39;s Information";
    }else{
      $hd_title = "ATHLETE&#39;s Information"; 
    }
	$form['email'] = array(
      '#type' => 'textfield',
      '#placeholder' => t('Email'),
      '#required' => TRUE,
      '#default_value' => $results4['mail'],
	  '#prefix' => '',
	  '#suffix' => '<a class="change_pass" id="change_id" href="javascript:void(0)">Change Password</a></div></div><div class="athlete_left"><h3><div class="toggle_icon"><i class="fa fa-minus"></i><i class="fa fa-plus hide"></i></div>'.$hd_title.'</h3><div class=items_div>',
      );
        $form['fname'] = array(
      '#type' => 'textfield',
      '#default_value' => $results1['field_first_name_value'],
      '#attributes' => array('disabled'=>true),
	  
      );
	  $form['lname'] = array(
      '#type' => 'textfield',
     // '#title' => t('Mobile Number:'),
      // '#placeholder' => t('Bloggs'),
      '#default_value' => $results2['field_last_name_value'],
	  '#attributes' => array('disabled'=>true),
      );

    if(!in_array('assessors', $roles_user)){
        $form['numberone'] = array(
            '#type' => 'textfield',
            '#placeholder' => 'Phone Number',
            #'#required' => TRUE,
             '#default_value' => $results5['field_mobile_value'],
          ); 
        if(!in_array('coach', $roles_user)){
          $form['date_joined'] = array (
            '#type' => 'textfield',
            '#placeholder' => t('Date joined'),
            '#suffix' => '</div></div>',
            '#default_value' => substr($results3['field_date_value'],0,10),
            // '#attributes' => array('disabled'=>true),
            '#attributes' => array('id' => array('datepicker')),
          );
        }
    }
    // for coach
      if(in_array('coach', $roles_user)){
      $states = $this->getStates();
      $form['az'] = array(
        //'#title' => t('az'),
        '#type' => 'select',
        //'#description' => 'Select the desired pizza crust size.',
      '#options'=>$states,
      '#default_value' => $city,
      #'#required' => TRUE,
        );
      $form['city'] = array(
        '#type' => 'textfield',
        //'#title' => t('City'),
        # '#required' => TRUE,
        '#placeholder' => t('City'),
        '#default_value' => $results18['field_city'],
      );
      $gender_arr =  array('' => 'Select Gender','male' => 'Male','female' => 'Female','other' => 'Other');
      $form['sextype'] = array(
      '#type' => 'select',
       #'#title' => t('City'),
      #'#required' => TRUE,
      '#suffix' => '</div></div>',
      '#options' => $gender_arr,
      '#default_value' => $results18['field_birth_gender'],
      #'#attributes' => array('readonly' => 'readonly'),
      );
    }

 
    $orgtype = array(
      ""=>t('Organization Type'),
      "1"=>t('School'),
      "2"=>t('Club'),
      "3"=>t('University'));
    $form['organizationType'] = array(
      //'#title' => t('az'),
      '#type' => 'select',
      //'#description' => 'Select the desired pizza crust size.',
     '#required' => TRUE,
      '#options' => $orgtype,
      '#prefix' => '<div class="athlete_school bfssAthleteProfile"><div class = "athlete_left"><h3><div class="toggle_icon"><i class="fa fa-minus"></i><i class="fa fa-plus hide"></i></div>School/Club/University</h3><div class=items_div>',
      '#default_value' => $orgtype_1,
      );
      
    
    $form['organizationName'] = array(
      '#type' => 'textfield',
      '#placeholder' => t('Orginization Name'),
      //'#description' => 'Select the desired pizza crust size.',
       '#required' => TRUE,
      //'#default_value' => array_search($results5['athlete_school_name'], $orgname),
      '#default_value' => $orgname_1,
      );
      
      
    $form['coach'] = array(
      '#type' => 'textfield',
      '#placeholder' => t("Coach Title"),
      '#default_value' => $orgcoach_1,
      );
   
    $form['sport'] = array(
    '#type' => 'textfield',
    '#placeholder' => t('Sport'),
    '#options'=>$sports_arr,
    '#default_value' => $orgsport_1_id,
    '#suffix' => '</div>',
      );

    // $form['position'] = array(
    //   '#type' => 'textfield',
    //   '#placeholder' => t('Position'),
    //   '#default_value' => $orgpos_1,
    //   '#prefix' => '',
    //   '#suffix' => '',
    //   );

    // if ($orgpos2_1=='') {
    //   $form['position2'] = array(
    //     '#type' => 'textfield',
    //     '#placeholder' => t('Position'),
    //     '#attributes' => array('style' => 'display:none'),
    //     // '#prefix' => '<div class ="pos_first_1"',
    //     // '#suffix' => '</div>',
    //     );
    // } else {
    //   $form['position2'] = array(
    //     '#type' => 'textfield',
    //     '#placeholder' => t('Position'),
    //     '#default_value' => $orgpos2_1,
    //     '#attributes' => array('class' => 'pos_show_first_1', 'style' => 'display:block'),
    //     // '#prefix' => '<div class =pos_first_1',
    //     // '#suffix' => '</div>',
    //     );
    // }

    // if ($orgpos3_1=='') {
    //   $form['position3'] = array(
    //     '#type' => 'textfield',
    //     '#placeholder' => t('Position'),
    //     '#attributes' => array('class' => 'pos_hidden_first_2', 'style' => 'display:none'),
    //     // '#prefix' => '<div class ="pos_first_2"',
    //     // '#suffix' => '</div>',
    //     );
    // } else {
    //   $form['position3'] = array(
    //     '#type' => 'textfield',
    //     '#placeholder' => t('Position'),
    //     '#default_value' => $orgpos3_1,
    //     '#attributes' => array('class' => 'pos_show_first_2', 'style' => 'display:block'),
    //     // '#prefix' => '<div class =pos_first_2',
    //     // '#suffix' => '</div>',
    //     );
    // }

    // $form['stats'] = array(
    //   '#type' => 'textarea',
    //   '#placeholder' => t('Add all personal stats'),
    //   '#prefix' => '<a class="add_pos_first"><i class="fa fa-plus"></i>Add Position</a><a class="remove_pos_first"><i class="fa fa-trash"></i>Remove Position</a>',
    //   '#suffix' => '',
    //   '#default_value' => $orgstats_1,
    //   );

    /*Add another organization 1 start*/
  //print '<pre>';print_r($results12);die;
  //print $results12['athlete_uni_type'];die;
      // for type 2
    
    if ($orgtype_2!='') { //uni
  //print '<pre>';print_r($results12);die;
  //print $results12['athlete_uni_type'];die;
      $orgtype2 =  array(
      ""=>t('Organization Type'),
      "1"=>t('School'),
      "2"=>t('Club'),
      "3"=>t('University'));
      $form['education_1'] = array( // uni
        '#type' => 'select',
        //'#description' => 'Select the desired pizza crust size.',
        '#options' => $orgtype2,
        '#prefix' => '</div><div class="org_notempty"><div class = "athlete_left"><h3><div class="toggle_icon"><i class="fa fa-minus"></i><i class="fa fa-plus hide"></i></div>School/Club/University</h3><i id="athlete_uni" class="fa fa-trash right-icon delete_icon" aria-hidden="true"></i><div class=items_div>',
        '#default_value' => $orgtype_2,
        );
      /*$orgname2 = array(
        t('Organization Name'),
        t('Organization Name 1'),
        t('Organization Name 2'),
        t('Organization Name 3'));*/
      $form['schoolname_1'] = array(
        //'#title' => t('az'),
        '#type' => 'textfield',
    '#placeholder' => t('Orginization Name'),
        //'#description' => 'Select the desired pizza crust size.',
      /*  '#options' => array(
          t('Organization Name'),
          t('Organization Name 1'),
          t('Organization Name 2'),
          t('Organization Name 3')),*/
        //'#default_value' => array_search($results12['athlete_uni_name'], $orgname2),
        '#default_value' => $orgname_2,
        );
      $form['coach_1'] = array(
        '#type' => 'textfield',
        '#placeholder' => t("Coache Title"),
        '#default_value' => $orgcoach_2,
        );
      $form['sport_1'] = array(
        '#type' => 'select',
        //'#placeholder' => t('Sport'),
    '#options'=>$sports_arr,
        '#default_value' => $orgsport_2_id,
        );
      $form['position_1'] = array(
        '#type' => 'textfield',
        '#placeholder' => t('Position'),
        '#default_value' => $orgpos_2,
        '#prefix' => '<div class="add_pos_div_second">',
        '#suffix' => '',
        );
      // if ($orgpos2_2=='') {
      //   $form['position_12'] = array(
      //     '#type' => 'textfield',
      //     '#placeholder' => t('Position'),
      //     '#default_value' => $orgpos2_2,
      //     '#attributes' => array('class' => 'pos_hidden_second_1', 'style' => 'display:none'),
      //     // '#prefix' => '<div class ="pos_hidden_first_1 hidpos"',
      //     // '#suffix' => '</div>',
      //     );
      // } else {
      //   $form['position_12'] = array(
      //     '#type' => 'textfield',
      //     '#placeholder' => t('Position'),
      //     '#default_value' => $orgpos2_2,
      //     '#attributes' => array('class' => 'pos_show_first_1', 'style' => 'display:block'),
      //     // '#prefix' => '<div class =pos_show_first_1',
      //     // '#suffix' => '</div>',
      //     );
      // }
      // if ($orgpos3_2=='') {
      //   $form['position_13'] = array(
      //     '#type' => 'textfield',
      //     '#placeholder' => t('Position'),
      //     '#default_value' => $orgpos3_2,
      //     '#attributes' => array('class' => 'pos_hidden_first_2', 'style' => 'display:none'),
      //     // '#prefix' => '<div class ="pos_hidden_first_2 hidpos"',
      //     // '#suffix' => '</div>',
      //     );
      // } else {
      //   $form['position_13'] = array(
      //     '#type' => 'textfield',
      //     '#placeholder' => t('Position'),
      //     '#default_value' => $orgpos3_2,
      //     '#attributes' => array('class' => 'pos_show_first_2', 'style' => 'display:block'),
      //     // '#prefix' => '<div class =pos_show_first_2',
      //     // '#suffix' => '</div>',
      //     );
      // }
      $form['stats_1'] = array(
        '#type' => 'textarea',
        '#placeholder' => t('Add all personal stats'),
        '#prefix' => '<a class="add_pos_second"><i class="fa fa-plus"></i>Add Position</a><a class="remove_pos_second"><i class="fa fa-trash"></i>Remove Position</a></div>',
        '#suffix' => '</div></div>',
        '#default_value' => $orgstats_2,
        );
    } else {

      $orgtype2 = array(
      ""=>t('Organization Type'),
      "1"=>t('School'),
      "2"=>t('Club'),
      "3"=>t('University'));
      $form['education_1'] = array( //uni
        //'#title' => t('az'),
        '#type' => 'select',
        //'#description' => 'Select the desired pizza crust size.',
        '#options' => $orgtype2,
        '#prefix' => '</div><div class="athlete_school popup-athlete-school-hide previous_athlete" style="display:none;"><div class = "athlete_left"><h3><div class="toggle_icon"><i class="fa fa-minus"></i><i class="fa fa-plus hide"></i></div>School/Club/University</h3><i class="fa fa-trash right-icon delete_icon previous_delete" aria-hidden="true"></i><div class=items_div>',
        // '#default_value' => array_search($results12['athlete_uni_type'],$orgtype2),

        );
      /*$orgname2 = array(
        t('Organization Name'),
        t('Organization Name 1'),
        t('Organization Name 2'),
        t('Organization Name 3'));*/
      $form['schoolname_1'] = array(
        //'#title' => t('az'),
        '#type' => 'textfield',
    '#placeholder' => t('Orginization Name'),
        //'#description' => 'Select the desired pizza crust size.',
        /*'#options' => array(
          t('Organization Name'),
          t('Organization Name 1'),
          t('Organization Name 2'),
          t('Organization Name 3')),*/
        // '#default_value' => array_search($results12['athlete_uni_name'],$orgname2),
        '#default_value' => $results12['athlete_uni_name'],

        );
      $form['coach_1'] = array(
        '#type' => 'textfield',
        '#placeholder' => t("Coache's Last Name"),
        // '#default_value' => $results12['athlete_uni_coach'],
        );
      $form['sport_1'] = array(
        '#type' => 'textfield',
        '#options'=>$sports_arr,
        '#placeholder' => t('Sport'),
        '#prefix' => '<div class="add_pos_div_second"></div>',
        '#suffix' => '</div></div>',
        // '#default_value' => $results12['athlete_uni_sport'],
        );
      // $form['position_1'] = array(
      //   '#type' => 'textfield',
      //   '#placeholder' => t('Position'),
      //   // '#default_value' => $results12['athlete_uni_pos'],
      //   '#prefix' => '<div class="add_pos_div_second">',
      //   '#suffix' => '',
      //   );
      // if (empty($results6['athlete_uni_pos2'])) {
      //   $form['position_12'] = array(
      //     '#type' => 'textfield',
      //     '#placeholder' => t('Position'),
      //     // '#default_value' => $results6['athlete_uni_pos2'],
      //     '#attributes' => array('class' => 'pos_hidden_second_1', 'style' => 'display:none'),
      //     // '#prefix' => '<div class ="pos_hidden_first_1 hidpos"',
      //     // '#suffix' => '</div>',
      //     );
      // } else {
      //   $form['position_12'] = array(
      //     '#type' => 'textfield',
      //     '#placeholder' => t('Position'),
      //     // '#default_value' => $results6['athlete_uni_pos2'],
      //     '#attributes' => array('class' => 'pos_show_first_1', 'style' => 'display:block'),
      //     // '#prefix' => '<div class =pos_show_first_1',
      //     // '#suffix' => '</div>',
      //     );
      // }
      // if (empty($results6['athlete_uni_pos3'])) {
      //   $form['position_13'] = array(
      //     '#type' => 'textfield',
      //     '#placeholder' => t('Position'),
      //     // '#default_value' => $results6['athlete_uni_pos3'],
      //     '#attributes' => array('class' => 'pos_hidden_first_2', 'style' => 'display:none'),
      //     // '#prefix' => '<div class ="pos_hidden_first_2 hidpos"',
      //     // '#suffix' => '</div>',
      //     );
      // } else {
      //   $form['position_13'] = array(
      //     '#type' => 'textfield',
      //     '#placeholder' => t('Position'),
      //     // '#default_value' => $results6['athlete_uni_pos3'],
      //     '#attributes' => array('class' => 'pos_show_first_2', 'style' => 'display:block'),
      //     // '#prefix' => '<div class =pos_show_first_2',
      //     // '#suffix' => '</div>',
      //     );
      // }
      // $form['stats_1'] = array(
      //   '#type' => 'textarea',
      //   '#placeholder' => t('Add all personal stats'),
      //   '#prefix' => '<a class="add_pos_second"><i class="fa fa-plus"></i>Add Position</a><a class="remove_pos_second"><i class="fa fa-trash"></i>Remove Position</a></div>',
      //   '#suffix' => '</div></div>',
      //   // '#default_value' => $results12['athlete_uni_stat'],
      //   );
    }
    

    /*Add another organization 1 END*/
    /*Add another organization 1 start*/
  
      if ($orgtype_3!='') {
      $unitype = array(
      ""=>t('Organization Type'),
      "1"=>t('School'),
      "2"=>t('Club'),
      "3"=>t('University'));
      $form['education_2'] = array(
        '#type' => 'select',
        '#options' => $unitype,
        '#prefix' => '<div class = "athlete_left"><h3><div class="toggle_icon"><i class="fa fa-minus"></i><i class="fa fa-plus hide"></i></div>School/Club/University</h3><i id="athlete_club" class="fa fa-trash right-icon delete_icon" aria-hidden="true"></i><div class=items_div>',
        '#default_value' => $orgtype_3,
        );
      /*$uniname = array(
        t('Organization Name'),
        t('Organization Name 1'),
        t('Organization Name 2'),
        t('Organization Name 3'));*/
      $form['schoolname_2'] = array(
        '#type' => 'textfield',
    '#placeholder' => t('Orginization Name'),
        /*'#options' => array(
          t('Organization Name'),
          t('Organization Name 1'),
          t('Organization Name 2'),
          t('Organization Name 3')),*/
        '#default_value' => $orgname_3,
        );
      $form['coach_2'] = array(
        '#type' => 'textfield',
        '#placeholder' => t("Coache Title"),
        '#default_value' => $orgcoach_3,
        );
      $form['sport_2'] = array(
        '#type' => 'textfield',
        '#options'=>$sports_arr,
        '#placeholder' => t('Sport'),
        '#default_value' => $orgsport_3_id,
        );
      // $form['position_2'] = array(
      //   '#type' => 'textfield',
      //   '#placeholder' => t('Position'),
      //   '#default_value' => $orgpos_3,
      //   '#prefix' => '<div class="add_pos_div_third">',
      //   '#suffix' => '</div>',
      //   );

      // if ($orgpos2_3=='') {
      //   $form['position_22'] = array(
      //     '#type' => 'textfield',
      //     '#placeholder' => t('Position'),
      //     '#default_value' => $orgpos2_3,
      //     '#attributes' => array('class' => 'pos_hidden_third_1', 'style' => 'display:none'),
      //     // '#prefix' => '<div class ="pos_hidden_first_1 hidpos"',
      //     // '#suffix' => '</div>',
      //     );
      // } else {
      //   $form['position_22'] = array(
      //     '#type' => 'textfield',
      //     '#placeholder' => t('Position'),
      //     '#default_value' => $orgpos2_3,
      //     '#attributes' => array('class' => 'pos_show_first_1', 'style' => 'display:block'),
      //     // '#prefix' => '<div class =pos_show_first_1',
      //     // '#suffix' => '</div>',
      //     );
      // }
      // if ($orgpos3_3=='') {
      //   $form['position_23'] = array(
      //     '#type' => 'textfield',
      //     '#placeholder' => t('Position'),
      //     '#default_value' => $orgpos3_3,
      //     '#attributes' => array('class' => 'pos_hidden_first_2', 'style' => 'display:none'),
      //     // '#prefix' => '<div class ="pos_hidden_first_2 hidpos"',
      //     // '#suffix' => '</div>',
      //     );
      // } else {
      //   $form['position_23'] = array(
      //     '#type' => 'textfield',
      //     '#placeholder' => t('Position'),
      //     '#default_value' => $orgpos3_3,
      //     '#attributes' => array('class' => 'pos_show_first_2', 'style' => 'display:block'),
      //     // '#prefix' => '<div class =pos_show_first_2',
      //     // '#suffix' => '</div>',
      //     );
      // }
      // $form['stats_2'] = array(
      //   '#type' => 'textarea',
      //   '#placeholder' => t('Add all personal stats'),
      //   '#suffix' => '</div></div></div><a class="add_org popup_add_org"><i class="fa fa-plus"></i>Add Another Organization</a></div>',
      //   '#default_value' => $orgstats_3,
      //   '#prefix' => '<a class="add_pos_third"><i class="fa fa-plus"></i>Add Position</a><a class="remove_pos_third"><i class="fa fa-trash"></i>Remove Position</a></div>',
      //   );
    } else {
    $unitype = array(
      ""=>t('Organization Type'),
      "1"=>t('School'),
      "2"=>t('Club'),
      "3"=>t('University'));
      $form['education_2'] = array( // club
        //'#title' => t('az'),
        '#type' => 'select',
        //'#description' => 'Select the desired pizza crust size.',
        '#options' => $unitype,
        '#prefix' => '</div><div class="athlete_school popup-athlete-school-hide last_athlete"><div class = "athlete_left"><h3><div class="toggle_icon"><i class="fa fa-minus"></i><i class="fa fa-plus hide"></i></div>School/Club/University</h3><i class="fa fa-trash right-icon delete_icon last_delete" aria-hidden="true"></i><div class=items_div>',
        );

      $form['schoolname_2'] = array(
        //'#title' => t('az'),
        '#type' => 'textfield',
    '#placeholder' => t('Orginization Name'),
        //'#description' => 'Select the desired pizza crust size.',
        /*'#options' => array(
          t('Organization Name'),
          t('Organization Name 1'),
          t('Organization Name 2'),
          t('Organization Name 3')),*/
        );
      $form['coach_2'] = array(
        '#type' => 'textfield',
        '#placeholder' => t("Coache's Last Name"),
        '#default_value' => '',
        );
      $form['sport_2'] = array(
        '#type' => 'textfield',
        '#placeholder' => t('Sport'),
        '#options'=>$sports_arr,
        '#default_value' => '',
         '#prefix' => '<div class="add_pos_div_third">',
        '#suffix' => '</div></div></div></div><a class="add_org popup_add_org"><i class="fa fa-plus"></i>Add Another Organization</a></div>',
        );
      

      // $form['stats_2'] = array(
      //   '#type' => 'textarea',
      //   '#placeholder' => t('Add all personal stats'),
      //    '#prefix' => '</div>',
      //   '#suffix' => '',
      //   '#default_value' => '',
       
      //   );
    }
    /*Add another organization 1 END*/

$form['submit'] = ['#type' => 'submit', '#value' => 'save', '#prefix' => '<div id="athlete_submit">','#suffix' => '</div></div>
<div class ="right_section">
<div class = "athlete_right">
                <h3><div class="toggle_icon"><i class="fa fa-minus"></i><i class="fa fa-plus hide"></i></div>Profile Photo</h3>
       <div class="edit_dropdown"><a class="drop" >Action<span class="down-arrow fa fa-angle-down"></span></a>
        <ul class="dropdown-menu" style="padding:0"></ul>
       </div>
<div class=items_div>
<img src='.$url.' class="edit-profile-image" >
<div class="popupimage" id="imagepopup">
 <div class="popup_header">
       <h3>Profile Photo <i class="fa fa-times right-icon imagepopup-modal-close spb_close" aria-hidden="true"></i></h3>
  </div>',
//'#value' => t('Submit'),
];

$form['image_athlete'] = [
'#type' => 'managed_file',
'#upload_validators' => ['file_validate_extensions' => ['gif png jpg jpeg'],
'file_validate_size' => [25600000], ],
'#theme' => 'image_widget',
'#preview_image_style' => 'medium', 
'#upload_location' => 'public://',
'#required' => false,
'#default_value' => array($img_id), 
'#prefix' => '</div></div>', 
'#suffix' => '<div class="action_bttn">
                <span>Action</span><ul><li>Remove</li></ul>
              </div>
</div></div>',
 ];

$form['school_web'] = array(
  '#type' => 'textfield',
  '#placeholder' => t('School'),
  '#default_value' => $orgname_1,
  '#attributes' => array('disabled' => true),
  '#prefix' => '
  </div></div>
  <div class = "athlete_right edit-user-display">
  <h3><div class="toggle_icon"><i class="fa fa-minus"></i><i class="fa fa-plus hide"></i></div>My Website</h3>
  <div class=items_div>',
  );




    $form['sport_web'] = array(
      '#type' => 'textfield',
      '#placeholder' => t('Sport'),
      '#attributes' => array('disabled' => true),
      '#default_value' => $orgsport_1,
      );
   // print '<pre>';print_r($results13);die;
    $form['name_web'] = array(
      '#type' => 'textfield',
      '#placeholder' => t('Pick a Name'),
      '#default_value' => $deltaweb0,
      '#prefix' => '<div class="container-inline web_name webfield">',
      '#suffix' => '</div>',
      '#attributes' => array('id' => 'name_1'),
      );
    $form['label_1'] = array(
      '#type' => 'label',
      '#title' => ' http://bfsscience.com/users/',
      '#attributes' => array('id' => 'label_1', 'class' => array('weblabel')),
      );
    $form['label_2'] = array(
      '#type' => 'label',
      '#title' => 'Create your unique website profile.<br> eg: http://bfsscience.com/users/jodibloggs<br> Once published , this will become your permanent address and it can not be changed.<br>',
      );
    $form['preview_1'] = array(
      '#type' => 'markup',
      '#markup' => render($link),
      '#prefix' => "<div class='previewdiv' data-id='1'>",
      '#suffix' => "</div>",
      // '#type' => 'button',
      // '#default_value' => 'Preview Changes',
      );
    $form['web_visible_1'] = array(
      '#type' => 'select',
      '#options' => array(
        t('Website Visibility'),
        t('On'),
        t('Off')),
      '#default_value' => $deltaweb0_visibility,
      '#suffix' => '',
      );
    if ($orgtype_2!='') {
      $form['school_web2'] = array(
        '#type' => 'textfield',
        '#placeholder' => t('School'),
        '#default_value' => $orgname_2,
        '#attributes' => array('disabled' => true),
        '#prefix' => '</div></div><div class = "athlete_right edit-user-display"><h3><div class="toggle_icon"><i class="fa fa-minus"></i><i class="fa fa-plus hide"></i></div>Additional Website</h3><div class=items_div>',
        );
      $form['sport_web2'] = array(
        '#type' => 'textfield',
        '#placeholder' => t('Sport'),
        '#default_value' => $orgsport_2,
        '#attributes' => array('disabled' => true),
        );
      $form['name_web2'] = array(
        '#type' => 'textfield',
        '#placeholder' => t('Pick a Name'),
        '#default_value' => $deltaweb1,
        '#prefix' => '<div class="container-inline web_name webfield">',
        '#suffix' => '</div>',
        '#attributes' => array('id' => 'name_2'),
        );
      $form['label_12'] = array(
        '#type' => 'label',
        '#title' => ' http://bfsscience.com/users/',
        '#attributes' => array('id' => 'label_2', 'class' => array('weblabel')),
        );
      $form['label_22'] = array(
        '#type' => 'label',
        '#title' => 'Create your unique website profile.<br> eg: http://bfsscience.com/users/jodibloggs<br> Once published , this will become your permanent address and it can not be changed.<br>',
        );
      $form['preview_12'] = array(
        '#type' => 'markup',
        '#markup' => render($link),
        '#prefix' => "<div class='previewdiv' data-id='2'>",
        '#suffix' => "</div>",
        // '#type' => 'button',
        // '#default_value' => 'Preview Changes',
        );
      $form['web_visible_2'] = array(
        '#type' => 'select',
        '#options' => array(
          t('Website Visibility'),
          t('on'),
          t('off')),
        '#default_value' => $deltaweb1_visibility,
        '#suffix' => '</div></div>',
        );
    }
    if ($orgtype_3!='') {
      $form['school_web3'] = array(
        '#type' => 'textfield',
        '#placeholder' => t('School'),
        '#default_value' => $orgname_3,
        '#attributes' => array('disabled' => true),
        '#prefix' => '<div class = "athlete_right"><h3><div class="toggle_icon"><i class="fa fa-minus"></i><i class="fa fa-plus hide"></i></div>Additional Website</h3><div class=items_div>',
        );
      $form['sport_web3'] = array(
        '#type' => 'textfield',
        '#placeholder' => t('Sport'),
        '#default_value' => $orgsport_3,
        '#attributes' => array('disabled' => true),
        );
      $form['name_web3'] = array(
        '#type' => 'textfield',
        '#placeholder' => t('Pick a Name'),
        '#default_value' => $deltaweb2,
        '#prefix' => '<div class="container-inline web_name webfield">',
        '#suffix' => '</div>',
        '#attributes' => array('id' => 'name_2'),
        );
      $form['label_13'] = array(
        '#type' => 'label',
        '#title' => 'http://bfsscience.com/users/',
        '#attributes' => array('id' => 'label_2', 'class' => array('weblabel')),
        );
      $form['label_23'] = array(
        '#type' => 'label',
        '#title' => 'Create your unique website profile.<br> eg: http://bfsscience.com/users/jodibloggs<br> Once published , this will become your permanent address and it can not be changed.<br>',
        );
      $form['preview_13'] = array(
        '#type' => 'markup',
        '#markup' => render($link),
        '#prefix' => "<div class='previewdiv' data-id='3'>",
        '#suffix' => "</div>",
        // '#type' => 'button',
        // '#default_value' => 'Preview Changes',
        );
      $form['web_visible_3'] = array(
        '#type' => 'select',
        '#options' => array(
          t('Website Visibility'),
          t('on'),
          t('off')),
        '#default_value' => $deltaweb2_visibility,
        '#suffix' => '</div></div></div>',
        );
    }
    // $form['#theme'] = 'athlete_form';
    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
   if (!$form_state->getValue('email') || !filter_var($form_state->getValue('email'), FILTER_VALIDATE_EMAIL)) {
        $form_state->setErrorByName('email', $this->t('Please enter a valid email.'));
    }
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $current_user = \Drupal::currentUser()->id();
    $roles_user = \Drupal::currentUser()->getRoles();
    $conn = Database::getConnection();

    //user profile 
	  $query_pic = \Drupal::database()->select('user__user_picture', 'uup');
		$query_pic->fields('uup');
		$query_pic->condition('entity_id', $current_user,'=');
		$results_pic = $query_pic->execute()->fetchAll();	
                $imgid = $form_state->getValue('image_athlete');
				if(empty($results_pic)){
					 $conn->insert('user__user_picture')->fields(
							array(
							'entity_id' => $current_user,
							'bundle' => 'user',
							'deleted' => '0',
							'revision_id' => $current_user,
							'langcode' => 'en',
							'delta' => '0',
							'user_picture_target_id' => $imgid[0],
							)
					)->execute();
				}else {
					if(!empty($imgid[0])){
						$conn->update('user__user_picture')
						->condition('entity_id',$current_user,'=')
						->fields(
							array(
							'user_picture_target_id' => $imgid[0],
							)
						)
						->execute();
					}else{
						$conn->update('user__user_picture')
						->condition('entity_id',$current_user,'=')
						->fields(
							array(
							'user_picture_target_id' => '240',
							)
						)
						->execute();
					}
                
			}
				
		//joining date	
    if(!in_array('coach', $roles_user)){
  	  $query3 = \Drupal::database()->select('user__field_date', 'ufln3');
      $query3->addField('ufln3', 'field_date_value');
      $query3->condition('entity_id', $current_user, '=');
      $results3 = $query3->execute()->fetchAssoc();
    	$lang_code = \Drupal::languageManager()->getCurrentLanguage()->getId();
        if (empty($results3)) {
    		
            $conn->insert('user__field_date')->fields(array(
            'entity_id' => $current_user,
            'bundle' => 'user',
            'revision_id' => $current_user,
            'delta' => 0,
            'langcode' => $lang_code,
            'field_date_value' => $form_state->getValue('date_joined'),
            ))->execute();
        }
        else {
            $conn->update('user__field_date')->condition('entity_id', $current_user, '=')->fields(array(
            'field_date_value' => $form_state->getValue('date_joined'),
            ))->execute();
        }
      }


	  //mydata
    $query_mydata = \Drupal::database()->select('mydata', 'md');
    $query_mydata->fields('md');
    $query_mydata->condition('uid', $current_user, '=');
    $results_mydata = $query_mydata->execute()->fetchAll();
    if (empty($results_mydata)) {
        $conn->insert('mydata')->fields(array(
          'uid' => $current_user,
          'field_az' => $form_state->getValue('az'),
          'field_city' => $form_state->getValue('city'),
          'field_birth_gender' => $form_state->getValue('sextype'),
          ))->execute();
      } else {
        $conn->update('mydata')->condition('uid', $current_user, '=')->fields(array(
          'field_az' => $form_state->getValue('az'),
          'field_city' => $form_state->getValue('city'),
          'field_birth_gender' => $form_state->getValue('sextype'),
          ))->execute();
      } 
      if(in_array('coach', $roles_user)){
          //org
          $org_type1= $form_state->getValue('organizationType'); // school
          $org_type2= $form_state->getValue('education_1'); // club
          $org_type3= $form_state->getValue('education_2'); //uni

          /* for selection in Type 1 starts here ==== */
            if($org_type1==1){

              $conn->insert('athlete_school')->fields(array(
              'athlete_uid' => $current_user,
              'athlete_school_name' => $form_state->getValue('organizationName'),
              'athlete_school_coach' => $form_state->getValue('coach'),
              'athlete_school_sport' => $form_state->getValue('sport'),
              // 'athlete_school_pos' => $form_state->getValue('position'),
              // 'athlete_school_pos2' => $form_state->getValue('position2'),
              // 'athlete_school_pos3' => $form_state->getValue('position3'),
              // 'athlete_school_stat' => $form_state->getValue('stats'),
              'athlete_school_type' => $org_type1,
              ))->execute();
              
              $query_sch = \Drupal::database()->select('athlete_school', 'n');
              $query_sch->addField('n', 'id');
              $query_sch->condition('athlete_uid', $current_user, '=');
              $query_sch->orderBy('id', 'DESC');
              $query_sch->range(0, 1);
              $results = $query_sch->execute()->fetchAssoc();
              $id1 = $results['id'];
              
            }elseif($org_type1==2){
                  $conn->insert('athlete_club')->fields(array(
                    'athlete_uid' => $current_user,
                    'athlete_club_name' => $form_state->getValue('organizationName'),
                    'athlete_club_coach' => $form_state->getValue('coach'),
                    'athlete_club_sport' => $form_state->getValue('sport'),
                    // 'athlete_club_pos' => $form_state->getValue('position'),
                    // 'athlete_school_pos2' => $form_state->getValue('position2'),
                    // 'athlete_school_pos3' => $form_state->getValue('position3'),
                    // 'athlete_club_stat' => $form_state->getValue('stats'),
                    'athlete_school_type' => $org_type1,
                    ))->execute();
                
              
              $query_sch = \Drupal::database()->select('athlete_club', 'n');
              $query_sch->addField('n', 'id');
              $query_sch->condition('athlete_uid', $current_user, '=');
              $query_sch->orderBy('id', 'DESC');
              $query_sch->range(0, 1);
              $results = $query_sch->execute()->fetchAssoc();
              $id1 = $results['id'];
              
            }elseif($org_type1==3){
                $conn->insert('athlete_uni')->fields(array(
                  'athlete_uid' => $current_user,
                  'athlete_uni_name' => $form_state->getValue('organizationName'),
                  'athlete_uni_coach' => $form_state->getValue('coach'),
                  'athlete_uni_sport' => $form_state->getValue('sport'),
                  // 'athlete_uni_pos' => $form_state->getValue('position'),
                  // 'athlete_uni_pos2' => $form_state->getValue('position2'),
                  // 'athlete_uni_pos3' => $form_state->getValue('position3'),
                  // 'athlete_uni_stat' => $form_state->getValue('stats'),
                  'athlete_uni_type' => $org_type1,
                  ))->execute();
              
              $query_sch = \Drupal::database()->select('athlete_uni', 'n');
              $query_sch->addField('n', 'id');
              $query_sch->condition('athlete_uid', $current_user, '=');
              $query_sch->orderBy('id', 'DESC');
              $query_sch->range(0, 1);
              $results = $query_sch->execute()->fetchAssoc();
              $id1 = $results['id'];
              
            }
            /* for selection in Type 1 ends here ==== */
            
            /* for selection in Type 2 starts here ==== */
            if($org_type2==1){
              $conn->insert('athlete_school')->fields(array(
              'athlete_uid' => $current_user,
              'athlete_school_name' => $form_state->getValue('schoolname_1'),
              'athlete_school_coach' => $form_state->getValue('coach_1'),
              'athlete_school_sport' => $form_state->getValue('sport_1'),
              // 'athlete_school_pos' => $form_state->getValue('position_1'),
              // 'athlete_school_pos2' => $form_state->getValue('position_12'),
              // 'athlete_school_pos3' => $form_state->getValue('position_13'),
              // 'athlete_school_stat' => $form_state->getValue('stats_1'),
              'athlete_school_type' => $org_type2,
              ))->execute();
              
              $query_sch = \Drupal::database()->select('athlete_school', 'n');
              $query_sch->addField('n', 'id');
              $query_sch->condition('athlete_uid', $current_user, '=');
              $query_sch->orderBy('id', 'DESC');
              $query_sch->range(0, 1);
              $results = $query_sch->execute()->fetchAssoc();
              $id2 = $results['id'];
            }elseif($org_type2==2){
                  $conn->insert('athlete_club')->fields(array(
                    'athlete_uid' => $current_user,
                    'athlete_club_name' => $form_state->getValue('schoolname_1'),
                    'athlete_club_coach' => $form_state->getValue('coach_1'),
                    'athlete_club_sport' => $form_state->getValue('sport_1'),
                    // 'athlete_club_pos' => $form_state->getValue('position_1'),
                    // 'athlete_school_pos2' => $form_state->getValue('position_12'),
                    // 'athlete_school_pos3' => $form_state->getValue('position_13'),
                    // 'athlete_club_stat' => $form_state->getValue('stats_1'),
                    'athlete_school_type' => $org_type2,
                    ))->execute();
              $query_sch = \Drupal::database()->select('athlete_club', 'n');
              $query_sch->addField('n', 'id');
              $query_sch->condition('athlete_uid', $current_user, '=');
              $query_sch->orderBy('id', 'DESC');
              $query_sch->range(0, 1);
              $results = $query_sch->execute()->fetchAssoc();
              $id2 = $results['id'];
              
            }elseif($org_type2==3){
                $conn->insert('athlete_uni')->fields(array(
                  'athlete_uid' => $current_user,
                  'athlete_uni_name' => $form_state->getValue('schoolname_1'),
                  'athlete_uni_coach' => $form_state->getValue('coach_1'),
                  'athlete_uni_sport' => $form_state->getValue('sport_1'),
                  // 'athlete_uni_pos' => $form_state->getValue('position_1'),
                  // 'athlete_uni_pos2' => $form_state->getValue('position_12'),
                  // 'athlete_uni_pos3' => $form_state->getValue('position_13'),
                  // 'athlete_uni_stat' => $form_state->getValue('stats_1'),
                  'athlete_uni_type' => $org_type2,
                  ))->execute();
              $query_sch = \Drupal::database()->select('athlete_uni', 'n');
              $query_sch->addField('n', 'id');
              $query_sch->condition('athlete_uid', $current_user, '=');
              $query_sch->orderBy('id', 'DESC');
              $query_sch->range(0, 1);
              $results = $query_sch->execute()->fetchAssoc();
              $id2 = $results['id'];
            }
            /* for selection in Type 2 ends here ==== */
            
            /* for selection in Type 3 starts here ==== */
            if($org_type3==1){
              
              $conn->insert('athlete_school')->fields(array(
              'athlete_uid' => $current_user,
              'athlete_school_name' => $form_state->getValue('schoolname_2'),
              'athlete_school_coach' => $form_state->getValue('coach_2'),
              'athlete_school_sport' => $form_state->getValue('sport_2'),
              // 'athlete_school_pos' => $form_state->getValue('position_2'),
              // 'athlete_school_pos2' => $form_state->getValue('position_22'),
              // 'athlete_school_pos3' => $form_state->getValue('position_23'),
              // 'athlete_school_stat' => $form_state->getValue('stats_2'),
              'athlete_school_type' => $org_type3,
              ))->execute();
              $query_sch = \Drupal::database()->select('athlete_school', 'n');
              $query_sch->addField('n', 'id');
              $query_sch->condition('athlete_uid', $current_user, '=');
              $query_sch->orderBy('id', 'DESC');
              $query_sch->range(0, 1);
              $results = $query_sch->execute()->fetchAssoc();
              $id3 = $results['id'];
              
            }elseif($org_type3==2){
                  $conn->insert('athlete_club')->fields(array(
                    'athlete_uid' => $current_user,
                    'athlete_club_name' => $form_state->getValue('schoolname_2'),
                    'athlete_club_coach' => $form_state->getValue('coach_2'),
                    'athlete_club_sport' => $form_state->getValue('sport_2'),
                    // 'athlete_club_pos' => $form_state->getValue('position_2'),
                    // 'athlete_school_pos2' => $form_state->getValue('position_22'),
                    // 'athlete_school_pos3' => $form_state->getValue('position_23'),
                    // 'athlete_club_stat' => $form_state->getValue('stats_2'),
                    'athlete_school_type' => $org_type3,
                    ))->execute();
              
              $query_sch = \Drupal::database()->select('athlete_club', 'n');
              $query_sch->addField('n', 'id');
              $query_sch->condition('athlete_uid', $current_user, '=');
              $query_sch->orderBy('id', 'DESC');
              $query_sch->range(0, 1);
              $results = $query_sch->execute()->fetchAssoc();
              $id3 = $results['id'];
            }elseif($org_type3==3){
                $conn->insert('athlete_uni')->fields(array(
                  'athlete_uid' => $current_user,
                  'athlete_uni_name' => $form_state->getValue('schoolname_2'),
                  'athlete_uni_coach' => $form_state->getValue('coach_2'),
                  'athlete_uni_sport' => $form_state->getValue('sport_2'),
                  // 'athlete_uni_pos' => $form_state->getValue('position_2'),
                  // 'athlete_uni_pos2' => $form_state->getValue('position_22'),
                  // 'athlete_uni_pos3' => $form_state->getValue('position_23'),
                  // 'athlete_uni_stat' => $form_state->getValue('stats_2'),
                  'athlete_uni_type' => $org_type3,
                  ))->execute();
              $query_sch = \Drupal::database()->select('athlete_uni', 'n');
              $query_sch->addField('n', 'id');
              $query_sch->condition('athlete_uid', $current_user, '=');
              $query_sch->orderBy('id', 'DESC');
              $query_sch->range(0, 1);
              $results = $query_sch->execute()->fetchAssoc();
              $id3 = $results['id'];
            }
            /* for selection in Type 3 ends here ==== */
      }
		
    //mobile field
    $query = \Drupal::database()->select('user__field_mobile', 'ufm');
    $query->fields('ufm');
    $query->condition('entity_id', $current_user,'=');
    $results = $query->execute()->fetchAll(); 		
    if(empty($results)){
    $conn->insert('user__field_mobile')->fields(
            array(
            'entity_id' => $current_user,
            'field_mobile_value' => $form_state->getValue('numberone'),
            'bundle' => 'user',
            'deleted' => '0',
            'revision_id' => $current_user,
            'langcode' => 'en',
            'delta' => '0',
            )
    )->execute();
    }else{
        $conn->update('user__field_mobile')
        ->condition('entity_id',$current_user,'=')
        ->fields([
          'field_mobile_value' => $form_state->getValue('numberone'),
        ])
        ->execute();
    }

    //first name 
    $conn->update('user__field_first_name')
  	->condition('entity_id',$current_user,'=')
  	->fields([
  		'field_first_name_value' => $form_state->getValue('fname'),
  	])
  	->execute();

    //email
    $conn->update('users_field_data')
  	->condition('uid',$current_user,'=')
  	->fields([
  		'mail' => $form_state->getValue('email'),
  	])
  	->execute();

    
	   $form_state->setRedirect('acme_hello');
  }


  public function getStates() {
      return $st=array(
          'AL'=>  t('AL'),
          'AK'=>  t('AK'),
          'AZ'=>  t('AZ'),
          'AR'=> t('AR'),
          'CA'=>  t('CA'),
          'CO'=>   t('CO'),
          'CT'=>    t('CT'),
          'DE'=>    t('DE'),
          'DC'=>      t('DC'),
          'FL'=>    t('FL'),
          'GA'=>     t('GA'),
          'HI'=>     t('HI'),
          'ID'=>    t('ID'),
           'IL'=>   t('IL'),
           'IN'=> t('IN'),
           'IA'=> t('IA'),
          'KS'=>  t('KS'),
           'KY'=> t('KY'),
           'LA'=> t('LA'),
           'ME'=> t('ME'),
           'MT'=> t('MT'),
           'NE'=> t('NE'),
           'NV'=> t('NV'),
           'NH'=> t('NH'),
           'NJ'=> t('NJ'),
           'NM'=> t('NM'),
           'NY'=> t('NY'),
           'NC'=> t('NC'),
            'ND'=>t('ND'),
           'OH'=> t('OH'),
            'OR'=>t('OR'),
           'MD'=> t('MD'),
           'MA'=> t('MA'),
           'MI'=> t('MI'),
            'MN'=>t('MN'),
            'MS'=>t('MS'),
           'MO'=> t('MO'),
           'PA'=> t('PA'),
           'RI'=> t('RI'),
           'SC'=> t('SC'),
            'SD'=>t('SD'),
           'TN'=> t('TN'),
            'TX'=>  t('TX'),
             'UT'=> t('UT'),
            'VT'=>  t('VT'),
            'VA'=>  t('VA'),
             'WA'=> t('WA'),
             'WV'=> t('WV'),
            'WI'=>  t('WI'),
            'WY'=>  t('WY'));
    }
}
?>
