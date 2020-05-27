<?php
/**
 * @file
 * Contains \Drupal\bfss_assessors\Form\StarterProfessionalAssessments.
 */
namespace Drupal\bfss_assessors\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use \Drupal\node\Entity\Node;
use Drupal\Core\Ajax\AjaxResponse;
use Drupal\Core\Ajax\HtmlCommand;
class StarterProfessionalAssessments extends FormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'individual_starter_professional_assessments';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $param = \Drupal::request()->query->all();
    $nid = $param['nid'];
    $formtype = $param['formtype'];
    $Assess_type = $param['Assess_type'];
    $booked_id = $param['booked_id'];
    $st = $param['st'];
    $assess_nid = $param['assess_nid'];
    $first_name = $param['first_name'];
    $last_name = $param['last_name'];
    $sport = $param['sport'];
  if(isset($nid) && isset($formtype) && isset($Assess_type))
    {
            if($formtype == 'starter' && $Assess_type == 'individual'){
              $form_title = 'STARTER/PROFESSIONAL ASSESSMENTS';
            }
            elseif($formtype == 'elete' && $Assess_type == 'individual'){
              $form_title = 'ELITE ASSESSMENT';
            }
            elseif($formtype == 'starter' && $Assess_type == 'private'){
               $form_title = 'STARTER/PROFESSIONAL ASSESSMENTS';
             }
            elseif($formtype == 'elete' && $Assess_type == 'private'){
               $form_title = 'ELITE ASSESSMENT';
             }
         
            $form['#attached']['library'][] = 'bfss_assessors/bfss_assessors';
            $form['#prefix'] = '
            <!-- Modal start-->
                    <div id="assessor_popup_form" class="asse_frm" >
                    
                                    <div class="">
                                      <!-- Modal content-->
                                      <div>
                                        <div id="accessorform">
                                            <div class="accessorform_inner">
                                           <div class="usrinfo"><h3>'.$first_name.' '.$last_name.'</h3><ul><li>'.$sport.'</li><li>'.$param['postion'].'</li></ul></div>
                                             <h2>'.$form_title.'</h2><ul class="st_lk">
                                              <li>EF-Equipment Failure</li>
                                              <li>Al-Athlete Injured</li>
                                              <li>ART-Athlete Refused Test</li>
                                              </ul>';

                    $form['#suffix'] = '  </div>
                                        </div>       
                                      </div>   
                                    </div>
                                </div>
            <!-- Modal end-->';
             $form['message'] = [
              '#type' => 'markup',
              '#markup' => '<div class="result_message"></div>',
            ];
            $form['form_fields_wrap'] = array(
            '#type' => 'fieldset',
           # '#title' => $this->t(''),
            '#prefix' => '<div id="form_fields_wrap" class="form_fields_wrap">',
            '#suffix' => '</div>',
             );
            if( !empty($assess_nid) ){
            //default values here 
            $node = Node::load($assess_nid);
            $starter_weight_rea_str = $node->title->value;
            $field_jump_height_in_reactive = $node->field_jump_height_in_reactive->value;
            $field_rsi_reactive = $node->field_rsi_reactive->value;
            $field_jump_height_in_elastic = $node->field_jump_height_in_elastic->value;
            $field_peak_propulsive_elastic = $node->field_peak_propulsive_elastic->value;
            $field_peak_power_w_elastic = $node->field_peak_power_w_elastic->value;
            $field_jump_height_in_ballistic = $node->field_jump_height_in_ballistic->value;
            $field_peak_propulsive_ballistic = $node->field_peak_propulsive_ballistic->value;
            $field_peak_power_w_ballistic = $node->field_peak_power_w_ballistic->value;
            $field_10m_time_sec_sprint = $node->field_10m_time_sec_sprint->value;
            $field_40m_time_sec_sprint = $node->field_40m_time_sec_sprint->value;
            $field_peak_force_n_maximal = $node->field_peak_force_n_maximal->value;
            $field_rfd_100ms_n_maximal = $node->field_rfd_100ms_n_maximal->value;
            $field_assessment_type = $node->field_assessment_type->value;
            $field_form_type = $node->field_form_type->value;
            $field_athelete_nid = $node->field_athelete_nid->value;
            $field_booked_id = $node->field_booked_id->value;
            $field_power_w_ssm_ipe = $node->field_power_w_ssm_ipe->value;
            $field_power_w_spm_ipe = $node->field_power_w_spm_ipe->value;
            $field_power_w_rm_ipe = $node->field_power_w_rm_ipe->value;
            $field_repetitions_se_ipe = $node->field_repetitions_se_ipe->value;
            $field_power_w_cfd_ipe = $node->field_power_w_cfd_ipe->value;
            }
          	//Reactive Strength (CM Rebound Jump)
          	$form['form_fields_wrap']['reactive_strength'] = array(
        	  '#type' => 'fieldset',
        	  '#title' => $this->t('Reactive Strength (CM Rebound Jump)'),

        	  '#prefix' => '<div id="reactive_strength" class="sm_cls">',
        	  '#suffix' => '</div>',
        	   );

            $form['form_fields_wrap']['reactive_strength']['starter_weight_rea_str'] = array(
              '#type' => 'textfield',
              '#default_value' => $starter_weight_rea_str,
              #'#title' => t('Weight (N) Calculated into Ibs'),
              '#required' => TRUE,
              '#attributes' => array(
                'placeholder' => t('Weight (N) Calculated into Ibs'),
              ),
            );

            $form['form_fields_wrap']['reactive_strength']['starter_jump_height_rea_str'] = array(
              '#type' => 'textfield',
              #'#title' => t('Jump Height (ln)'),
              '#default_value' => $field_jump_height_in_reactive,
              #'#required' => TRUE,
              '#attributes' => array(
                'placeholder' => t('Jump Height (ln)'),
              ),
            );
            $form['form_fields_wrap']['reactive_strength']['starter_rsi_rea_str'] = array (
              '#type' => 'textfield',
              #'#title' => t('RSI'),
              '#default_value' => $field_rsi_reactive,
              #'#required' => TRUE,
              '#attributes' => array(
                'placeholder' => t('RSI'),
              ),
            );

            //Elastic Strenght (Countermovement Jump)
        	$form['form_fields_wrap']['elastic_strenght'] = array(
        		  '#type' => 'fieldset',
        		  '#title' => $this->t('Elastic Strength (Countermovement Jump)'),
        		  '#prefix' => '<div id="elastic_strenght" class="sm_cls">',
        		  '#suffix' => '</div>',
        	);
            $form['form_fields_wrap']['elastic_strenght']['starter_jump_height_ela_str'] = array (
              '#type' => 'textfield',
              '#default_value' => $field_jump_height_in_elastic,
              #'#title' => t('Jump Height (ln)'),
              #'#required' => TRUE,
              '#attributes' => array(
                'placeholder' => t('Jump Height (ln)'),
              ),
            );

            $form['form_fields_wrap']['elastic_strenght']['elastic_strenght']['starter_peak_pro_ela_str'] = array (
              '#type' => 'textfield',
              '#default_value' => $field_peak_propulsive_elastic,
              #'#required' => TRUE,
              '#attributes' => array(
                'placeholder' => t('Peak Propulslve Force (N)'),
              ),
            );
            $form['form_fields_wrap']['elastic_strenght']['starter_peak_power_ela_str'] = array (
              '#type' => 'textfield',
              '#default_value' => $field_peak_power_w_elastic,
              #'#required' => TRUE,
              '#attributes' => array(
                'placeholder' => t('Peak Power (W)'),
              ),
            );

            //Ballistic Strength (Squat Jump)
            $form['form_fields_wrap']['ballistic_strength'] = array(
        		  '#type' => 'fieldset',
        		  '#title' => $this->t('Ballistic Strength (Squat Jump)'),
        		  '#prefix' => '<div id="ballistic_strength" class="sm_cls">',
        		  '#suffix' => '</div>',
        	);
            $form['form_fields_wrap']['ballistic_strength']['starter_jump_height_ballistic'] = array (
              '#type' => 'textfield',
              '#default_value' => $field_jump_height_in_ballistic,
              #'#required' => TRUE,
              '#attributes' => array(
                'placeholder' => t('Jump Height (ln)'),
              ),
            );

            $form['form_fields_wrap']['ballistic_strength']['starter_peak_pro_ballistic'] = array (
              '#type' => 'textfield',
              '#default_value' => $field_peak_propulsive_ballistic,
              #'#required' => TRUE,
              '#attributes' => array(
                'placeholder' => t('Peak Propulslve Force (N)'),
              ),
            );

            $formv['ballistic_strength']['starter_peak_power_ballistic'] = array (
              '#type' => 'textfield',
              '#default_value' => $field_peak_power_w_ballistic,
              #'#required' => TRUE,
              '#attributes' => array(
                'placeholder' => t('Peak Power (W)'),
              ),
            );

            // 10M/40M Sprint
            $form['form_fields_wrap']['10m_40m_sprint'] = array(
        		  '#type' => 'fieldset',
        		  '#title' => $this->t('10M/40M Sprint'),
        		  '#prefix' => '<div id="10m_40m" class="sm_cls">',
        		  '#suffix' => '</div>',
        	);

            $form['form_fields_wrap']['10m_40m_sprint'] ['starter_10m'] = array (
              '#type' => 'textfield',
              '#default_value' => $field_10m_time_sec_sprint,
              #'#required' => TRUE,
              '#attributes' => array(
                'placeholder' => t('10 M Time (sec)'),
              ),
            );

            $form['form_fields_wrap']['10m_40m_sprint'] ['starter_40m'] = array (
              '#type' => 'textfield',
              '#default_value' => $field_40m_time_sec_sprint,
              #'#required' => TRUE,
              '#attributes' => array(
                'placeholder' => t('40 M Time (sec)'),
              ),
            );

            // Maximal Strength (Isometric Mid-Thigh Pull)
            $form['form_fields_wrap']['maximal_strength'] = array(
        		  '#type' => 'fieldset',
        		  '#title' => $this->t('Maximal Strength (Isometric Mid-Thigh Pull)'),
        		  '#prefix' => '<div id="10m_40m" class="sm_cls">',
        		  '#suffix' => '</div>',
        	);

            $form['form_fields_wrap']['maximal_strength']['starter_peak_for_max'] = array (
              '#type' => 'textfield',
              '#default_value' => $field_peak_force_n_maximal,
              #'#required' => TRUE,
              '#attributes' => array(
                'placeholder' => t('Peak Force (N)'),
              ),
            );

             $form['form_fields_wrap']['maximal_strength']['starter_rfd_max'] = array (
              '#type' => 'textfield',
              '#default_value' => $field_rfd_100ms_n_maximal,
              #'#required' => TRUE,
              '#attributes' => array(
                'placeholder' => t('RFD @ 100ms (N)'),
              ),
            );

             /*********** elete fields start ************/
                       if($formtype == 'elete'){
                          $required = TRUE;
                       }else{
                          $required = FALSE;
                       }
            if($formtype == 'elete'){
                //UE Power (SPM Ball Throw)
                $form['form_fields_wrap']['ue_power'] = array(
                  '#type' => 'fieldset',
                  '#title' => $this->t('UE Power (SSM Ball Throw)'),
                  '#prefix' => '<div id="ue_power1" class="sm_cls">',
                  '#suffix' => '</div>',
                );

                $form['form_fields_wrap']['ue_power']['power'] = array (
                  '#type' => 'textfield',
                  '#default_value' => $field_power_w_ssm_ipe,
                  #'#required' => $required,
                  '#attributes' => array(
                    'placeholder' => t('Power (W)'),
                  ),
                );

                 //UE Power (SPM Ball Throw)
                 $form['form_fields_wrap']['ue_power_spm'] = array(
                  '#type' => 'fieldset',
                  '#title' => $this->t('UE Power (SPM Ball Throw)'),
                  '#prefix' => '<div id="ue_power2" class="sm_cls">',
                  '#suffix' => '</div>',
                );
                
                $form['form_fields_wrap']['ue_power_spm']['power_spm'] = array (
                  '#type' => 'textfield',
                  '#default_value' => $field_power_w_spm_ipe,
                  #'#required' => $required,
                  '#attributes' => array(
                    'placeholder' => t('Power (W)'),
                  ),
                );
                //UE Power (RM Ball Throw)
                 $form['form_fields_wrap']['ue_power_rm'] = array(
                  '#type' => 'fieldset',
                  '#title' => $this->t('UE Power (RM Ball Throw)'),
                  '#prefix' => '<div id="ue_power3" class="sm_cls">',
                  '#suffix' => '</div>',
                );
                $form['form_fields_wrap']['ue_power_rm']['power_rm'] = array (
                  '#type' => 'textfield',
                  '#default_value' => $field_power_w_rm_ipe,
                  #'#required' => $required,
                  '#attributes' => array(
                    'placeholder' => t('Power (W)'),
                  ),
                );

                //Strength Endurance (Pull-ups,Push-ups,Single Leg Squats)
                $form['form_fields_wrap']['strength_endurance'] = array(
                  '#type' => 'fieldset',
                  '#title' => $this->t('Strength Endurance (Pull-ups, Push-ups, Single Leg Squats)'),
                  '#prefix' => '<div id="strength_endurance" class="sm_cls">',
                  '#suffix' => '</div>',
                );

                 $form['form_fields_wrap']['strength_endurance']['repetitions'] = array (
                  '#type' => 'textfield',
                  '#default_value' => $field_repetitions_se_ipe,
                  #'#required' => $required,
                  '#attributes' => array(
                    'placeholder' => t('Repetitions (#)'),
                  ),
                );

                //Change of Direction (5-10-5 Pro Agility Test)
                   $form['form_fields_wrap']['change_of_direction'] = array(
                  '#type' => 'fieldset',
                  '#title' => $this->t('Change of Direction (5-10-5 Pro Agility Test)'),
                  '#prefix' => '<div id="change_of_direction" class="sm_cls">',
                  '#suffix' => '</div>',
                );

                 $form['form_fields_wrap']['change_of_direction']['power_ch'] = array (
                  '#type' => 'textfield',
                  '#default_value' => $field_power_w_cfd_ipe,
                  #'#required' => $required,
                  '#attributes' => array(
                    'placeholder' => t('Power (W)'),
                  ),
                );
            }
            /*********** elete fields start ************/

             //hidden fields

            if($formtype == 'elete'){  
             $formtype_val = 'elete';
            }elseif($formtype == 'starter'){
             $formtype_val = 'starter';
            }else{
              $formtype_val = '';
            }

            if($Assess_type == 'individual'){  
             $Assess_type_val = 'individual';
            }elseif($Assess_type == 'private'){
             $Assess_type_val = 'private';
            }else{
              $Assess_type_val = '';
            }

            $form['assessment_type'] = array(
             '#type' => 'hidden',
             '#value' => 'individual',
            );

            $form['form_type'] = array(
             '#type' => 'hidden',
             '#value' => $formtype_val,
            );

            $form['booked_id'] = array(
             '#type' => 'hidden',
             '#value' => $booked_id,
            );
            
             $form['athelete_nid'] = array(
             '#type' => 'hidden',
             '#value' => $nid,
            );



            $form['actions']['#type'] = 'actions';
            $form['actions']['draft'] = array(
              '#type' => 'submit',
              '#value' => $this->t('SAVE - INCOMPLETE'),
              '#button_type' => 'primary',
               '#ajax' => [
                  'callback' => '::submitForm', // don't forget :: when calling a class method.
                  //'callback' => [$this, 'myAjaxCallback'], //alternative notation
                  'disable-refocus' => FALSE, // Or TRUE to prevent re-focusing on the triggeringuse Drupal\Core\Ajax\HtmlCommand; element.
                  'event' => 'click',
                  'wrapper' => 'edit-output', // This element is updated with this AJAX callback.
                  'progress' => [
                    'type' => 'throbber',
                    'message' => $this->t('Verifying entry...'),
                  ],
                ]
            );
            $form['actions']['submit'] = array(
              '#type' => 'submit',
              '#value' => $this->t('SAVE - ALL FIELDS COMPLETED'),
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
            );

            return $form;
      }else{
        return;
      }
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
        $triggerElement = $form_state->getTriggeringElement();
        if(!empty($param)){
           $nid = $param['nid'];
           $formtype = $param['formtype'];
           $Assess_type = $param['Assess_type'];
           $booked_id = $param['booked_id'];
           $st = $param['st']; 
           $assess_nid = $param['assess_nid'];
        } 
        //check node already exist
        $query1 = \Drupal::entityQuery('node');
        $query1->condition('type', 'athlete_assessment_info');
        $query1->condition('field_booked_id',$booked_id, 'IN');
        $nids1 = $query1->execute();
        	
    		
        //current user
        $current_user = \Drupal::currentUser();
        $user_id = $current_user->id();
        $user = \Drupal\user\Entity\User::load($user_id);

  	  	$form_data = [];
  	  	foreach ($form_state->getValues() as $key => $value) {
  	  	 	$form_data[$key] = $value;
  	  	}
        
        if(!empty($assess_nid) && $st == 1){
          //update here
          $node = Node::load($assess_nid);
        }else{
          //insert insert here
        	if(empty($nids1)){
	          $node = Node::create([
	             'type' => 'athlete_assessment_info',
	          ]); 
      		}
        } 
        $node->set('title', $form_data['starter_weight_rea_str']);
        $node->set('field_jump_height_in_reactive', $form_data['starter_jump_height_rea_str']);
        $node->set('field_rsi_reactive', $form_data['starter_rsi_rea_str']);
        $node->set('field_jump_height_in_elastic', $form_data['starter_jump_height_ela_str']);
        $node->set('field_peak_propulsive_elastic', $form_data['starter_peak_pro_ela_str']);
        $node->set('field_peak_power_w_elastic', $form_data['starter_peak_power_ela_str']);
        $node->set('field_jump_height_in_ballistic', $form_data['starter_jump_height_ballistic']);
        $node->set('field_peak_propulsive_ballistic', $form_data['starter_peak_pro_ballistic']);
        $node->set('field_peak_power_w_ballistic', $form_data['starter_peak_power_ballistic']);
        $node->set('field_10m_time_sec_sprint', $form_data['starter_10m']);
        $node->set('field_40m_time_sec_sprint', $form_data['starter_40m']);
        $node->set('field_peak_force_n_maximal', $form_data['starter_peak_for_max']);
        $node->set('field_rfd_100ms_n_maximal', $form_data['starter_rfd_max']);
        $node->set('field_assessment_type', $form_data['assessment_type']);
        $node->set('field_form_type', $form_data['form_type']);
        $node->set('field_athelete_nid', $form_data['athelete_nid']);
        $node->set('field_booked_id', $form_data['booked_id']);
        //aditional fields for elete
        $node->set('field_power_w_ssm_ipe', $form_data['power']);
        $node->set('field_power_w_spm_ipe', $form_data['power_spm']);
        $node->set('field_power_w_rm_ipe', $form_data['power_rm']);
        $node->set('field_repetitions_se_ipe', $form_data['repetitions']);
        $node->set('field_power_w_cfd_ipe', $form_data['power_ch']);
        //user target id 
        $node->set('field_user', ['target_id' => $user_id]);
  	  
      if (isset($triggerElement['#id']) && strpos($triggerElement['#id'], 'edit-submit') !== false) { 
            // if "SAVE - ALL FIELDS COMPLETED" trigger
            $node->set('field_status', 'complete');
           if (!is_numeric($form_state->getValue('starter_jump_height_rea_str')) || empty($form_state->getValue('starter_jump_height_rea_str'))) {
              $message = '<p style="color:red;">"Jump Height (ln)" Required or Numeric</p>';
           }
           elseif(!is_numeric($form_state->getValue('starter_weight_rea_str')) || empty($form_state->getValue('starter_weight_rea_str'))){
            $message = '<p style="color:red;">"Weight (N) Calculated into Ibs" Required or Numeric</p>';
           }
           elseif(!is_numeric($form_state->getValue('starter_rsi_rea_str')) || empty($form_state->getValue('starter_rsi_rea_str'))){
             $message = '<p style="color:red;">"RSI" Required or Numeric</p>';
           }
           elseif(!is_numeric($form_state->getValue('starter_jump_height_ela_str')) || empty($form_state->getValue('starter_jump_height_ela_str'))){
             $message = '<p style="color:red;">"Jump Height (ln)" Required or Numeric</p>';
           }
           elseif(!is_numeric($form_state->getValue('starter_peak_pro_ela_str')) || empty($form_state->getValue('starter_peak_pro_ela_str'))){
             $message = '<p style="color:red;">"Peak Propulslve Force (N)" Required or Numeric</p>';
           }
            elseif(!is_numeric($form_state->getValue('starter_peak_power_ela_str')) || empty($form_state->getValue('starter_peak_power_ela_str'))){
             $message = '<p style="color:red;">"Peak Power (W)" Required or Numeric</p>';
           }
            elseif(!is_numeric($form_state->getValue('starter_jump_height_ballistic')) || empty($form_state->getValue('starter_jump_height_ballistic'))){
             $message = '<p style="color:red;">"Jump Height (ln)" Required or Numeric</p>';
           }
            elseif(!is_numeric($form_state->getValue('starter_peak_pro_ballistic')) || empty($form_state->getValue('starter_peak_pro_ballistic'))){
             $message = '<p style="color:red;">"Peak Propulslve Force (N)" Required or Numeric</p>';
           }
            elseif(!is_numeric($form_state->getValue('starter_10m')) || empty($form_state->getValue('starter_10m'))){
             $message = '<p style="color:red;">"10 M Time (sec)" Required or Numeric</p>';
           }
            elseif(!is_numeric($form_state->getValue('starter_40m')) || empty($form_state->getValue('starter_40m'))){
             $message = '<p style="color:red;">"40 M Time (sec)" Required or Numeric</p>';
           }
           elseif(!is_numeric($form_state->getValue('starter_peak_for_max')) || empty($form_state->getValue('starter_peak_for_max'))){
             $message = '<p style="color:red;">"Peak Force (N)" Required or Numeric</p>';
           }elseif(!is_numeric($form_state->getValue('starter_rfd_max')) || empty($form_state->getValue('starter_rfd_max'))){
             $message = '<p style="color:red;">"RFD @ 100ms (N)" Required or Numeric</p>';
           }
           elseif( (!is_numeric($form_state->getValue('power')) || empty($form_state->getValue('power'))) && $formtype == 'elete' ){

           
             $message = '<p style="color:red;">"Power (W)" Required or Numeric</p>';
            

           }
           elseif((!is_numeric($form_state->getValue('power_spm')) || empty($form_state->getValue('power_spm'))) && $formtype == 'elete'){
           
             $message = '<p style="color:red;">"Power (W)" Required or Numeric</p>';
            }
           elseif( (!is_numeric($form_state->getValue('power_rm')) || empty($form_state->getValue('power_rm'))) && $formtype == 'elete'){
            
              $message = '<p style="color:red;">"Power (W)" Required or Numeric</p>'; 
           
           }
            elseif((!is_numeric($form_state->getValue('repetitions')) || empty($form_state->getValue('repetitions'))) && $formtype == 'elete'){
             $message = '<p style="color:red;">"Repetitions (#)" Required or Numeric</p>';   
           }elseif((!is_numeric($form_state->getValue('power_ch')) || empty($form_state->getValue('power_ch'))) && $formtype == 'elete'){  
             $message = '<p style="color:red;">"Power (W)" Required or Numeric</p>';
           }
           else{
             $message = 'Saved successfully!';
              $node->setPublished(TRUE);
              $node->save(); 	
              
           }
      }

      if (isset($triggerElement['#id']) && strpos($triggerElement['#id'], 'edit-draft') !== false ) {
          // if "SAVE - INCOMPLETE" button trigger
           if(!is_numeric($form_state->getValue('starter_weight_rea_str')) || empty($form_state->getValue('starter_weight_rea_str'))){
              $message = '<p style="color:red;">"Weight (N) Calculated into Ibs" Required</p>';
           }else{
              $node->set('field_status', 'incomplete');
              $message = 'Saved successfully!';
              $node->setPublished(FALSE);
              $node->save();
           }  
      }

      // for success message show
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