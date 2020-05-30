<?php
/**
 * @file
 * Contains \Drupal\bfss_manager\Form\PendingAssessmentsForm.
 */
namespace Drupal\bfss_manager\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use \Drupal\node\Entity\Node;
use Drupal\Core\Ajax\AjaxResponse;
use Drupal\Core\Ajax\HtmlCommand;
class PendingAssessmentsForm extends FormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'pending_assessments_form';
  }

  /**
   * Use this to extract "professional", because $param['formtype'] only contains 'starter' OR 'elite'
   */
  public function getFormTypeFromPrice($assessmentPrice) {
    $realFormType = '';
    if($assessmentPrice == '199.99'){
      $realFormType = 'elete';
    }elseif($assessmentPrice == '29.99'){
      $realFormType = 'starter';
    }elseif($assessmentPrice == '69.99'){
      $realFormType = 'professional';
    }
    return $realFormType;
  }

  /**
   * Returns a drupal-style textfield - which is an array of values.
   * @param string $fieldName
   *   Name of the form field, also is the name of the default value (if any)
   * @param int $fieldColumn
   *   0 - for the field to appear on the left
   *   1 - for the field to appear on the right
   * @param string $defaultValue
   *   Optional. Sets the textfield's value attribute.
   * @param string $placeholder
   *   Optional. The text that appears within the textfield when empty.
   */
  public function getFormField($fieldName, $fieldColumn, $defaultValue = '', $placeholder = '') {
    $fieldAry = [];

    $style = ($fieldColumn == 0) ? 'width: 62%;' : 'position: absolute; width: 42%; right: 7.1%;';

    $fieldAry[$fieldName] = array(
      '#type' => 'textfield',
      '#default_value' => $defaultValue,
      #'#required' => TRUE,
      '#attributes' => array(
        'placeholder' => t($placeholder),
        'style' => $style,
      ),
    );
    // TODO: Try to integrate this COOL Right-aligned "units" text, after textfield. -- ie "Secs" or "In"
    //    array('#markup' => '<span style="margin-left:-24px; background-color: grey; color: white; padding-left:3px; padding-right: 3px;">Inch</span>', );
    return $fieldAry;
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
    if(isset($nid) && isset($formtype) && isset($Assess_type))
    {
            //jody - use this to extract "professional" (because $formtype only contains 'starter' OR 'elite')
            $entity = \Drupal\bfss_assessment\Entity\BfssPayments::load($booked_id);

            $sport = $param['sport'] || $entity->mydata->field_sport->value;
            // $tmp_user_id = $entity->user_id->value;

            $realFormType = $param['formtype'];
            $realFormType = $this->getFormTypeFromPrice($entity->service->value);

            if($realFormType == 'starter' && $Assess_type == 'individual'){
              $form_title = 'STARTER ASSESSMENT';
            }
            elseif($realFormType == 'professional' && $Assess_type == 'individual'){
              $form_title = 'PROFESSIONAL ASSESSMENT';
            }
            elseif($realFormType == 'elete' && $Assess_type == 'individual'){
              $form_title = 'ELITE ASSESSMENT';
            }
            elseif($realFormType == 'starter' && $Assess_type == 'private'){
              $form_title = 'STARTER ASSESSMENT';
            }
            elseif($realFormType == 'professional' && $Assess_type == 'private'){
              $form_title = 'PROFESSIONAL ASSESSMENT';
            }
            elseif($realFormType == 'elete' && $Assess_type == 'private'){
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
                          <h2>'.$form_title.'</h2>
                          <ul class="st_lk">
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
              '#markup' => '<div class="result_message form_fields_wrap"></div>',
            ];

            if( !empty($assess_nid) ) {
              //default values here
              $node = Node::load($assess_nid);
            }
            else {
              $node = array();
            }

            $defaultValues = array();
            // ------------------------------------------------------------------------------------------------ // LEGACY form field names
            $defaultValues['starter_weight_rea_str']          = @$node->title->value;                           // starter_weight_rea_str
            $defaultValues['field_jump_height_in_reactive']   = @$node->field_jump_height_in_reactive->value;   // starter_jump_height_rea_str
            $defaultValues['field_rsi_reactive']              = @$node->field_rsi_reactive->value;              // starter_rsi_rea_str
            $defaultValues['field_jump_height_in_elastic']    = @$node->field_jump_height_in_elastic->value;    // starter_jump_height_ela_str
            $defaultValues['field_peak_propulsive_elastic']   = @$node->field_peak_propulsive_elastic->value;   // starter_peak_pro_ela_str
            $defaultValues['field_peak_power_w_elastic']      = @$node->field_peak_power_w_elastic->value;      // starter_peak_power_ela_str
            $defaultValues['field_jump_height_in_ballistic']  = @$node->field_jump_height_in_ballistic->value;  // starter_jump_height_ballistic
            $defaultValues['field_peak_propulsive_ballistic'] = @$node->field_peak_propulsive_ballistic->value; // starter_peak_pro_ballistic
            $defaultValues['field_peak_power_w_ballistic']    = @$node->field_peak_power_w_ballistic->value;    // starter_peak_power_ballistic
            $defaultValues['field_10m_time_sec_sprint']       = @$node->field_10m_time_sec_sprint->value;       // starter_10m
            $defaultValues['field_40m_time_sec_sprint']       = @$node->field_40m_time_sec_sprint->value;       // starter_40m
            $defaultValues['field_peak_force_n_maximal']      = @$node->field_peak_force_n_maximal->value;      // starter_peak_for_max
            $defaultValues['field_rfd_100ms_n_maximal']       = @$node->field_rfd_100ms_n_maximal->value;       // starter_rfd_max
            $defaultValues['field_assessment_type']           = @$node->field_assessment_type->value;           // assessment_type
            $defaultValues['field_form_type']                 = @$node->field_form_type->value;                 // form_type
            $defaultValues['field_athelete_nid']              = @$node->field_athelete_nid->value;              // athelete_nid
            $defaultValues['field_booked_id']                 = @$node->field_booked_id->value;                 // booked_id
            $defaultValues['field_power_w_ssm_ipe']           = @$node->field_power_w_ssm_ipe->value;           // ['ue_power']['power']
            $defaultValues['field_power_w_spm_ipe']           = @$node->field_power_w_spm_ipe->value;           // ['ue_power_spm']['power_spm']
            $defaultValues['field_power_w_rm_ipe']            = @$node->field_power_w_rm_ipe->value;            // ['ue_power_rm']['power_rm']
            $defaultValues['field_repetitions_se_ipe']        = @$node->field_repetitions_se_ipe->value;        // ['strength_endurance']['repetitions']
            $defaultValues['field_power_w_cfd_ipe']           = @$node->field_power_w_cfd_ipe->value;           // ['change_of_direction']['power_ch']

            /***************************************
             * TODO
             *    Field names above are origional database field names, which appear below in $fieldName.
             *    $fieldName's below (that aren't above) need to be added to the database.  Feel free to rename or organize any way you want.
            * ****************************/


            if($realFormType == 'starter')
            {
              //~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
              $form['field_wrap_1'] = array(
                '#type' => 'fieldset',
                #'#title' => $this->t('<div style="text-align: center;"></div>'),
                '#prefix' => '<div id="form_fields_wrap" class="form_fields_wrap">',
                '#suffix' => '</div>',
              );

              $fieldName = 'starter_weight_rea_str';
              $form['field_wrap_1'][$fieldName] = $this->getFormField($fieldName, 0, $defaultValues[$fieldName], 'REACTIVE STRENGTH (In)'); // 5
              $fieldName = 'field_jump_height_in_elastic';
              $form['field_wrap_1'][$fieldName] = $this->getFormField($fieldName, 1, $defaultValues[$fieldName], 'ELASTIC STRENGTH (In)'); // 6

              $fieldName = 'field_jump_height_in_ballistic';
              $form['field_wrap_1'][$fieldName] = $this->getFormField($fieldName, 0, $defaultValues[$fieldName], 'BALLISTIC STRENGTH (In)'); // 7
              $fieldName = 'field_10m_time_sec_sprint';
              $form['field_wrap_1'][$fieldName] = $this->getFormField($fieldName, 1, $defaultValues[$fieldName], 'ACCELERATION/SPEED (Sec)'); // 8

              $fieldName = 'field_peak_force_n_maximal';
              $form['field_wrap_1'][$fieldName] = $this->getFormField($fieldName, 0, $defaultValues[$fieldName], 'MAXIMAL STRENGTH (N)');  // 9
              $fieldName = 'field_rsi_reactive';
              $form['field_wrap_1'][$fieldName] = $this->getFormField($fieldName, 1, $defaultValues[$fieldName], '(E) REACTIVE STRENGTH (In)'); // 10

              $fieldName = 'starter_weight_rea_str_B';
              $form['field_wrap_1'][$fieldName] = $this->getFormField($fieldName, 0, $defaultValues[$fieldName], '(B) REACTIVE STRENGTH'); // 11
              $fieldName = 'field_elite_age_E';
              $form['field_wrap_1'][$fieldName] = $this->getFormField($fieldName, 1, $defaultValues[$fieldName], 'ELITE PERFORMERS AGE - test'); // *ELITE PERFORMERS AGE
            }

            else if($realFormType == 'professional')
            {

              //~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ ASSESSMENT TABLE

              $form['field_wrap_1'] = array(
                '#type' => 'fieldset',
                '#title' => $this->t('<div style="text-align: center; font-weight: bold;">ASSESSMENT TABLE</div>'),
                '#prefix' => '<div id="form_fields_wrap" class="form_fields_wrap">',
                '#suffix' => '</div>',
              );

              $fieldName = 'field_jump_height_in_reactive';
              $form['field_wrap_1'][$fieldName] = $this->getFormField($fieldName, 0, $defaultValues[$fieldName], 'REACTIVE STRENGTH (In)'); // 5
              $fieldName = 'field_jump_height_in_elastic';
              $form['field_wrap_1'][$fieldName] = $this->getFormField($fieldName, 1, $defaultValues[$fieldName], 'ELASTIC STRENGTH (In)'); // 6

              $fieldName = 'field_jump_height_in_ballistic';
              $form['field_wrap_1'][$fieldName] = $this->getFormField($fieldName, 0, $defaultValues[$fieldName], 'BALLISTIC STRENGTH (In)'); // 7
              $fieldName = 'field_10m_time_sec_sprint';
              $form['field_wrap_1'][$fieldName] = $this->getFormField($fieldName, 1, $defaultValues[$fieldName], 'ACCELERATION/SPEED (Sec)'); // 8

              $fieldName = 'field_peak_force_n_maximal';
              $form['field_wrap_1'][$fieldName] = $this->getFormField($fieldName, 0, $defaultValues[$fieldName], 'MAXIMAL STRENGTH (N)'); // 9
              $fieldName = 'field_rsi_reactive';
              $form['field_wrap_1'][$fieldName] = $this->getFormField($fieldName, 1, $defaultValues[$fieldName], '(E) REACTIVE STRENGTH (In)'); // 10

              $fieldName = 'field_jump_height_in_elastic_E';
              $form['field_wrap_1'][$fieldName] = $this->getFormField($fieldName, 0, $defaultValues[$fieldName], '(E) ELASTIC STRENGTH (In)'); // 11
              $fieldName = 'field_jump_height_in_ballistic_E';
              $form['field_wrap_1'][$fieldName] = $this->getFormField($fieldName, 1, $defaultValues[$fieldName], '(E) BALLISTIC STRENGTH (In)'); // 12

              $fieldName = 'field_10m_time_sec_sprint_E';
              $form['field_wrap_1'][$fieldName] = $this->getFormField($fieldName, 0, $defaultValues[$fieldName], '(E) ACCELERATION/SPEED (Secs)'); // 13
              $fieldName = 'field_peak_force_n_maximal_E';
              $form['field_wrap_1'][$fieldName] = $this->getFormField($fieldName, 1, $defaultValues[$fieldName], '(E) MAXIMAL STRENGTH (Lbs)'); // 14

              $fieldName = 'field_rsi_reactive_B';
              $form['field_wrap_1'][$fieldName] = $this->getFormField($fieldName, 0, $defaultValues[$fieldName], '(B) REACTIVE STRENGTH'); // 15
              $fieldName = 'field_jump_height_in_elastic_B';
              $form['field_wrap_1'][$fieldName] = $this->getFormField($fieldName, 1, $defaultValues[$fieldName], '(B) ELASTIC STRENGTH'); // 16

              $fieldName = 'field_jump_height_in_ballistic_B';
              $form['field_wrap_1'][$fieldName] = $this->getFormField($fieldName, 0, $defaultValues[$fieldName], '(B) BALLISTIC STRENGTH'); // 17
              $fieldName = 'field_10m_time_sec_sprint_B';
              $form['field_wrap_1'][$fieldName] = $this->getFormField($fieldName, 1, $defaultValues[$fieldName], '(B) ACCELERATION/SPEED'); // 18

              $fieldName = 'field_peak_force_n_maximal_B';
              $form['field_wrap_1'][$fieldName] = $this->getFormField($fieldName, 0, $defaultValues[$fieldName], '(B) MAXIMAL STRENGTH'); // 19
              $fieldName = 'field_elite_age_E';
              $form['field_wrap_1'][$fieldName] = $this->getFormField($fieldName, 1, $defaultValues[$fieldName], 'ELITE PERFORMERS AGE'); // *ELITE PERFORMERS AGE


              //~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ REBOUND JUMP

              $form['field_wrap_2'] = array(
                '#type' => 'fieldset',
                '#title' => $this->t('<div style="text-align: center; font-weight: bold;">REBOUND JUMP</div>'),
                '#prefix' => '<div id="form_fields_wrap" class="form_fields_wrap">',
                '#suffix' => '</div>',
              );

              $fieldName = 'rebound_jump_rank';
              $form['field_wrap_2'][$fieldName] = $this->getFormField($fieldName, 0, $defaultValues[$fieldName], 'YOUR RANK IS (Good, ...)'); // 20
              $fieldName = 'rebound_jump_rsi';
              $form['field_wrap_2'][$fieldName] = $this->getFormField($fieldName, 1, $defaultValues[$fieldName], 'RSI SCORE (#.#)'); // 21

              $fieldName = 'rebound_jump_height';
              $form['field_wrap_2'][$fieldName] = $this->getFormField($fieldName, 0, $defaultValues[$fieldName], 'JUMP HEIGHT (In)'); // 22
              $fieldName = 'rebound_jump_ground_contact_time';
              $form['field_wrap_2'][$fieldName] = $this->getFormField($fieldName, 1, $defaultValues[$fieldName], 'GROUND CONTACT TIME (ms)'); // 23

              $fieldName = 'rebound_jump_low_rsi';
              $form['field_wrap_2'][$fieldName] = $this->getFormField($fieldName, 0, $defaultValues[$fieldName], 'LOW RSI (>1.5)'); // 24
              $fieldName = 'rebound_jump_medium_rsi';
              $form['field_wrap_2'][$fieldName] = $this->getFormField($fieldName, 1, $defaultValues[$fieldName], 'MEDIUM RSI (1.5-2.0)'); // 25

              $fieldName = 'rebound_jump_high_rsi';
              $form['field_wrap_2'][$fieldName] = $this->getFormField($fieldName, 0, $defaultValues[$fieldName], 'HIGH RSI (2.0-2.5)'); // 26


              //~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ COUNTER MOVEMENT JUMP

              $form['field_wrap_3'] = array(
                '#type' => 'fieldset',
                '#title' => $this->t('<div style="text-align: center; font-weight: bold;">COUNTER MOVEMENT JUMP</div>'),
                '#prefix' => '<div id="form_fields_wrap" class="form_fields_wrap">',
                '#suffix' => '</div>',
              );

              $fieldName = 'counter_movement_rank';
              $form['field_wrap_3'][$fieldName] = $this->getFormField($fieldName, 0, $defaultValues[$fieldName], 'YOUR RANK IS (Good, ...)'); // 27
              $fieldName = 'counter_movement_cmj_height';
              $form['field_wrap_3'][$fieldName] = $this->getFormField($fieldName, 1, $defaultValues[$fieldName], 'CMJ HEIGHT (In)'); // 28

              $fieldName = 'counter_movement_cmj_height_E';
              $form['field_wrap_3'][$fieldName] = $this->getFormField($fieldName, 0, $defaultValues[$fieldName], '(E) CMJ HEIGHT (In)'); // 29
              $fieldName = 'counter_movement_cmj_force';
              $form['field_wrap_3'][$fieldName] = $this->getFormField($fieldName, 1, $defaultValues[$fieldName], 'CMJ PEAK FORCE (N)'); // 30

              $fieldName = 'counter_movement_cmj_force_E';
              $form['field_wrap_3'][$fieldName] = $this->getFormField($fieldName, 0, $defaultValues[$fieldName], '(E) CMJ PEAK FORCE (N)'); // 31


              //~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ SQUAT JUMP

              $form['field_wrap_4'] = array(
                '#type' => 'fieldset',
                '#title' => $this->t('<div style="text-align: center; font-weight: bold;">SQUAT JUMP</div>'),
                '#prefix' => '<div id="form_fields_wrap" class="form_fields_wrap">',
                '#suffix' => '</div>',
              );

              $fieldName = 'squat_jump_rank';
              $form['field_wrap_4'][$fieldName] = $this->getFormField($fieldName, 0, $defaultValues[$fieldName], 'YOUR RANK IS'); // 32
              $fieldName = 'squat_jump_jump_height';
              $form['field_wrap_4'][$fieldName] = $this->getFormField($fieldName, 1, $defaultValues[$fieldName], 'JUMP HEIGHT (In)'); // 33

              $fieldName = 'squat_jump_jump_height_E';
              $form['field_wrap_4'][$fieldName] = $this->getFormField($fieldName, 0, $defaultValues[$fieldName], '(E) JUMP HEIGHT (In)'); // 34
              $fieldName = 'squat_jump_force';
              $form['field_wrap_4'][$fieldName] = $this->getFormField($fieldName, 1, $defaultValues[$fieldName], 'PEAK FORCE (N)'); // 35

              $fieldName = 'squat_jump_force_E';
              $form['field_wrap_4'][$fieldName] = $this->getFormField($fieldName, 0, $defaultValues[$fieldName], '(E) PEAK FORCE (M)'); // 36


              //~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ EUR SCORE

              $form['field_wrap_5'] = array(
                '#type' => 'fieldset',
                '#title' => $this->t('<div style="text-align: center; font-weight: bold;">EUR SCORE</div>'),
                '#prefix' => '<div id="form_fields_wrap" class="form_fields_wrap">',
                '#suffix' => '</div>',
              );

              $fieldName = 'eur_score';
              $form['field_wrap_5'][$fieldName] = $this->getFormField($fieldName, 0, $defaultValues[$fieldName], 'YOUR EUR SCORE'); // 37


              //~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ 10M/40M SPRINT

              $form['field_wrap_6'] = array(
                '#type' => 'fieldset',
                '#title' => $this->t('<div style="text-align: center; font-weight: bold;">10M/40M SPRINT</div>'),
                '#prefix' => '<div id="form_fields_wrap" class="form_fields_wrap">',
                '#suffix' => '</div>',
              );

              $fieldName = 'sprint_rank';
              $form['field_wrap_6'][$fieldName] = $this->getFormField($fieldName, 0, $defaultValues[$fieldName], 'YOUR RANK IS'); // 38
              $fieldName = 'sprint_10m';
              $form['field_wrap_6'][$fieldName] = $this->getFormField($fieldName, 1, $defaultValues[$fieldName], '10M SPRINT (Secs)'); // 39

              $fieldName = 'sprint_10m_E';
              $form['field_wrap_6'][$fieldName] = $this->getFormField($fieldName, 0, $defaultValues[$fieldName], '(E) 10M SPRINT (Secs)'); // 40
              $fieldName = 'sprint_40m';
              $form['field_wrap_6'][$fieldName] = $this->getFormField($fieldName, 1, $defaultValues[$fieldName], '40M SPRINT (Secs)'); // 41

              $fieldName = 'sprint_40m_E';
              $form['field_wrap_6'][$fieldName] = $this->getFormField($fieldName, 0, $defaultValues[$fieldName], '(E) 40M SPRINT (Secs)'); // 42


              //~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ MID-THIGH PULL

              $form['field_wrap_7'] = array(
                '#type' => 'fieldset',
                '#title' => $this->t('<div style="text-align: center; font-weight: bold;">MID-THIGH PULL</div>'),
                '#prefix' => '<div id="form_fields_wrap" class="form_fields_wrap">',
                '#suffix' => '</div>',
              );

              $fieldName = 'mid_thigh_rank';
              $form['field_wrap_7'][$fieldName] = $this->getFormField($fieldName, 0, $defaultValues[$fieldName], 'YOUR RANK IS'); // 43
              $fieldName = 'mid_thigh_your_weight';
              $form['field_wrap_7'][$fieldName] = $this->getFormField($fieldName, 1, $defaultValues[$fieldName], 'YOUR WEIGHT (LBS)'); // 44

              $fieldName = 'mid_thigh_abs_strength_n';
              $form['field_wrap_7'][$fieldName] = $this->getFormField($fieldName, 0, $defaultValues[$fieldName], 'ABSOLUTE STRENGTH (N)'); // 45
              $fieldName = 'mid_thigh_abs_strength_lbs';
              $form['field_wrap_7'][$fieldName] = $this->getFormField($fieldName, 1, $defaultValues[$fieldName], 'ABSOLUTE STRENGTH (LBS)'); // 46

              $fieldName = 'mid_thigh_abs_strength_n_E';
              $form['field_wrap_7'][$fieldName] = $this->getFormField($fieldName, 0, $defaultValues[$fieldName], '(E) ABSOLUTE STRENGTH (N)'); // 47
              $fieldName = 'mid_thigh_abs_strength_lbs_E';
              $form['field_wrap_7'][$fieldName] = $this->getFormField($fieldName, 1, $defaultValues[$fieldName], '(E) ABSOLUTE STRENGTH (LBS)'); // 48

              $fieldName = 'mid_thigh_rel_strength';
              $form['field_wrap_7'][$fieldName] = $this->getFormField($fieldName, 0, $defaultValues[$fieldName], 'RELATIVE STRENGTH (%)'); // 49
              $fieldName = 'mid_thigh_rel_strength_E';
              $form['field_wrap_7'][$fieldName] = $this->getFormField($fieldName, 1, $defaultValues[$fieldName], '(E) RELATIVE STRENGTH (%)'); // 50


              //~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ RATE OF FORCE

              $form['field_wrap_8'] = array(
                '#type' => 'fieldset',
                '#title' => $this->t('<div style="text-align: center; font-weight: bold;">RATE OF FORCE</div>'),
                '#prefix' => '<div id="form_fields_wrap" class="form_fields_wrap">',
                '#suffix' => '</div>',
              );

              $fieldName = 'force_rate_peak';
              $form['field_wrap_8'][$fieldName] = $this->getFormField($fieldName, 0, $defaultValues[$fieldName], 'PEAK FORCE (LBS)'); // 51
              $fieldName = 'force_rate_your_weight';
              $form['field_wrap_8'][$fieldName] = $this->getFormField($fieldName, 1, $defaultValues[$fieldName], 'YOUR WEIGHT (LBS)'); // 52

              $fieldName = 'force_rate_peak_weight';
              $form['field_wrap_8'][$fieldName] = $this->getFormField($fieldName, 0, $defaultValues[$fieldName], 'PEAK FORCE/BODY WEIGHT (#.#x)'); // 53
              $fieldName = 'force_rate_force_n';
              $form['field_wrap_8'][$fieldName] = $this->getFormField($fieldName, 1, $defaultValues[$fieldName], 'FORCE (N)'); // 54

              $fieldName = 'force_rate_rfd';
              $form['field_wrap_8'][$fieldName] = $this->getFormField($fieldName, 0, $defaultValues[$fieldName], 'RFD (%)'); // 55
              $fieldName = 'force_rate_low_peak';
              $form['field_wrap_8'][$fieldName] = $this->getFormField($fieldName, 1, $defaultValues[$fieldName], 'LOW PEAK FORCE'); // 56

              $fieldName = 'force_rate_medium_peak';
              $form['field_wrap_8'][$fieldName] = $this->getFormField($fieldName, 0, $defaultValues[$fieldName], 'MEDIUM PEAK FORCE'); // 57
              $fieldName = 'force_rate_high_peak';
              $form['field_wrap_8'][$fieldName] = $this->getFormField($fieldName, 1, $defaultValues[$fieldName], 'HIGH PEAK FORCE'); // 58


              //~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ DYNAMIC STRENGTH INDEX

              $form['field_wrap_9'] = array(
                '#type' => 'fieldset',
                '#title' => $this->t('<div style="text-align: center; font-weight: bold;">DYNAMIC STRENGTH INDEX</div>'),
                '#prefix' => '<div id="form_fields_wrap" class="form_fields_wrap">',
                '#suffix' => '</div>',
              );

              $fieldName = 'dynamic_strength_impt_peak';
              $form['field_wrap_9'][$fieldName] = $this->getFormField($fieldName, 0, $defaultValues[$fieldName], 'IMTP PEAK FORCE (N)'); // 59
              $fieldName = 'dynamic_strength_cmj_peak';
              $form['field_wrap_9'][$fieldName] = $this->getFormField($fieldName, 1, $defaultValues[$fieldName], 'CMJ PEAK FORCE (N)'); // 60

              $fieldName = 'dynamic_strength_dsi_score';
              $form['field_wrap_9'][$fieldName] = $this->getFormField($fieldName, 0, $defaultValues[$fieldName], 'YOUR DSI SCORE (.##)'); // 61


              //~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ SUMMARY

              $form['field_wrap_91'] = array(
                '#type' => 'fieldset',
                '#title' => $this->t('<div style="text-align: center; font-weight: bold;">SUMMARY</div>'),
                '#prefix' => '<div id="form_fields_wrap" class="form_fields_wrap">',
                '#suffix' => '</div>',
              );

              $fieldName = 'summary_reactive';
              $form['field_wrap_91'][$fieldName] = $this->getFormField($fieldName, 0, $defaultValues[$fieldName], 'REACTIVE STRENGTH (#.#)'); // 62
              $fieldName = 'summary_reactive_E';
              $form['field_wrap_91'][$fieldName] = $this->getFormField($fieldName, 1, $defaultValues[$fieldName], '(E) REACTIVE STRENGTH (#.#)'); // 63

              $fieldName = 'summary_reactive_B';
              $form['field_wrap_91'][$fieldName] = $this->getFormField($fieldName, 0, $defaultValues[$fieldName], '(B) REACTIVE STRENGTH (##TH PERCENTILE)'); // 64
              $fieldName = 'summary_dynamic';
              $form['field_wrap_91'][$fieldName] = $this->getFormField($fieldName, 1, $defaultValues[$fieldName], 'DYNAMIC STRENGTH (#.#%)'); // 65

              $fieldName = 'summary_dynamic_E';
              $form['field_wrap_91'][$fieldName] = $this->getFormField($fieldName, 0, $defaultValues[$fieldName], '(E) DYNAMIC STRENGTH (#.#%)'); // 66
              $fieldName = 'summary_dynamic_B';
              $form['field_wrap_91'][$fieldName] = $this->getFormField($fieldName, 1, $defaultValues[$fieldName], '(B) DYNAMIC STRENGTH (N/A)'); // 67

              $fieldName = 'summary_ecc';
              $form['field_wrap_91'][$fieldName] = $this->getFormField($fieldName, 0, $defaultValues[$fieldName], 'ECC. RATIO (#.##)'); // 68
              $fieldName = 'summary_summary_ecc_E';
              $form['field_wrap_91'][$fieldName] = $this->getFormField($fieldName, 1, $defaultValues[$fieldName], '(E) ECC. RATIO (#.##)'); // 69

              $fieldName = 'summary_summary_ecc_B';
              $form['field_wrap_91'][$fieldName] = $this->getFormField($fieldName, 0, $defaultValues[$fieldName], '(B) ECC. RATIO (N/A)'); // 70
              $fieldName = 'summary_relative';
              $form['field_wrap_91'][$fieldName] = $this->getFormField($fieldName, 1, $defaultValues[$fieldName], 'RELATIVE STRENGTH (###LBS)'); // 71

              $fieldName = 'summary_relative_E';
              $form['field_wrap_91'][$fieldName] = $this->getFormField($fieldName, 0, $defaultValues[$fieldName], '(E) RELATIVE STRENGTH (###LBS)'); // 72
              $fieldName = 'summary_relative_B';
              $form['field_wrap_91'][$fieldName] = $this->getFormField($fieldName, 1, $defaultValues[$fieldName], '(B) RELATIVE STRENGTH (##TH PERCENTILE)'); // 73

            }






          //xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx jody
          // 	//Reactive Strength (CM Rebound Jump)
          // 	$form['form_fields_wrap']['reactive_strength'] = array(
        	//   '#type' => 'fieldset',
        	//   '#title' => $this->t('Reactive Strength (CM Rebound Jump)'),
        	//   '#prefix' => '<div id="reactive_strength" class="sm_cls">',
        	//   '#suffix' => '</div>',
        	//    );

          //    $form['form_fields_wrap']['reactive_strength']['starter_weight_rea_str'] = array(
          //     '#type' => 'textfield',
          //     '#default_value' => $starter_weight_rea_str,
          //     #'#title' => t('Weight (N) Calculated into Ibs'),
          //     #'#required' => TRUE,
          //     '#attributes' => array(
          //       'placeholder' => t('Weight (N) Calculated into Ibs'),
          //     ),
          //   );

          //   $form['form_fields_wrap']['reactive_strength']['starter_jump_height_rea_str'] = array(
          //     '#type' => 'textfield',
          //     #'#title' => t('Jump Height (ln)'),
          //     '#default_value' => $field_jump_height_in_reactive,
          //     #'#required' => TRUE,
          //     '#attributes' => array(
          //       'placeholder' => t('Jump Height (ln)'),
          //     ),
          //   );
          //   $form['form_fields_wrap']['reactive_strength']['starter_rsi_rea_str'] = array (
          //     '#type' => 'textfield',
          //     #'#title' => t('RSI'),
          //     '#default_value' => $field_rsi_reactive,
          //     #'#required' => TRUE,
          //     '#attributes' => array(
          //       'placeholder' => t('RSI'),
          //     ),
          //   );

          //   //Elastic Strenght (Countermovement Jump)
        	// $form['form_fields_wrap']['elastic_strenght'] = array(
        	// 	  '#type' => 'fieldset',
        	// 	  '#title' => $this->t('Elastic Strenght (Countermovement Jump)'),
        	// 	  '#prefix' => '<div id="elastic_strenght" class="sm_cls">',
        	// 	  '#suffix' => '</div>',
        	// );
          //   $form['form_fields_wrap']['elastic_strenght']['starter_jump_height_ela_str'] = array (
          //     '#type' => 'textfield',
          //     '#default_value' => $field_jump_height_in_elastic,
          //     #'#title' => t('Jump Height (ln)'),
          //     #'#required' => TRUE,
          //     '#attributes' => array(
          //       'placeholder' => t('Jump Height (ln)'),
          //     ),
          //   );

          //   $form['form_fields_wrap']['elastic_strenght']['elastic_strenght']['starter_peak_pro_ela_str'] = array (
          //     '#type' => 'textfield',
          //     '#default_value' => $field_peak_propulsive_elastic,
          //     #'#required' => TRUE,
          //     '#attributes' => array(
          //       'placeholder' => t('Peak Propulslve Force (N)'),
          //     ),
          //   );
          //   $form['form_fields_wrap']['elastic_strenght']['starter_peak_power_ela_str'] = array (
          //     '#type' => 'textfield',
          //     '#default_value' => $field_peak_power_w_elastic,
          //     #'#required' => TRUE,
          //     '#attributes' => array(
          //       'placeholder' => t('Peak Power (W)'),
          //     ),
          //   );

          //   //Ballistic Strength (Squat Jump)
          //   $form['form_fields_wrap']['ballistic_strength'] = array(
        	// 	  '#type' => 'fieldset',
        	// 	  '#title' => $this->t('Ballistic Strength (Squat Jump)'),
        	// 	  '#prefix' => '<div id="ballistic_strength" class="sm_cls">',
        	// 	  '#suffix' => '</div>',
        	// );
          //   $form['form_fields_wrap']['ballistic_strength']['starter_jump_height_ballistic'] = array (
          //     '#type' => 'textfield',
          //     '#default_value' => $field_jump_height_in_ballistic,
          //     #'#required' => TRUE,
          //     '#attributes' => array(
          //       'placeholder' => t('Jump Height (ln)'),
          //     ),
          //   );

          //   $form['form_fields_wrap']['ballistic_strength']['starter_peak_pro_ballistic'] = array (
          //     '#type' => 'textfield',
          //     '#default_value' => $field_peak_propulsive_ballistic,
          //     #'#required' => TRUE,
          //     '#attributes' => array(
          //       'placeholder' => t('Peak Propulslve Force (N)'),
          //     ),
          //   );

          //   $formv['ballistic_strength']['starter_peak_power_ballistic'] = array (
          //     '#type' => 'textfield',
          //     '#default_value' => $field_peak_power_w_ballistic,
          //     #'#required' => TRUE,
          //     '#attributes' => array(
          //       'placeholder' => t('Peak Power (W)'),
          //     ),
          //   );

          //   // 10M/40M Sprint
          //   $form['form_fields_wrap']['10m_40m_sprint'] = array(
        	// 	  '#type' => 'fieldset',
        	// 	  '#title' => $this->t('10M/40M Sprint'),
        	// 	  '#prefix' => '<div id="10m_40m" class="sm_cls">',
        	// 	  '#suffix' => '</div>',
        	// );

          //   $form['form_fields_wrap']['10m_40m_sprint']['starter_10m'] = array (
          //     '#type' => 'textfield',
          //     '#default_value' => $field_10m_time_sec_sprint,
          //     #'#required' => TRUE,
          //     '#attributes' => array(
          //       'placeholder' => t('10 M Time (sec)'),
          //     ),
          //   );

          //   $form['form_fields_wrap']['10m_40m_sprint']['starter_40m'] = array (
          //     '#type' => 'textfield',
          //     '#default_value' => $field_40m_time_sec_sprint,
          //     #'#required' => TRUE,
          //     '#attributes' => array(
          //       'placeholder' => t('40 M Time (sec)'),
          //     ),
          //   );

          //   // Maximal Strength (Isometric Mid-Thigh Pull)
          //   $form['form_fields_wrap']['maximal_strength'] = array(
        	// 	  '#type' => 'fieldset',
        	// 	  '#title' => $this->t('Maximal Strength (Isometric Mid-Thigh Pull)'),
        	// 	  '#prefix' => '<div id="10m_40m" class="sm_cls">',
        	// 	  '#suffix' => '</div>',
        	// );

          //   $form['form_fields_wrap']['maximal_strength']['starter_peak_for_max'] = array (
          //     '#type' => 'textfield',
          //     '#default_value' => $field_peak_force_n_maximal,
          //     #'#required' => TRUE,
          //     '#attributes' => array(
          //       'placeholder' => t('Peak Force (N)'),
          //     ),
          //   );

          //    $form['form_fields_wrap']['maximal_strength']['starter_rfd_max'] = array (
          //     '#type' => 'textfield',
          //     '#default_value' => $field_rfd_100ms_n_maximal,
          //     #'#required' => TRUE,
          //     '#attributes' => array(
          //       'placeholder' => t('RFD @ 100ms (N)'),
          //     ),
          //   );




             /*********** elete fields start ************/
            if($realFormType == 'elete'){
              $required = TRUE;
            }else{
              $required = FALSE;
            }
            if($realFormType == 'elete') {
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
                  '#title' => $this->t('Strength Endurance (Pull-ups,Push-ups,Single Leg Squats)'),
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

            if($realFormType == 'elete'){
             $formtype_val = 'elete';
            }elseif($realFormType == 'starter'){
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




            $form['actions'] = array(
              '#type' => 'fieldset',
              '#attributes' => array(
                'style' => 'position: sticky; bottom: 0; background-color: white; padding-top: 4px;',
              ),
              '#prefix' => '<div>',
              '#suffix' => '</div>',
            );

            $form['actions']['#type'] = 'actions';


            $form['actions']['draft'] = array(
              '#type' => 'submit',
              '#value' => $this->t('SAVE & UNPUBLISH'),
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
              '#value' => $this->t('MANAGER - SAVE & PUBLISH'),
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

        $message = '';



          /****
           * TODO
           *    See "LEGACY form field names" in code here, convert legacy form field names (Example, change "starter_rsi_rea_str" into natural database field name "field_rsi_reactive")
           */
          /***/
          $message = '<hr><div style="font-size: 0.8em; text-align:left;"><pre>';
          $message .= '<b>$form_data array:</b> ' . var_export($form_data, TRUE);
          $message .= '</pre></div><hr>';
          /***
           if (!is_numeric($form_state->getValue('starter_weight_rea_str')) || empty($form_state->getValue('starter_weight_rea_str'))) {
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
           }else{
             $message = '<p style="color:green;">Successfully saved!</p>';
                if(!$nids1){
                      $node = Node::create([
                         'type' => 'athlete_assessment_info',
                        ]);

                    $node->set('title', $form_data['starter_weight_rea_str']);
                    $node->set('field_jump_height_in_reactive', $form_data['starter_weight_rea_str']);
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
                     // if "SAVE - ALL FIELDS COMPLETED" trigger
                    $node->set('field_status', 'complete');
                    $node->setPublished(TRUE);
                    $node->save();
                  }else{
                    // $node = Node::load($nids1);
                    // $node->set('title', $form_data['starter_weight_rea_str']);
                    // $node->set('field_jump_height_in_reactive', $form_data['starter_jump_height_rea_str']);
                    // $node->set('field_rsi_reactive', $form_data['starter_rsi_rea_str']);
                    // $node->set('field_jump_height_in_elastic', $form_data['starter_jump_height_ela_str']);
                    // $node->set('field_peak_propulsive_elastic', $form_data['starter_peak_pro_ela_str']);
                    // $node->set('field_peak_power_w_elastic', $form_data['starter_peak_power_ela_str']);
                    // $node->set('field_jump_height_in_ballistic', $form_data['starter_jump_height_ballistic']);
                    // $node->set('field_peak_propulsive_ballistic', $form_data['starter_peak_pro_ballistic']);
                    // $node->set('field_peak_power_w_ballistic', $form_data['starter_peak_power_ballistic']);
                    // $node->set('field_10m_time_sec_sprint', $form_data['starter_10m']);
                    // $node->set('field_40m_time_sec_sprint', $form_data['starter_40m']);
                    // $node->set('field_peak_force_n_maximal', $form_data['starter_peak_for_max']);
                    // $node->set('field_rfd_100ms_n_maximal', $form_data['starter_rfd_max']);
                    // $node->set('field_assessment_type', $form_data['assessment_type']);
                    // $node->set('field_form_type', $form_data['form_type']);
                    // $node->set('field_athelete_nid', $form_data['athelete_nid']);
                    // $node->set('field_booked_id', $form_data['booked_id']);
                    // //aditional fields for elete
                    // $node->set('field_power_w_ssm_ipe', $form_data['power']);
                    // $node->set('field_power_w_spm_ipe', $form_data['power_spm']);
                    // $node->set('field_power_w_rm_ipe', $form_data['power_rm']);
                    // $node->set('field_repetitions_se_ipe', $form_data['repetitions']);
                    // $node->set('field_power_w_cfd_ipe', $form_data['power_ch']);
                    // //user target id
                    // $node->set('field_user', ['target_id' => $user_id]);
                    //  // if "SAVE - ALL FIELDS COMPLETED" trigger
                    // $node->set('field_status', 'complete');
                    // $node->setPublished(TRUE);
                    // $node->save();
                  }
           }
           */







            // if(!$nids1){
            //        $node = Node::create([
            //          'type' => 'athlete_assessment_info',
            //         ]);
            //       $node->set('title', $form_data['starter_weight_rea_str']);
            //       $node->set('field_jump_height_in_reactive', $form_data['starter_jump_height_rea_str']);
            //       $node->set('field_rsi_reactive', $form_data['starter_rsi_rea_str']);
            //       $node->set('field_jump_height_in_elastic', $form_data['starter_jump_height_ela_str']);
            //       $node->set('field_peak_propulsive_elastic', $form_data['starter_peak_pro_ela_str']);
            //       $node->set('field_peak_power_w_elastic', $form_data['starter_peak_power_ela_str']);
            //       $node->set('field_jump_height_in_ballistic', $form_data['starter_jump_height_ballistic']);
            //       $node->set('field_peak_propulsive_ballistic', $form_data['starter_peak_pro_ballistic']);
            //       $node->set('field_peak_power_w_ballistic', $form_data['starter_peak_power_ballistic']);
            //       $node->set('field_10m_time_sec_sprint', $form_data['starter_10m']);
            //       $node->set('field_40m_time_sec_sprint', $form_data['starter_40m']);
            //       $node->set('field_peak_force_n_maximal', $form_data['starter_peak_for_max']);
            //       $node->set('field_rfd_100ms_n_maximal', $form_data['starter_rfd_max']);
            //       $node->set('field_assessment_type', $form_data['assessment_type']);
            //       $node->set('field_form_type', $form_data['form_type']);
            //       $node->set('field_athelete_nid', $form_data['athelete_nid']);
            //       $node->set('field_booked_id', $form_data['booked_id']);
            //       //aditional fields for elete
            //       $node->set('field_power_w_ssm_ipe', $form_data['power']);
            //       $node->set('field_power_w_spm_ipe', $form_data['power_spm']);
            //       $node->set('field_power_w_rm_ipe', $form_data['power_rm']);
            //       $node->set('field_repetitions_se_ipe', $form_data['repetitions']);
            //       $node->set('field_power_w_cfd_ipe', $form_data['power_ch']);
            //       //user target id
            //       $node->set('field_user', ['target_id' => $user_id]);
            //        // if "SAVE - ALL FIELDS COMPLETED" trigger
            //       $node->set('field_status', 'complete');

            //       $node->setPublished(TRUE);
            //  }else{
            //       $node->set('title', $form_data['starter_weight_rea_str']);
            //       $node->set('field_jump_height_in_reactive', $form_data['starter_jump_height_rea_str']);
            //       $node->set('field_rsi_reactive', $form_data['starter_rsi_rea_str']);
            //       $node->set('field_jump_height_in_elastic', $form_data['starter_jump_height_ela_str']);
            //       $node->set('field_peak_propulsive_elastic', $form_data['starter_peak_pro_ela_str']);
            //       $node->set('field_peak_power_w_elastic', $form_data['starter_peak_power_ela_str']);
            //       $node->set('field_jump_height_in_ballistic', $form_data['starter_jump_height_ballistic']);
            //       $node->set('field_peak_propulsive_ballistic', $form_data['starter_peak_pro_ballistic']);
            //       $node->set('field_peak_power_w_ballistic', $form_data['starter_peak_power_ballistic']);
            //       $node->set('field_10m_time_sec_sprint', $form_data['starter_10m']);
            //       $node->set('field_40m_time_sec_sprint', $form_data['starter_40m']);
            //       $node->set('field_peak_force_n_maximal', $form_data['starter_peak_for_max']);
            //       $node->set('field_rfd_100ms_n_maximal', $form_data['starter_rfd_max']);
            //       $node->set('field_assessment_type', $form_data['assessment_type']);
            //       $node->set('field_form_type', $form_data['form_type']);
            //       $node->set('field_athelete_nid', $form_data['athelete_nid']);
            //       $node->set('field_booked_id', $form_data['booked_id']);
            //       //aditional fields for elete
            //       $node->set('field_power_w_ssm_ipe', $form_data['power']);
            //       $node->set('field_power_w_spm_ipe', $form_data['power_spm']);
            //       $node->set('field_power_w_rm_ipe', $form_data['power_rm']);
            //       $node->set('field_repetitions_se_ipe', $form_data['repetitions']);
            //       $node->set('field_power_w_cfd_ipe', $form_data['power_ch']);
            //       //user target id
            //       $node->set('field_user', ['target_id' => $user_id]);
            //        // if "SAVE - ALL FIELDS COMPLETED" trigger
            //       $node->set('field_status', 'complete');

            //       $node->setPublished(TRUE);
            //       $node = Node::load($nids1);
            //  }
            //   $node->save();
            //   $message = 'Successfully saved.';
             // $message = "Successfully saved.";

          if (isset($triggerElement['#id']) && strpos($triggerElement['#id'], 'edit-draft') !== false ) {
          // if "ASSESSORS - SAVE" button trigger
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
