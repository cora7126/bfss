<?php
namespace Drupal\bfss_admin\Form;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Component\Utility\UrlHelper;
use Drupal\Core\Database\Database;
use Drupal\file\Entity\File;
use \Drupal\user\Entity\User;
use Drupal\Core\Render\Markup;
/**
 * Implements an bfss_admin form.
 */
class BfssUsersEditableAccount extends FormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'bfss_users_editable_account';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $param = \Drupal::request()->query->all();
    if(isset($param['uid'])){
        $current_user = $param['uid'];
        $conn = Database::getConnection();
        $user = User::load($current_user);
        if(is_array($user->get('user_picture')->getValue()) && !empty($user->get('user_picture')->getValue())){
          $fid = isset($user->get('user_picture')->getValue()[0]['target_id'])?$user->get('user_picture')->getValue()[0]['target_id']:'';
        }

        $field_state =  $user->get('field_state')->value;
        $roles_user = \Drupal::currentUser()->getRoles();
        $query18 = \Drupal::database()->select('mydata', 'md');
        $query18->fields('md');
        $query18->condition('uid', $current_user, '=');
        $results18 = $query18->execute()->fetchAssoc();
       
        $query1 = \Drupal::database()->select('user__field_first_name', 'ufln');
        $query1->addField('ufln', 'field_first_name_value');
        $query1->condition('entity_id', $current_user,'=');
        $results1 = $query1->execute()->fetchAssoc(); 

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

         $date_of_birth =  \Drupal::database()->select('user__field_date_of_birth', 'ufln4');
    $date_of_birth->addField('ufln4', 'field_date_of_birth_value');
    $date_of_birth->condition('entity_id', $current_user, '=');
    $date_of_birth_val = $date_of_birth->execute()->fetchAssoc();

          if(empty($results18)){
          $cityquery1 = \Drupal::database()->select('user__field_state', 'ufln');
          $cityquery1->addField('ufln', 'field_state_value');
          $cityquery1->condition('entity_id', $current_user, '=');
          $cityresults1 = $cityquery1->execute()->fetchAssoc();
          $state=$cityresults1['field_state_value'];
        }else{
          $state=$results18['field_az'];
        }
        $img_id = $results['user_picture_target_id'];
            $file = File::load($img_id);
        if(!empty($file)){
          $url = $file->url();
        }

    }


    $form['#attributes']['class'][] = 'edit-form';
    $form['#prefix'] = '<div class="edit-user">';
    $form['#suffix'] = '</div>';
    $form['username'] = array(
      '#type' => 'textfield',
      '#required' => TRUE,
      '#default_value' => $results4['name'],
    '#prefix'=>'<div class="left_section popup_left_section"><div class="athlete_left"><h3><div class="toggle_icon"><i class="fa fa-minus"></i><i class="fa fa-plus hide"></i></div>Login Information</h3><div class=items_div>',
    '#attributes' => array('disabled'=>true),
      );

     $hd_title = "USER&#39;s Information"; 
     $form['email'] = array(
      '#type' => 'textfield',
      '#placeholder' => t('Email'),
      '#required' => TRUE,
      '#default_value' => $results4['mail'],
      '#attributes' => array('disabled'=>$dis_status),
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
      '#default_value' => $results2['field_last_name_value'],
      '#attributes' => array('disabled'=>true),
      );

     $form['doj'] = array(
      '#placeholder' => 'Date of Birth',
      '#type' => 'textfield',
      '#required' => true,
      '#default_value' => $date_of_birth_val,
      '#format' => 'm/d/Y',
      '#attributes' => array('id' => array('datepicker')),
      );

      // $states = $this->getStates();
      // $form['az'] = array(
      // '#type' => 'select',
      // '#options'=>$states,
      // '#default_value' => $state,
      // '#required' => TRUE,
      // );

      $form['numberone'] = array(
        '#type' => 'textfield',
        '#placeholder' => 'Phone Number',
        #'#required' => TRUE,
         '#default_value' => $results5['field_mobile_value'],
            '#suffix' => '</div></div>',
      ); 

    



  $form['html_image_athlete'] = [
    '#type' => 'markup',
    '#markup' => '</div>
    <div class ="right_section">
      <div class = "athlete_right">
        <h3><div class="toggle_icon">
            <i class="fa fa-minus"></i><i class="fa fa-plus hide"></i>
          </div>My Website Photo
        </h3>
        <div class="edit_dropdown"><a class="drop" >Action<span class="down-arrow fa fa-angle-down"></span></a><ul class="dropdown-menu" style="padding:0"></ul>
        </div>
        <div class=items_div>',
  ];

 $form['image_athlete'] = [
    '#type' => 'managed_file',
    '#upload_validators' => [
        'file_validate_extensions' => ['gif png jpg jpeg'],
        //'file_validate_size' => [25600000], 
    ],
    '#theme' => 'image_widget', 
    '#preview_image_style' => 'medium', 
    '#upload_location' => 'public://',
    '#required' => false,
    '#default_value' => array($fid),
    '#prefix' => '</div>',
    '#suffix' => '<div class="action_bttn">
            <span>Action</span><ul><li>Remove</li></ul>
    </div></div></div>',
    ];
 



 //CHANGE PASSWORD FIELDS
     $form['pass_label'] = array(
      '#type' => 'label',
      '#value' => t('Your password must be at least 8 characters long and contain at least one number and one character'),
    '#prefix' => '</div><div id="changepassdiv" class="changePassword_popup"><div class="popup_header change_password_header"><h3>Change Password <i class="fa fa-times right-icon changepassdiv-modal-close spb_close" aria-hidden="true"></i></h3></div>',
      );
    $form['current_pass'] = array(
      '#type' => 'password',
      '#placeholder' => t('Old Password'),
      );
    $form['newpass'] = array(
      '#type' => 'password',
      '#placeholder' => t('New Password'),
      );
    $form['newpassconfirm'] = array(
      '#type' => 'password',
      '#placeholder' => t('Confirm New Password'),
      );
    
    $form['pass_error'] = array(
      '#type' => 'label',
      '#value' => t('Incorrect entry,please try again.'),
    '#suffix' => '<span class="passerror"> Need more help? Click here </span>',
      );
    $form['changebutton'] = [
    '#type' => 'label',
    '#title' => 'update',
    '#prefix' =>'',
    '#suffix' => '</div></div>',
    '#attributes' => array('id'=>'save_pass','style'=>'cursor:pointer; background:green;padding: 5px;
    border-radius: 3px;'),
    ];
     // $form['label_text'] = array(
     //      '#type' => 'label',
     //      '#title' => 'No longer need your Parent / Guardian on your account and want to remove them? <br> You can request Parent / Guardian removal from your account via our ticketing system.',
     //      '#prefix' => '<div class ="right_section box-pre"><div class="athlete_right">',
     //      '#suffix' => '</div></div>',
     //      '#attributes' => array('id => parent_label'),
     //    );
    //end change password
     $form['actions'] = [
                      '#type' => 'actions',
                    ];
    $form['actions']['submit'] = [
    '#type' => 'submit',
    '#value' => 'SAVE ALL CHANGES',
    '#prefix' => '<div class="bfss_save_all save_all_changes">',
    '#suffix' => '</div>'
    ];

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
      $param = \Drupal::request()->query->all();
      $conn = Database::getConnection();
      $imgid = $form_state->getValue('image_athlete');
      if(isset($param['uid'])){
        $current_user = $param['uid'];
        $user = User::load($current_user);

        $user->mail->value = $form_state->getValue('email');
        $user->field_first_name->value = $form_state->getValue('fname');
        $user->field_last_name->value = $form_state->getValue('lname');
        $user->field_mobile->value = $form_state->getValue('numberone');
        #$user->field_state->value = $form_state->getValue('az');
        $user->field_date_of_birth->value = $form_state->getValue('doj');
         if(isset($imgid[0])){
             $user->set('user_picture', $imgid[0]);  
          }
        $user->save();
        //user profile 
        // $query_pic = \Drupal::database()->select('user__user_picture', 'uup');
        // $query_pic->fields('uup');
        // $query_pic->condition('entity_id', $current_user,'=');
        // $results_pic = $query_pic->execute()->fetchAll(); 
        

        //     if(empty($results_pic)){
        //       if(isset($imgid[0])){
        //        $conn->insert('user__user_picture')->fields(
        //           array(
        //           'entity_id' => $current_user,
        //           'bundle' => 'user',
        //           'deleted' => '0',
        //           'revision_id' => $current_user,
        //           'langcode' => 'en',
        //           'delta' => '0',
        //           'user_picture_target_id' => $imgid[0],
        //           )
        //       )->execute();
        //       }
        //     }else {
        //         if(!empty($imgid[0])){
        //           $conn->update('user__user_picture')
        //           ->condition('entity_id',$current_user,'=')
        //           ->fields(
        //             array(
        //             'user_picture_target_id' => $imgid[0],
        //             )
        //           )
        //           ->execute();
        //         }else{
        //           $conn->update('user__user_picture')
        //           ->condition('entity_id',$current_user,'=')
        //           ->fields(
        //             array(
        //             'user_picture_target_id' => '240',
        //             )
        //           )
        //           ->execute();
        //         }        
        //     }
        
          
      }
  }

     public function getStates() {
      return $st=array(
          'AL'=> t('AL'),
          'AK'=> t('AK'),
          'AZ'=> t('AZ'),
          'AR'=> t('AR'),
          'CA'=> t('CA'),
          'CO'=> t('CO'),
          'CT'=> t('CT'),
          'DE'=> t('DE'),
          'DC'=> t('DC'),
          'FL'=> t('FL'),
          'GA'=> t('GA'),
          'HI'=> t('HI'),
          'ID'=> t('ID'),
           'IL'=> t('IL'),
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