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
use Drupal\date_popup\DatePopup;
use Drupal\date_popup\DatetimePopup;

use Symfony\Component\HttpFoundation\RedirectResponse;

/**
 * Contribute form.
 */
class ContributeForm extends FormBase {
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
    #/preview/profile
    $url = \Drupal\Core\Url::fromRoute('bfss_assessment.preview_atheltic_profile');
    // print_r($url);die;
    $link = \Drupal\Core\Link::fromTextAndUrl($this->t('<span class="icon glyphicon glyphicon-eye-open" aria-hidden="true"></span> Preview Changes'), $url);

    $form['#attributes']['class'][] = 'edit_profile_form';

    if ($link) {
      $link = $link->toRenderable();
      $link['#attributes'] = ['target' => '__blank', 'class' => ['button', 'previewButton'], ];
    }

    $conn = Database::getConnection();
    $query1 = \Drupal::database()->select('user__field_first_name', 'ufln');
    $query1->addField('ufln', 'field_first_name_value');
    $query1->condition('entity_id', $current_user, '=');
    $results1 = $query1->execute()->fetchAssoc();
    $query2 = \Drupal::database()->select('user__field_last_name', 'ufln2');
    $query2->addField('ufln2', 'field_last_name_value');
    $query2->condition('entity_id', $current_user, '=');
    $results2 = $query2->execute()->fetchAssoc();
    $query3 = \Drupal::database()->select('user__field_date', 'ufln3');
    $query3->addField('ufln3', 'field_date_value');
    $query3->condition('entity_id', $current_user, '=');
    $results3 = $query3->execute()->fetchAssoc();
    $query4 = \Drupal::database()->select('users_field_data', 'ufln4');
    $query4->addField('ufln4', 'mail');
    $query4->condition('uid', $current_user, '=');
    $results4 = $query4->execute()->fetchAssoc();
    $query5 = \Drupal::database()->select('athlete_school', 'ats');
    $query5->fields('ats');
    $query5->condition('athlete_uid', $current_user, '=');
    $results5 = $query5->execute()->fetchAssoc();
    $query6 = \Drupal::database()->select('athlete_uni', 'atc');
    $query6->fields('atc');
    $query6->condition('athlete_uid', $current_user, '=');
    $results6 = $query6->execute()->fetchAssoc();
    $query7 = \Drupal::database()->select('athlete_info', 'ai');
    $query7->fields('ai');
    $query7->condition('athlete_uid', $current_user, '=');
    $results7 = $query7->execute()->fetchAssoc();
    $query8 = \Drupal::database()->select('user__field_state', 'ufs');
    $query8->fields('ufs');
    $query8->condition('entity_id', $current_user, '=');
    $results8 = $query8->execute()->fetchAssoc();
    $query9 = \Drupal::database()->select('athlete_about', 'aa');
    $query9->fields('aa');
    $query9->condition('athlete_uid', $current_user, '=');
    $results9 = $query9->execute()->fetchAssoc();
    $query10 = \Drupal::database()->select('athlete_social', 'asoc');
    $query10->fields('asoc');
    $query10->condition('athlete_uid', $current_user, '=');
    $results10 = $query10->execute()->fetchAssoc();
    $query12 = \Drupal::database()->select('athlete_uni', 'auni');
    $query12->fields('auni');
    $query12->condition('athlete_uid', $current_user, '=');
    $results12 = $query12->execute()->fetchAssoc();
    $query13 = \Drupal::database()->select('athlete_web', 'aweb');
    $query13->fields('aweb');
    $query13->condition('athlete_uid', $current_user, '=');
    $results13 = $query13->execute()->fetchAssoc();
    $query14 = \Drupal::database()->select('athlete_addweb', 'aaweb');
    $query14->fields('aaweb');
    $query14->condition('athlete_uid', $current_user, '=');
    $results14 = $query14->execute()->fetchAssoc();
    $query_img = \Drupal::database()->select('user__user_picture', 'n');
    $query_img->addField('n', 'user_picture_target_id');
    $query_img->condition('entity_id', $current_user, '=');
    $results = $query_img->execute()->fetchAssoc();
    $query15 = \Drupal::database()->select('athlete_addschool', 'aas');
    $query15->fields('aas');
    $query15->condition('athlete_uid', $current_user, '=');
    $results15 = $query15->execute()->fetchAssoc();
    $query16 = \Drupal::database()->select('athlete_club', 'aclub');
    $query16->fields('aclub');
    $query16->condition('athlete_uid', $current_user, '=');
    $results16 = $query16->execute()->fetchAssoc();
    $query17 = \Drupal::database()->select('athlete_clubweb', 'aclubw');
    $query17->fields('aclubw');
    $query17->condition('athlete_uid', $current_user, '=');
    $results17 = $query17->execute()->fetchAssoc();
    $query18 = \Drupal::database()->select('mydata', 'md');
    $query18->fields('md');
    $query18->condition('uid', $current_user, '=');
    $results18 = $query18->execute()->fetchAssoc();
    $img_id = $results['user_picture_target_id'];
    // echo "<pre>"; print_r($results8['field_state_value']);die;
    // $file = File::load($img_id);


    $form['prefix'] = "<div class=athlete_edit_class>";
    $form['suffix'] = "</div>";
    //     echo print_r($results1['0']->athlete_school_name);
    // echo print_r($results5);die;
    $form['fname'] = array(
      '#type' => 'textfield',
      //'#title' => t('Candidate Name:'),
      //      '#required' => TRUE,
      '#placeholder' => t('Firstname'),
      //'#default_values' => array(array('id')),
      '#default_value' => $results1['field_first_name_value'],
      '#prefix' => '<div class="left_section popup_left_section"><div class="athlete_left"><h3><div class="toggle_icon"><i class="fa fa-minus"></i><i class="fa fa-plus hide"></i></div>Athletic Information</h3><div class=items_div>',
      );
    $form['lname'] = array(
      '#type' => 'textfield',
      // '#title' => t('Mobile Number:'),
      '#placeholder' => t('Lastname'),
      '#default_value' => $results2['field_last_name_value'],
      );
    $form['email'] = array(
      '#type' => 'textfield',
      '#placeholder' => t('Preferred Contact Email'),
      '#default_value' => $results4['mail'],
      );
    $form['az'] = array(
      //'#title' => t('az'),
      '#type' => 'select',
      //'#description' => 'Select the desired pizza crust size.',
      '#options' => array(
        t('AL'),
        t('AK'),
        t('AZ'),
        t('AR'),
        t('CA'),
        t('CO'),
        t('CT'),
        t('DE'),
        t('DC'),
        t('FL'),
        t('GA'),
        t('HI'),
        t('ID'),
        t('IL'),
        t('IN'),
        t('IA'),
        t('KS'),
        t('KY'),
        t('LA'),
        t('ME'),
        t('MT'),
        t('NE'),
        t('NV'),
        t('NH'),
        t('NJ'),
        t('NM'),
        t('NY'),
        t('NC'),
        t('ND'),
        t('OH'),
        t('OR'),
        t('MD'),
        t('MA'),
        t('MI'),
        t('MN'),
        t('MS'),
        t('MO'),
        t('PA'),
        t('RI'),
        t('SC'),
        t('SD'),
        t('TN'),
        t('TX'),
        t('UT'),
        t('VT'),
        t('VA'),
        t('WA'),
        t('WV'),
        t('WI'),
        t('WY')),
      '#default_value' => $results18['field_az'],
      );
    $form['city'] = array(
      '#type' => 'textfield',
      //'#title' => t('City'),
      // '#required' => TRUE,
      '#placeholder' => t('City'),
      '#default_value' => $results18['field_city'],
      );

    $form['sex'] = array(
      //'#title' => t('az'),
      '#type' => 'select',
      //'#description' => 'Select the desired pizza crust size.',
      '#options' => array(
        t('Gender'),
        t('Male'),
        t('Female'),
        t('Other')),
      '#default_value' => $results7['athlete_state'],
      );
    $form['doj'] = array(
      //'#title' => 'Date of Birth',
      '#placeholder' => 'Date of Birth',
      '#type' => 'date',
      //'#type' => 'date_popup',
     // '#attributes' => ['class' => ['container-inline']],
      '#attributes' => ['class' => ['date-popup']],
      '#required' => true,
      '#default_value' => substr($results3['field_date_value'], 0, 10),
      '#format' => 'm/d/Y',
      '#description' => t('i.e. 09/06/2016'),
      //'#attributes' => array('disabled' => true),
      );



 
	  
    $form['grade'] = array(
      '#type' => 'textfield',
      //'#title' => ('Height'),
      '#placeholder' => t('Grade'),
      '#default_value' => $results7['athlete_city'],
      );
    $form['gradyear'] = array(
      '#type' => 'textfield',
      //'#title' => ('Height'),
      '#placeholder' => t('Graduation Year'),
      '#default_value' => $results7['athlete_year'],
      );
    $form['height'] = array(
      '#type' => 'textfield',
      //'#title' => ('Height'),
      '#placeholder' => t('Height in Inches'),
      '#default_value' => $results7['field_height'],
      );
    $form['weight'] = array(
      '#type' => 'textfield',
      //'#title' => ('Height'),
      '#placeholder' => t('Weight in Pounds'),
      '#default_value' => $results7['field_weight'],
      '#suffix' => '</div></div>',
      );
    $form['aboutme'] = array(
      '#type' => 'textarea',
      '#placeholder' => t('Tell us about yourself'),
      '#attributes' => array('maxlength' => 1500),
      '#default_value' => $results9['athlete_about_me'],
      '#prefix' => '<div class = "athlete_left"><h3><div class="toggle_icon"><i class="fa fa-minus"></i><i class="fa fa-plus hide"></i></div>About me</h3><div class=items_div>',
      '#suffix' => '</div></div>',
      );
    $form['instagram'] = array(
      '#type' => 'textfield',
      '#placeholder' => t('Your Instagram Account(Optional)'),
      '#prefix' => '<div class = "athlete_left"><h3><div class="toggle_icon"><i class="fa fa-minus"></i><i class="fa fa-plus hide"></i></div>Social Media<i class="fa fa-info right-icon" aria-hidden="true"></i></h3><div class=items_div>',
      '#default_value' => $results10['athlete_social_1'],
      );
    $form['youtube'] = array(
      '#type' => 'textfield',
      '#placeholder' => t('Your Youtube/Video Channel(Optional)'),
      '#suffix' => '</div></div>',
      '#default_value' => $results10['athlete_social_2'],
      );
    $orgtype = array(
      t('Organization Type'),
      t('School'),
      t('Club'),
      t('University'));
    $form['organizationType'] = array(
      //'#title' => t('az'),
      '#type' => 'select',
      //'#description' => 'Select the desired pizza crust size.',
      '#options' => array(
        t('Organization Type'),
        t('School'),
        t('Club'),
        t('University')),
      '#prefix' => '<div class="athlete_school"><div class = "athlete_left"><h3><div class="toggle_icon"><i class="fa fa-minus"></i><i class="fa fa-plus hide"></i></div>School/Club/University</h3><div class=items_div>',
      '#default_value' => array_search($results5['athlete_school_type'], $orgtype),
      );
      
    
    $form['organizationName'] = array(
      '#type' => 'textfield',
      '#placeholder' => t('Orginization Name'),
      //'#description' => 'Select the desired pizza crust size.',
      
      //'#default_value' => array_search($results5['athlete_school_name'], $orgname),
      '#default_value' => $results5['athlete_school_name'],
      );
      
      
    $form['coach'] = array(
      '#type' => 'textfield',
      '#placeholder' => t("Coach's Last Name"),
      '#default_value' => $results5['athlete_school_coach'],
      );
    $form['sport'] = array(
      '#type' => 'textfield',
      '#placeholder' => t('Sport'),
      '#default_value' => $results5['athlete_school_sport'],
      );
    $form['position'] = array(
      '#type' => 'textfield',
      '#placeholder' => t('Position'),
      '#default_value' => $results5['athlete_school_pos'],
      '#prefix' => '<div class="add_pos_div_first">',
      '#suffix' => '',
      );
    if (empty($results5['athlete_school_pos2'])) {
      $form['position2'] = array(
        '#type' => 'textfield',
        '#placeholder' => t('Position'),
        '#default_value' => $results5['athlete_school_pos2'],
        '#attributes' => array('style' => 'display:none'),
        // '#prefix' => '<div class ="pos_first_1"',
        // '#suffix' => '</div>',
        );
    } else {
      $form['position2'] = array(
        '#type' => 'textfield',
        '#placeholder' => t('Position'),
        '#default_value' => $results5['athlete_school_pos2'],
        '#attributes' => array('class' => 'pos_show_first_1', 'style' => 'display:block'),
        // '#prefix' => '<div class =pos_first_1',
        // '#suffix' => '</div>',
        );
    }
    if (empty($results5['athlete_school_pos3'])) {
      $form['position3'] = array(
        '#type' => 'textfield',
        '#placeholder' => t('Position'),
        '#default_value' => $results5['athlete_school_pos3'],
        '#attributes' => array('class' => 'pos_hidden_first_2', 'style' => 'display:none'),
        // '#prefix' => '<div class ="pos_first_2"',
        // '#suffix' => '</div>',
        );
    } else {
      $form['position3'] = array(
        '#type' => 'textfield',
        '#placeholder' => t('Position'),
        '#default_value' => $results5['athlete_school_pos3'],
        '#attributes' => array('class' => 'pos_show_first_2', 'style' => 'display:block'),
        // '#prefix' => '<div class =pos_first_2',
        // '#suffix' => '</div>',
        );
    }

    $form['stats'] = array(
      '#type' => 'textarea',
      '#placeholder' => t('Add all personal stats'),
      '#prefix' => '<a class="add_pos_first"><i class="fa fa-plus"></i>Add Position</a><a class="remove_pos_first"><i class="fa fa-trash"></i>Remove Position</a>',
      '#suffix' => '</div></div>',
      '#default_value' => $results5['athlete_school_stat'],
      );

    /*Add another organization 1 start*/
    if (!empty($results12)) {
      $orgtype2 = array(
        t('Organization Type'),
        t('SCHOOL'),
        t('CLUB'),
        t('UNIVERSITY'));
      $form['education_1'] = array(
        '#type' => 'select',
        //'#description' => 'Select the desired pizza crust size.',
        '#options' => array(
          t('Organization Type'),
          t('SCHOOL'),
          t('CLUB'),
          t('UNIVERSITY')),
        '#prefix' => '</div><div class="org_notempty"><div class = "athlete_left"><h3><div class="toggle_icon"><i class="fa fa-minus"></i><i class="fa fa-plus hide"></i></div>School/Club/University</h3><i id="athlete_uni" class="fa fa-trash right-icon delete_icon" aria-hidden="true"></i><div class=items_div>',
        '#default_value' => array_search($results12['athlete_uni_type'], $orgtype2),
        '#required' => true,
        );
      $orgname2 = array(
        t('Organization Name'),
        t('Organization Name 1'),
        t('Organization Name 2'),
        t('Organization Name 3'));
      $form['schoolname_1'] = array(
        //'#title' => t('az'),
        '#type' => 'select',
        //'#description' => 'Select the desired pizza crust size.',
        '#options' => array(
          t('Organization Name'),
          t('Organization Name 1'),
          t('Organization Name 2'),
          t('Organization Name 3')),
        '#default_value' => array_search($results12['athlete_uni_name'], $orgname2),
        '#required' => true,
        );
      $form['coach_1'] = array(
        '#type' => 'textfield',
        '#placeholder' => t("Coache's Last Name"),
        '#default_value' => $results12['athlete_uni_coach'],
        );
      $form['sport_1'] = array(
        '#type' => 'textfield',
        '#placeholder' => t('Sport'),
        '#default_value' => $results12['athlete_uni_sport'],
        );
      $form['position_1'] = array(
        '#type' => 'textfield',
        '#placeholder' => t('Position'),
        '#default_value' => $results12['athlete_uni_pos'],
        '#prefix' => '<div class="add_pos_div_second">',
        '#suffix' => '',
        );
      if (empty($results6['athlete_uni_pos2'])) {
        $form['position_12'] = array(
          '#type' => 'textfield',
          '#placeholder' => t('Position'),
          '#default_value' => $results6['athlete_uni_pos2'],
          '#attributes' => array('class' => 'pos_hidden_second_1', 'style' => 'display:none'),
          // '#prefix' => '<div class ="pos_hidden_first_1 hidpos"',
          // '#suffix' => '</div>',
          );
      } else {
        $form['position_12'] = array(
          '#type' => 'textfield',
          '#placeholder' => t('Position'),
          '#default_value' => $results6['athlete_uni_pos2'],
          '#attributes' => array('class' => 'pos_show_first_1', 'style' => 'display:block'),
          // '#prefix' => '<div class =pos_show_first_1',
          // '#suffix' => '</div>',
          );
      }
      if (empty($results6['athlete_uni_pos3'])) {
        $form['position_13'] = array(
          '#type' => 'textfield',
          '#placeholder' => t('Position'),
          '#default_value' => $results6['athlete_uni_pos3'],
          '#attributes' => array('class' => 'pos_hidden_first_2', 'style' => 'display:none'),
          // '#prefix' => '<div class ="pos_hidden_first_2 hidpos"',
          // '#suffix' => '</div>',
          );
      } else {
        $form['position_13'] = array(
          '#type' => 'textfield',
          '#placeholder' => t('Position'),
          '#default_value' => $results6['athlete_uni_pos3'],
          '#attributes' => array('class' => 'pos_show_first_2', 'style' => 'display:block'),
          // '#prefix' => '<div class =pos_show_first_2',
          // '#suffix' => '</div>',
          );
      }
      $form['stats_1'] = array(
        '#type' => 'textarea',
        '#placeholder' => t('Add all personal stats'),
        '#prefix' => '<a class="add_pos_second"><i class="fa fa-plus"></i>Add Position</a><a class="remove_pos_second"><i class="fa fa-trash"></i>Remove Position</a></div>',
        '#suffix' => '</div></div>',
        '#default_value' => $results12['athlete_uni_stat'],
        );
    } else {

      $orgtype2 = array(
        t('Organization Type'),
        t('SCHOOL'),
        t('CLUB'),
        t('UNIVERSITY'));
      $form['education_1'] = array(
        //'#title' => t('az'),
        '#type' => 'select',
        //'#description' => 'Select the desired pizza crust size.',
        '#options' => array(
          t('Organization Type'),
          t('SCHOOL'),
          t('CLUB'),
          t('UNIVERSITY')),
        '#prefix' => '</div><div class="athlete_school popup-athlete-school-hide previous_athlete" style="display:none;"><div class = "athlete_left"><h3><div class="toggle_icon"><i class="fa fa-minus"></i><i class="fa fa-plus hide"></i></div>School/Club/University</h3><i class="fa fa-trash right-icon delete_icon previous_delete" aria-hidden="true"></i><div class=items_div>',
        // '#default_value' => array_search($results12['athlete_uni_type'],$orgtype2),
        );
      $orgname2 = array(
        t('Organization Name'),
        t('Organization Name 1'),
        t('Organization Name 2'),
        t('Organization Name 3'));
      $form['schoolname_1'] = array(
        //'#title' => t('az'),
        '#type' => 'select',
        //'#description' => 'Select the desired pizza crust size.',
        '#options' => array(
          t('Organization Name'),
          t('Organization Name 1'),
          t('Organization Name 2'),
          t('Organization Name 3')),
        // '#default_value' => array_search($results12['athlete_uni_name'],$orgname2),
        );
      $form['coach_1'] = array(
        '#type' => 'textfield',
        '#placeholder' => t("Coache's Last Name"),
        // '#default_value' => $results12['athlete_uni_coach'],
        );
      $form['sport_1'] = array(
        '#type' => 'textfield',
        '#placeholder' => t('Sport'),
        // '#default_value' => $results12['athlete_uni_sport'],
        );
      $form['position_1'] = array(
        '#type' => 'textfield',
        '#placeholder' => t('Position'),
        // '#default_value' => $results12['athlete_uni_pos'],
        '#prefix' => '<div class="add_pos_div_second">',
        '#suffix' => '',
        );
      if (empty($results6['athlete_uni_pos2'])) {
        $form['position_12'] = array(
          '#type' => 'textfield',
          '#placeholder' => t('Position'),
          // '#default_value' => $results6['athlete_uni_pos2'],
          '#attributes' => array('class' => 'pos_hidden_second_1', 'style' => 'display:none'),
          // '#prefix' => '<div class ="pos_hidden_first_1 hidpos"',
          // '#suffix' => '</div>',
          );
      } else {
        $form['position_12'] = array(
          '#type' => 'textfield',
          '#placeholder' => t('Position'),
          // '#default_value' => $results6['athlete_uni_pos2'],
          '#attributes' => array('class' => 'pos_show_first_1', 'style' => 'display:block'),
          // '#prefix' => '<div class =pos_show_first_1',
          // '#suffix' => '</div>',
          );
      }
      if (empty($results6['athlete_uni_pos3'])) {
        $form['position_13'] = array(
          '#type' => 'textfield',
          '#placeholder' => t('Position'),
          // '#default_value' => $results6['athlete_uni_pos3'],
          '#attributes' => array('class' => 'pos_hidden_first_2', 'style' => 'display:none'),
          // '#prefix' => '<div class ="pos_hidden_first_2 hidpos"',
          // '#suffix' => '</div>',
          );
      } else {
        $form['position_13'] = array(
          '#type' => 'textfield',
          '#placeholder' => t('Position'),
          // '#default_value' => $results6['athlete_uni_pos3'],
          '#attributes' => array('class' => 'pos_show_first_2', 'style' => 'display:block'),
          // '#prefix' => '<div class =pos_show_first_2',
          // '#suffix' => '</div>',
          );
      }
      $form['stats_1'] = array(
        '#type' => 'textarea',
        '#placeholder' => t('Add all personal stats'),
        '#prefix' => '<a class="add_pos_second"><i class="fa fa-plus"></i>Add Position</a><a class="remove_pos_second"><i class="fa fa-trash"></i>Remove Position</a></div>',
        '#suffix' => '</div></div>',
        // '#default_value' => $results12['athlete_uni_stat'],
        );
    }

    /*Add another organization 1 END*/
    /*Add another organization 1 start*/
    if (!empty($results16)) {
      $unitype = array(
        t('Organization Type'),
        t('SCHOOL'),
        t('CLUB'),
        t('UNIVERSITY'));
      $form['education_2'] = array(
        '#type' => 'select',
        '#required' => true,
        '#options' => array(
          t('Organization Type'),
          t('SCHOOL'),
          t('CLUB'),
          t('UNIVERSITY')),
        '#prefix' => '<div class = "athlete_left"><h3><div class="toggle_icon"><i class="fa fa-minus"></i><i class="fa fa-plus hide"></i></div>School/Club/University</h3><i id="athlete_club" class="fa fa-trash right-icon delete_icon" aria-hidden="true"></i><div class=items_div>',
        '#default_value' => array_search($results12['athlete_uni_type'], $unitype),
        );
      $uniname = array(
        t('Organization Name'),
        t('Organization Name 1'),
        t('Organization Name 2'),
        t('Organization Name 3'));
      $form['schoolname_2'] = array(
        '#type' => 'select',
        '#required' => true,
        '#options' => array(
          t('Organization Name'),
          t('Organization Name 1'),
          t('Organization Name 2'),
          t('Organization Name 3')),
        '#default_value' => array_search($results12['athlete_uni_name'], $uniname),
        );
      $form['coach_2'] = array(
        '#type' => 'textfield',
        '#placeholder' => t("Coache's Last Name"),
        '#default_value' => $results16['athlete_club_coach'],
        );
      $form['sport_2'] = array(
        '#type' => 'textfield',
        '#placeholder' => t('Sport'),
        '#default_value' => $results16['athlete_club_sport'],
        );
      $form['position_2'] = array(
        '#type' => 'textfield',
        '#placeholder' => t('Position'),
        '#default_value' => $results16['athlete_club_pos'],
        '#prefix' => '<div class="add_pos_div_third">',
        '#suffix' => '',
        );
      if (empty($results16['athlete_school_pos2'])) {
        $form['position_22'] = array(
          '#type' => 'textfield',
          '#placeholder' => t('Position'),
          '#default_value' => $results16['athlete_school_pos2'],
          '#attributes' => array('class' => 'pos_hidden_third_1', 'style' => 'display:none'),
          // '#prefix' => '<div class ="pos_hidden_first_1 hidpos"',
          // '#suffix' => '</div>',
          );
      } else {
        $form['position_22'] = array(
          '#type' => 'textfield',
          '#placeholder' => t('Position'),
          '#default_value' => $results16['athlete_school_pos2'],
          '#attributes' => array('class' => 'pos_show_first_1', 'style' => 'display:block'),
          // '#prefix' => '<div class =pos_show_first_1',
          // '#suffix' => '</div>',
          );
      }
      if (empty($results16['athlete_school_pos3'])) {
        $form['position_23'] = array(
          '#type' => 'textfield',
          '#placeholder' => t('Position'),
          '#default_value' => $results16['athlete_school_pos3'],
          '#attributes' => array('class' => 'pos_hidden_first_2', 'style' => 'display:none'),
          // '#prefix' => '<div class ="pos_hidden_first_2 hidpos"',
          // '#suffix' => '</div>',
          );
      } else {
        $form['position_23'] = array(
          '#type' => 'textfield',
          '#placeholder' => t('Position'),
          '#default_value' => $results16['athlete_school_pos3'],
          '#attributes' => array('class' => 'pos_show_first_2', 'style' => 'display:block'),
          // '#prefix' => '<div class =pos_show_first_2',
          // '#suffix' => '</div>',
          );
      }
      $form['stats_2'] = array(
        '#type' => 'textarea',
        '#placeholder' => t('Add all personal stats'),
        '#suffix' => '</div></div></div><a class="add_org popup_add_org"><i class="fa fa-plus"></i>Add Another Organization</a></div>',
        '#default_value' => '',
        '#prefix' => '<a class="add_pos_third"><i class="fa fa-plus"></i>Add Position</a><a class="remove_pos_third"><i class="fa fa-trash"></i>Remove Position</a></div>',
        );
    } else {
      $form['education_2'] = array(
        //'#title' => t('az'),
        '#type' => 'select',
        //'#description' => 'Select the desired pizza crust size.',
        '#options' => array(
          t('Organization Type'),
          t('SCHOOL'),
          t('CLUB'),
          t('UNIVERSITY')),
        '#prefix' => '</div><div class="athlete_school popup-athlete-school-hide last_athlete"><div class = "athlete_left"><h3><div class="toggle_icon"><i class="fa fa-minus"></i><i class="fa fa-plus hide"></i></div>School/Club/University</h3><i class="fa fa-trash right-icon delete_icon last_delete" aria-hidden="true"></i><div class=items_div>',
        );

      $form['schoolname_2'] = array(
        //'#title' => t('az'),
        '#type' => 'select',
        //'#description' => 'Select the desired pizza crust size.',
        '#options' => array(
          t('Organization Name'),
          t('Organization Name 1'),
          t('Organization Name 2'),
          t('Organization Name 3')),
        );
      $form['coach_2'] = array(
        '#type' => 'textfield',
        '#placeholder' => t("Coache's Last Name"),
        '#default_value' => '',
        );
      $form['sport_2'] = array(
        '#type' => 'textfield',
        '#placeholder' => t('Sport'),
        '#default_value' => '',
        );
      $form['position_2'] = array(
        '#type' => 'textfield',
        '#placeholder' => t('Position'),
        '#default_value' => '',
        '#prefix' => '<div class="add_pos_div_third">',
        '#suffix' => '',
        );
      if (empty($results16['athlete_school_pos2'])) {
        $form['position_22'] = array(
          '#type' => 'textfield',
          '#placeholder' => t('Position'),
          // '#default_value' => $results6['athlete_school_pos2'],
          '#attributes' => array('class' => 'pos_hidden_first_1', 'style' => 'display:none'),
          // '#prefix' => '<div class ="pos_hidden_first_1 hidpos"',
          // '#suffix' => '</div>',
          );
      } else {
        $form['position_22'] = array(
          '#type' => 'textfield',
          '#placeholder' => t('Position'),
          // '#default_value' => $results6['athlete_school_pos2'],
          '#attributes' => array('class' => 'pos_show_first_1', 'style' => 'display:block'),
          // '#prefix' => '<div class =pos_show_first_1 ',
          // '#suffix' => '</div>',
          );
      }
      if (empty($results16['athlete_school_pos3'])) {
        $form['position_23'] = array(
          '#type' => 'textfield',
          '#placeholder' => t('Position'),
          // '#default_value' => $results6['athlete_school_pos3'],
          '#attributes' => array('class' => 'pos_hidden_first_2', 'style' => 'display:none'),
          // '#prefix' => '<div class ="pos_hidden_first_2 hidpos"',
          // '#suffix' => '</div>',
          );
      } else {
        $form['position_23'] = array(
          '#type' => 'textfield',
          '#placeholder' => t('Position'),
          // '#default_value' => $results6['athlete_school_pos3'],
          '#attributes' => array('class' => 'pos_show_first_2', 'style' => 'display:block'),
          // '#prefix' => '<div class =pos_show_first_2',
          // '#suffix' => '</div>',
          );
      }
      $form['stats_2'] = array(
        '#type' => 'textarea',
        '#placeholder' => t('Add all personal stats'),
        '#suffix' => '</div></div></div><a class="add_org popup_add_org"><i class="fa fa-plus"></i>Add Another Organization</a></div>',
        '#default_value' => '',
        '#prefix' => '<a class="add_pos_third"><i class="fa fa-plus"></i>Add Position</a><a class="remove_pos_third"><i class="fa fa-trash"></i>Remove Position</a></div>',
        );
    }
    /*Add another organization 1 END*/
    $form['submit'] = ['#type' => 'submit', '#value' => 'save', '#prefix' => '<div id="athlete_submit">', '#suffix' => '</div></div><div class ="right_section"><div class = "athlete_right"><h3><div class="toggle_icon"><i class="fa fa-minus"></i><i class="fa fa-plus hide"></i></div>My Website Photo</h3><div class="edit_dropdown"><a class="drop" >Action<span class="down-arrow fa fa-angle-down"></span></a><ul class="dropdown-menu" style="padding:0"></ul></div><div class=items_div>',
      //'#value' => t('Submit'),
      ];
    $form['image_athlete'] = ['#type' => 'managed_file', '#upload_validators' => ['file_validate_extensions' => ['gif png jpg jpeg'], 'file_validate_size' => [25600000], ], '#theme' => 'image_widget', '#preview_image_style' => 'medium', '#upload_location' => 'public://', '#required' => false, '#default_value' => array($img_id), '#prefix' => '</div>', '#suffix' => '<div class="action_bttn"><span>Action</span><ul><li>Remove</li></ul></div></div></div>', ];

    $form['school_web'] = array(
      '#type' => 'textfield',
      '#placeholder' => t('School'),
      '#default_value' => $results5['athlete_school_name'],
      '#attributes' => array('disabled' => true),
      '#prefix' => '</div></div><div class = "athlete_right"><h3><div class="toggle_icon"><i class="fa fa-minus"></i><i class="fa fa-plus hide"></i></div>My Website</h3><div class=items_div>',
      );
    $form['sport_web'] = array(
      '#type' => 'textfield',
      '#placeholder' => t('Sport'),
      '#attributes' => array('disabled' => true),
      '#default_value' => $results5['athlete_school_sport'],
      );
    $form['name_web'] = array(
      '#type' => 'textfield',
      '#placeholder' => t('Pick a Name'),
      '#default_value' => $results13['athlete_web_name'],
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
      '#default_value' => $results13['athlete_web_visibility'],
      '#suffix' => '',
      );
    if (!empty($results12)) {
      $form['school_web2'] = array(
        '#type' => 'textfield',
        '#placeholder' => t('School'),
        '#default_value' => $results6['athlete_uni_name'],
        '#attributes' => array('disabled' => true),
        '#prefix' => '</div></div><div class = "athlete_right"><h3><div class="toggle_icon"><i class="fa fa-minus"></i><i class="fa fa-plus hide"></i></div>Additional Website</h3><div class=items_div>',
        );
      $form['sport_web2'] = array(
        '#type' => 'textfield',
        '#placeholder' => t('Sport'),
        '#default_value' => $results6['athlete_uni_sport'],
        '#attributes' => array('disabled' => true),
        );
      $form['name_web2'] = array(
        '#type' => 'textfield',
        '#placeholder' => t('Pick a Name'),
        '#default_value' => $results14['athlete_addweb_name'],
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
        '#default_value' => $results14['athlete_addweb_visibility'],
        '#suffix' => '</div></div>',
        );
    }
    if (!empty($results16)) {
      $form['school_web3'] = array(
        '#type' => 'textfield',
        '#placeholder' => t('School'),
        '#default_value' => $results16['athlete_club_name'],
        '#attributes' => array('disabled' => true),
        '#prefix' => '<div class = "athlete_right"><h3><div class="toggle_icon"><i class="fa fa-minus"></i><i class="fa fa-plus hide"></i></div>Additional Website</h3><div class=items_div>',
        );
      $form['sport_web3'] = array(
        '#type' => 'textfield',
        '#placeholder' => t('Sport'),
        '#default_value' => $results16['athlete_club_sport'],
        '#attributes' => array('disabled' => true),
        );
      $form['name_web3'] = array(
        '#type' => 'textfield',
        '#placeholder' => t('Pick a Name'),
        '#default_value' => $results17['athlete_clubweb_name'],
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
        '#default_value' => $results17['athlete_clubweb_visibility'],
        '#suffix' => '</div></div></div>',
        );
    }

    // $form['#theme'] = 'athlete_form';
    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array & $form, FormStateInterface $form_state) {

    if (!$form_state->getValue('fname') || empty($form_state->getValue('fname'))) {
      $form_state->setErrorByName('fname', $this->t('First name should not be empty.'));
    }
    if (!$form_state->getValue('lname') || empty($form_state->getValue('lname'))) {
      $form_state->setErrorByName('lname', $this->t('Last name should not be empty.'));
    }
    if (!$form_state->getValue('email') || !filter_var($form_state->getValue('email'), FILTER_VALIDATE_EMAIL)) {
      $form_state->setErrorByName('email', $this->t('Please enter a valid email.'));
    }
    if (!$form_state->getValue('sex') || empty($form_state->getValue('sex'))) {
      $form_state->setErrorByName('sex', $this->t('Gender should not be empty.'));
    }
    if (!$form_state->getValue('city') || empty($form_state->getValue('city'))) {
      $form_state->setErrorByName('city', $this->t('City should not be empty.'));
    }
    if (!$form_state->getValue('grade') || empty($form_state->getValue('grade'))) {
      $form_state->setErrorByName('grade', $this->t('Grade should not be empty.'));
    }
    if (!$form_state->getValue('gradyear') || empty($form_state->getValue('gradyear'))) {
      $form_state->setErrorByName('gradyear', $this->t('Graduation year should not be empty.'));
    }
    if (!$form_state->getValue('height') || !is_numeric($form_state->getValue('height'))) {
      $form_state->setErrorByName('height', $this->t('Height should be a number.'));
    }
    if (empty($form_state->getValue('height'))) {
      $form_state->setErrorByName('height', $this->t('Height should not be empty.'));
    }
    if (!$form_state->getValue('weight') || !is_numeric($form_state->getValue('weight'))) {
      $form_state->setErrorByName('weight', $this->t('Weight should be a number.'));
    }
    if (empty($form_state->getValue('weight'))) {
      $form_state->setErrorByName('weight', $this->t('Weight should not be empty.'));
    }
    //    if (!$form_state->getValue('aboutme') || strlen($form_state->getValue('aboutme')) <= 15000 ) {
    //        $form_state->setErrorByName('aboutme', $this->t('Should be a less than 1000 character.'));
    //    }
    if (!$form_state->getValue('coach') || empty($form_state->getValue('coach'))) {
      $form_state->setErrorByName('coach', $this->t("Coach's last name should not be empty."));
    }
    if (!$form_state->getValue('sport') || empty($form_state->getValue('sport'))) {
      $form_state->setErrorByName('sport', $this->t("Sport should not be empty."));
    }

  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array & $form, FormStateInterface $form_state) {
    $current_user = \Drupal::currentUser()->id();
    $conn = Database::getConnection();
    $seltype1 = $form_state->getValue('organizationType');
    $selname1 = $form_state->getValue('organizationName');
    $seltype2 = $form_state->getValue('education_1');
    $selname2 = $form_state->getValue('schoolname_1');
    $seltype3 = $form_state->getValue('education_2');
    $selname3 = $form_state->getValue('schoolname_2');
    $selstate = $form_state->getValue('az');
    $seltypeval1 = $form['organizationType']['#options'][$seltype1];
    $selnameval1 = $form['organizationName']['#options'][$selname1];
    $seltypeval2 = $form['education_1']['#options'][$seltype2];
    $selnameval2 = $form['schoolname_1']['#options'][$selname2];
    $seltypeval3 = $form['education_2']['#options'][$seltype3];
    $selnameval3 = $form['schoolname_2']['#options'][$selname3];
    $statevalue = $form['az']['#options'][$selstate];
    $query_info = \Drupal::database()->select('athlete_info', 'ai');
    $query_info->fields('ai');
    $query_info->condition('athlete_uid', $current_user, '=');
    $results_info = $query_info->execute()->fetchAll();
    $query_about = \Drupal::database()->select('athlete_about', 'aa');
    $query_about->fields('aa');
    $query_about->condition('athlete_uid', $current_user, '=');
    $results_about = $query_about->execute()->fetchAll();
    $query_social = \Drupal::database()->select('athlete_social', 'ascc');
    $query_social->fields('ascc');
    $query_social->condition('athlete_uid', $current_user, '=');
    $results_social = $query_social->execute()->fetchAll();
    $query_web = \Drupal::database()->select('athlete_web', 'aw');
    $query_web->fields('aw');
    $query_web->condition('athlete_uid', $current_user, '=');
    $results_web = $query_web->execute()->fetchAll();
    $query_addweb = \Drupal::database()->select('athlete_addweb', 'aaw');
    $query_addweb->fields('aaw');
    $query_addweb->condition('athlete_uid', $current_user, '=');
    $results_addweb = $query_addweb->execute()->fetchAll();
    $query_school = \Drupal::database()->select('athlete_school', 'ats');
    $query_school->fields('ats');
    $query_school->condition('athlete_uid', $current_user, '=');
    $results_school = $query_school->execute()->fetchAll();
    $query_uni = \Drupal::database()->select('athlete_uni', 'au');
    $query_uni->fields('au');
    $query_uni->condition('athlete_uid', $current_user, '=');
    $results_uni = $query_uni->execute()->fetchAll();
    $query_fname = \Drupal::database()->select('user__field_first_name', 'uffn');
    $query_fname->fields('uffn');
    $query_fname->condition('entity_id', $current_user, '=');
    $results_fname = $query_fname->execute()->fetchAll();
    $query_lname = \Drupal::database()->select('user__field_last_name', 'ufln2');
    $query_lname->addField('ufln2', 'field_last_name_value');
    $query_lname->condition('entity_id', $current_user, '=');
    $results_lname = $query_lname->execute()->fetchAssoc();
    $query_mail = \Drupal::database()->select('users_field_data', 'ufln4');
    $query_mail->addField('ufln4', 'mail');
    $query_mail->condition('uid', $current_user, '=');
    $results_mail = $query_mail->execute()->fetchAssoc();
    $query_web = \Drupal::database()->select('athlete_web', 'athw');
    $query_web->fields('athw');
    $query_web->condition('athlete_uid', $current_user, '=');
    $results_web = $query_web->execute()->fetchAll();
    $query_addweb = \Drupal::database()->select('athlete_addweb', 'athaw');
    $query_addweb->fields('athaw');
    $query_addweb->condition('athlete_uid', $current_user, '=');
    $results_addweb = $query_addweb->execute()->fetchAll();
    $query_clubweb = \Drupal::database()->select('athlete_clubweb', 'athawa');
    $query_clubweb->fields('athawa');
    $query_clubweb->condition('athlete_uid', $current_user, '=');
    $results_clubweb = $query_clubweb->execute()->fetchAll();
    $query_club = \Drupal::database()->select('athlete_club', 'athawac');
    $query_club->fields('athawac');
    $query_club->condition('athlete_uid', $current_user, '=');
    $results_club = $query_club->execute()->fetchAll();
    $query_mydata = \Drupal::database()->select('mydata', 'md');
    $query_mydata->fields('md');
    $query_mydata->condition('uid', $current_user, '=');
    $results_mydata = $query_mydata->execute()->fetchAll();
    // $query_img = \Drupal::database()->select('user__user_picture', 'n');
    // $query_img->addField('n', 'user_picture_target_id');
    // $query_img->condition('entity_id', $current_user,'=');
    // $results = $query_img->execute()->fetchAssoc();
    // $img_id = $results['user_picture_target_id'];
    // print_r($form_state->getValue('image_athlete'));die;
    $imgid = $form_state->getValue('image_athlete');
    $query_pic = \Drupal::database()->select('user__user_picture', 'uup');
    $query_pic->fields('uup');
    $query_pic->condition('entity_id', $current_user, '=');
    $results_pic = $query_pic->execute()->fetchAll();
    $imgid = $form_state->getValue('image_athlete');
    if (empty($results_pic)) {
      $conn->insert('user__user_picture')->fields(array(
        'entity_id' => $current_user,
        'bundle' => 'user',
        'deleted' => '0',
        'revision_id' => $current_user,
        'langcode' => 'en',
        'delta' => '0',
        'user_picture_target_id' => $imgid[0],
        ))->execute();
    } else {
      if (!empty($imgid[0])) {
        $conn->update('user__user_picture')->condition('entity_id', $current_user, '=')->fields(array('user_picture_target_id' => $imgid[0], ))->execute();
      } else {
        $conn->update('user__user_picture')->condition('entity_id', $current_user, '=')->fields(array('user_picture_target_id' => '240', ))->execute();
      }

    }

    $conn->update('user__field_first_name')->condition('entity_id', $current_user, '=')->fields(array('field_first_name_value' => $form_state->getValue('fname'), ))->execute();

    $conn->update('user__field_last_name')->condition('entity_id', $current_user, '=')->fields(array('field_last_name_value' => $form_state->getValue('lname'), ))->execute();
    $conn->update('users_field_data')->condition('uid', $current_user, '=')->fields(array('mail' => $form_state->getValue('email'), ))->execute();
    $conn->update('user__field_state')->condition('entity_id', $current_user, '=')->fields(array('field_state_value' => $statevalue, ))->execute();


    $conn->update('mydata')->condition('uid', $current_user, '=')->fields(array(
      'field_az' => $az,
      'field_city' => $city,
      ))->execute();
    
    $query3 = \Drupal::database()->select('user__field_date', 'ufln3');
    
    $query3->addField('ufln3', 'field_date_value');
    $query3->condition('entity_id', $current_user, '=');
    $results3 = $query3->execute()->fetchAssoc();
    if (empty($results_web)) {
        $conn->insert('user__field_date')->fields(array(
        'entity_id' => $current_user,
        'field_date_value' => $form_state->getValue('doj'),
        ))->execute();
    }
    else {
        $conn->update('user__field_date')->condition('entity_id', $current_user, '=')->fields(array(
        'field_date_value' => $form_state->getValue('doj'),
        ))->execute();
    }

    if (empty($results_web)) {
      $conn->insert('athlete_web')->fields(array(
        'athlete_uid' => $current_user,
        'athlete_web_name' => $form_state->getValue('instagram'),
        'athlete_web_visibility' => $form_state->getValue('youtube'),
        ))->execute();
    } else {
      $conn->update('athlete_web')->condition('athlete_uid', $current_user, '=')->fields(array(
        'athlete_web_name' => $form_state->getValue('instagram'),
        'athlete_web_visibility' => $form_state->getValue('youtube'),
        ))->execute();
    }
    if (empty($results_mydata)) {
      $conn->insert('mydata')->fields(array(
        'uid' => $current_user,
        'field_az' => $form_state->getValue('az'),
        'field_city' => $form_state->getValue('city'),
        ))->execute();
    } else {
      $conn->update('mydata')->condition('uid', $current_user, '=')->fields(array(
        'field_az' => $form_state->getValue('az'),
        'field_city' => $form_state->getValue('city'),
        ))->execute();
    }

    if (empty($results_info)) {
      $conn->insert('athlete_info')->fields(array(
        'athlete_uid' => $current_user,
        'athlete_email' => $form_state->getValue('email'),
        'athlete_state' => $form_state->getValue('sex'),
        'athlete_city' => $form_state->getValue('grade'),
        'athlete_coach' => $form_state->getValue('coach'),
        'athlete_year' => $form_state->getValue('gradyear'),
        'field_height' => $form_state->getValue('height'),
        'field_weight' => $form_state->getValue('weight'),
        'popup_flag' => $popupFlag,
        ))->execute();
    } else {
      $conn->update('athlete_info')->condition('athlete_uid', $current_user, '=')->fields(array(
        'athlete_email' => $form_state->getValue('email'),
        'athlete_state' => $form_state->getValue('sex'),
        'athlete_city' => $form_state->getValue('grade'),
        'athlete_coach' => $form_state->getValue('coach'),
        'athlete_year' => $form_state->getValue('gradyear'),
        'field_height' => $form_state->getValue('height'),
        'field_weight' => $form_state->getValue('weight'),
        'popup_flag' => $popupFlag,
        ))->execute();
    }
    
    
    
    
    if (empty($results_about)) {
      $conn->insert('athlete_about')->fields(array(
        'athlete_uid' => $current_user,
        'athlete_about_me' => $form_state->getValue('aboutme'),
        ))->execute();
    } else {
      $conn->update('athlete_about')->condition('athlete_uid', $current_user, '=')->fields(array('athlete_about_me' => $form_state->getValue('aboutme'), ))->execute();
    }
    if (empty($results_social)) {
      $conn->insert('athlete_social')->fields(array(
        'athlete_uid' => $current_user,
        'athlete_social_1' => $form_state->getValue('instagram'),
        'athlete_social_2' => $form_state->getValue('youtube'),
        ))->execute();
    } else {
      $conn->update('athlete_social')->condition('athlete_uid', $current_user, '=')->fields(array(
        'athlete_social_1' => $form_state->getValue('instagram'),
        'athlete_social_2' => $form_state->getValue('youtube'),
        ))->execute();
    }
    if (empty($results_web)) {
      $conn->insert('athlete_web')->fields(array(
        'athlete_uid' => $current_user,
        'athlete_web_name' => $form_state->getValue('name_web'),
        'athlete_web_visibility' => $form_state->getValue('web_visible_1'),
        ))->execute();
    } else {
      $conn->update('athlete_web')->condition('athlete_uid', $current_user, '=')->fields(array(
        'athlete_web_name' => $form_state->getValue('name_web'),
        'athlete_web_visibility' => $form_state->getValue('web_visible_1'),
        ))->execute();

    }
    if (empty($results_addweb)) {
      $conn->insert('athlete_addweb')->fields(array(
        'athlete_uid' => $current_user,
        'athlete_addweb_name' => $form_state->getValue('name_web2'),
        'athlete_addweb_visibility' => $form_state->getValue('web_visible_2'),
        ))->execute();
    } else {
      $conn->update('athlete_addweb')->condition('athlete_uid', $current_user, '=')->fields(array(
        'athlete_addweb_name' => $form_state->getValue('name_web2'),
        'athlete_addweb_visibility' => $form_state->getValue('web_visible_2'),
        ))->execute();
    }
    if (empty($results_clubweb)) {
      $conn->insert('athlete_clubweb')->fields(array(
        'athlete_uid' => $current_user,
        'athlete_clubweb_name' => $form_state->getValue('name_web3'),
        'athlete_clubweb_visibility' => $form_state->getValue('web_visible_3'),
        ))->execute();
    } else {
      $conn->update('athlete_clubweb')->condition('athlete_uid', $current_user, '=')->fields(array(
        'athlete_clubweb_name' => $form_state->getValue('name_web3'),
        'athlete_clubweb_visibility' => $form_state->getValue('web_visible_3'),
        ))->execute();
    }
    if (empty($results_school) && $seltype1 != 0 && $selname1 != 0) {
      $conn->insert('athlete_school')->fields(array(
        'athlete_uid' => $current_user,
        'athlete_school_name' => $selnameval1,
        'athlete_school_coach' => $form_state->getValue('coach'),
        'athlete_school_sport' => $form_state->getValue('sport'),
        'athlete_school_pos' => $form_state->getValue('position'),
        'athlete_school_pos2' => $form_state->getValue('position2'),
        'athlete_school_pos3' => $form_state->getValue('position3'),
        'athlete_school_stat' => $form_state->getValue('stats'),
        'athlete_school_type' => $seltypeval1,
        ))->execute();
    } else
      if ($seltype1 != 0 && $selname1 != 0) {
        $conn->update('athlete_school')->condition('athlete_uid', $current_user, '=')->fields(array(
          'athlete_school_name' => $selnameval1,
          'athlete_school_coach' => $form_state->getValue('coach'),
          'athlete_school_sport' => $form_state->getValue('sport'),
          'athlete_school_pos' => $form_state->getValue('position'),
          'athlete_school_pos2' => $form_state->getValue('position2'),
          'athlete_school_pos3' => $form_state->getValue('position3'),
          'athlete_school_stat' => $form_state->getValue('stats'),
          'athlete_school_type' => $seltypeval1,
          ))->execute();
      }
    if (empty($results_club) && $seltype3 != 0 && $selname3 != 0) {
      if (!empty($selnameval3) && !empty($seltypeval3)) {
        $conn->insert('athlete_club')->fields(array(
          'athlete_uid' => $current_user,
          'athlete_club_name' => $selnameval3,
          'athlete_club_coach' => $form_state->getValue('coach_2'),
          'athlete_club_sport' => $form_state->getValue('sport_2'),
          'athlete_club_pos' => $form_state->getValue('position_2'),
          'athlete_school_pos2' => $form_state->getValue('position_22'),
          'athlete_school_pos3' => $form_state->getValue('position_23'),
          'athlete_club_stat' => $form_state->getValue('stats_2'),
          'athlete_school_type' => $seltypeval3,
          ))->execute();
      }
    } else
      if ($seltype3 != 0 && $selname3 != 0) {
        $conn->update('athlete_club')->condition('athlete_uid', $current_user, '=')->fields(array(
          'athlete_club_name' => $selnameval3,
          'athlete_club_coach' => $form_state->getValue('coach_2'),
          'athlete_club_sport' => $form_state->getValue('sport_2'),
          'athlete_club_pos' => $form_state->getValue('position_2'),
          'athlete_school_pos2' => $form_state->getValue('position_22'),
          'athlete_school_pos3' => $form_state->getValue('position_23'),
          'athlete_club_stat' => $form_state->getValue('stats_2'),
          'athlete_school_type' => $seltypeval3,
          ))->execute();
      }
    if (empty($results_uni) && $seltype2 != 0 && $selname2 != 0) {
      $conn->insert('athlete_uni')->fields(array(
        'athlete_uid' => $current_user,
        'athlete_uni_name' => $selnameval2,
        'athlete_uni_coach' => $form_state->getValue('coach_1'),
        'athlete_uni_sport' => $form_state->getValue('sport_1'),
        'athlete_uni_pos' => $form_state->getValue('position_1'),
        'athlete_uni_pos2' => $form_state->getValue('position_12'),
        'athlete_uni_pos3' => $form_state->getValue('position_13'),
        'athlete_uni_stat' => $form_state->getValue('stats_1'),
        'athlete_uni_type' => $seltypeval2,
        ))->execute();
    } else
      if ($seltype2 != 0 && $selname2 != 0) {
        $conn->update('athlete_uni')->condition('athlete_uid', $current_user, '=')->fields(array(
          'athlete_uni_name' => $selnameval2,
          'athlete_uni_coach' => $form_state->getValue('coach_1'),
          'athlete_uni_sport' => $form_state->getValue('sport_1'),
          'athlete_uni_pos' => $form_state->getValue('position_1'),
          'athlete_uni_pos2' => $form_state->getValue('position_12'),
          'athlete_uni_pos3' => $form_state->getValue('position_13'),
          'athlete_uni_stat' => $form_state->getValue('stats_1'),
          'athlete_uni_type' => $seltypeval2,
          ))->execute();
      }
    drupal_set_message(t('An error occurred and processing did not complete.'), 'error');
    // $form_state->setRedirect('acme_hello');
    // return;
  }
}

?>
