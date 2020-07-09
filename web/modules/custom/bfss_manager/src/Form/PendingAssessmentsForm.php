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
use Drupal\bfss_assessment\AssessmentService;

class PendingAssessmentsForm extends FormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'pending_assessments_form';
  }

  /**
   * Returns a drupal-style textfield - which is an array of values.
   * @param string $fieldName
   *   Name of the form field, also is the name of the default value (if any)
   * @param int $fieldNum
   *   0 - for the field to appear on the left
   *   1 - for the field to appear on the right
   * @param string $defaultValue
   *   Optional. Sets the textfield's value attribute.
   * @param string $placeholder
   *   Optional. The text that appears within the textfield when empty.
   * @param string $units
   *   Optional. i.e.  Lbs, In,  Secs
   */
  public function getFormField($fieldName, $fieldNum, $defaultValue = '', $placeholder = '', $units='') {
    $fieldAry = [];
    $modVal = ($fieldNum % 2);
    $style1 = $modVal ? 'width: 64.5%' : 'position: absolute; width: 43%; right: 7.2%';
    $fieldAry[$fieldName] = array(
      '#type' => 'textfield',
      '#default_value' => $defaultValue,
      // '#required' => TRUE,
      '#attributes' => array(
        'placeholder' => t($placeholder),
        'title' => t($placeholder) . ($units ? '   (' . t($units) . ')' : ''),
        'style' => 'height: calc(1.4em + 0.3rem + 2px); ' . $style1 . ';',
      ),
    );
    $style2 = '';
    if ($units) {
      // '#field_suffix' => t($units),
      $style2 = $modVal ? 'right: 50.4%' : 'right: 7.4%';
      $fieldWidth = 5;
      $fieldWidth += strlen($units) > 5 ? (strlen($units) * 0.3) : 0;
      $fieldWidth = $fieldWidth . '%';
      $fieldAry[$fieldName.'_unit'] = array(
        '#type' => 'textfield',
        '#default_value' => $units,
        '#attributes' => array(
          'tabindex' => '200',
          'readonly' => 'true',
          'style' => 'height: calc(1.2em + 0.3rem + 2px); text-align: center; margin-top: 1px; border: 1px solid white; background-color: grey; color: white; width: ' . $fieldWidth . '; padding: 0 2px; position: absolute; font-size: 1.12em; font-family: "Agency FB" !important; text-align: center;' . $style2 . ';'
        ),
      );
    }
    return $fieldAry;
  }


  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $formFields = $form;
    $param = \Drupal::request()->query->all();
    $nid = $param['nid'];
    $formtype = $param['formtype'];
    $Assess_type = $param['Assess_type'];
    $field_booked_id = $param['booked_id'];
    $st = $param['st'];
    $field_status = $param['field_status'];
    // $assess_nid = $param['assess_nid'];
    $first_name = $param['first_name'];
    $last_name = $param['last_name'];

    if ($field_booked_id) {
      // See if assessment has been started by assessor or mgr.
      // And get latest recorded status (and $assess_nid) for current assessment - see if complete or incomplete.
      $field_status = 'not started';
      $nidPrev = 0;
      $queryStat = \Drupal::entityQuery('node');
      $queryStat->condition('type', 'athlete_assessment_info');
      $queryStat->condition('field_booked_id',$field_booked_id, 'IN');
      $nidsStat = $queryStat->execute();
      if($nidsStat){
        foreach ($nidsStat as $nid) {
          if ($nid > $nidPrev) {
            $nidPrev = $nid;
            $assessmentNode = Node::load($nid);
            $field_status = $assessmentNode->get('field_status')->getValue()[0]['value'];
          }
        }
      }
      $assess_nid = $nidPrev;
    }

    if($nid && $formtype && $Assess_type)
    {
      //jody - use this to extract "professional" (because $formtype only contains 'Starter' OR 'Elite')
      $entity = \Drupal\bfss_assessment\Entity\BfssPayments::load($field_booked_id);

      $sport = $param['sport'] ? $param['sport'] : $entity->mydata->field_sport->value; // to be set in field_sport_assessment
      // $tmp_user_id = $entity->user_id->value;

      $realFormType = AssessmentService::getFormTypeFromPrice($entity->service->value);

      $realFormType = $realFormType ? $realFormType : $param['formtype'];
//ksm('$realFormType', $realFormType);
      if($realFormType == 'Starter' && $Assess_type == 'individual'){
        $form_title = 'STARTER ASSESSMENT';
      }
      elseif($realFormType == 'Professional' && $Assess_type == 'individual'){
        $form_title = 'PROFESSIONAL ASSESSMENT';
      }
      elseif($realFormType == 'Elite' && $Assess_type == 'individual'){
        $form_title = 'ELITE ASSESSMENT';
      }
      elseif($realFormType == 'Starter' && $Assess_type == 'private'){
        $form_title = 'STARTER ASSESSMENT';
      }
      elseif($realFormType == 'Professional' && $Assess_type == 'private'){
        $form_title = 'PROFESSIONAL ASSESSMENT';
      }
      elseif($realFormType == 'Elite' && $Assess_type == 'private'){
          $form_title = 'ELITE ASSESSMENT';
      }

      //ksm([' realFormType form_title param', $realFormType, $form_title, $param]);

      $formFields['#attached']['library'][] = 'bfss_assessors/bfss_assessors';
      $formFields['#prefix'] = '
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

      $formFields['#suffix'] = '  </div>
                          </div>
                        </div>
                      </div>
                  </div>
      <!-- Modal end-->';
        $formFields['message'] = [
        '#type' => 'markup',
        '#markup' => '<div class="result_message form_fields_wrap"></div>',
      ];

      if($assess_nid) { // from PendingAssessments.php, assess_nid is 0 by default.
        $node = Node::load($assess_nid);
      }
      else {
        $node = array();
      }

      //-------------------------------------- common to all pdf's -- Age, Sport, Weight, Sex
      $formFields['field_wrap_0'] = array(
        '#type' => 'fieldset',
        '#prefix' => '<div id="form_fields_wrap" class="form_fields_wrap">',
        '#suffix' => '</div>',
      );
      $fldNum = 0;

      $fieldName = 'field_age'; //dd
      $formFields['field_wrap_0'][$fieldName] = $this->getFormField($fieldName, ++$fldNum, @$node->{$fieldName}->value, 'AGE', 'y/o'); // 0
      $fieldName = 'field_sport_assessment'; // d
      $default_sport = @$node->{$fieldName}->value ? @$node->{$fieldName}->value : $sport;
      $formFields['field_wrap_0'][$fieldName] = $this->getFormField($fieldName, ++$fldNum, $default_sport, 'SPORT'); // 2

      $fieldName = 'field_weight'; //dd
      $formFields['field_wrap_0'][$fieldName] = $this->getFormField($fieldName, ++$fldNum, @$node->{$fieldName}->value, 'WEIGHT', 'Lbs', 'Lbs'); // 3
      $fieldName = 'field_sex'; //dd
      $formFields['field_wrap_0'][$fieldName] = $this->getFormField($fieldName, ++$fldNum, @$node->{$fieldName}->value, 'SEX'); // 4

      //------------------------------------ starter form
      if($realFormType == 'Starter')
      {
        $formFields['field_wrap_1'] = array(
          '#type' => 'fieldset',
          '#prefix' => '<div id="form_fields_wrap" class="form_fields_wrap">',
          '#suffix' => '</div>',
        );
        $fldNum = 0;

        $fieldName = 'field_jump_height_in_reactive'; //dd  LEGACY:  starter_jump_height_rea_str
        $formFields['field_wrap_1'][$fieldName] = $this->getFormField($fieldName, ++$fldNum, @$node->{$fieldName}->value, 'REACTIVE STRENGTH', 'In'); // 5
        $fieldName = 'field_jump_height_in_elastic'; //dd  LEGACY:  starter_jump_height_ela_str
        $formFields['field_wrap_1'][$fieldName] = $this->getFormField($fieldName, ++$fldNum, @$node->{$fieldName}->value, 'ELASTIC STRENGTH', 'In'); // 6

        $fieldName = 'field_jump_height_in_ballistic'; //dd  LEGACY:  starter_jump_height_ballistic
        $formFields['field_wrap_1'][$fieldName] = $this->getFormField($fieldName, ++$fldNum, @$node->{$fieldName}->value, 'BALLISTIC STRENGTH', 'In'); // 7
        $fieldName = 'field_10m_time_sec_sprint'; //dd  LEGACY:  starter_10m
        $formFields['field_wrap_1'][$fieldName] = $this->getFormField($fieldName, ++$fldNum, @$node->{$fieldName}->value, 'ACCELERATION/SPEED', 'Sec'); // 8

        // Note: field_rsi_reactive should be called field_jump_height_in_reactive_e
        // Note: field_rsi_reactive_b should be called field_jump_height_in_reactive_b

        $fieldName = 'field_peak_force_n_maximal'; //dd  LEGACY:  starter_peak_for_max
        $formFields['field_wrap_1'][$fieldName] = $this->getFormField($fieldName, ++$fldNum, @$node->{$fieldName}->value, 'MAXIMAL STRENGTH', 'Lbs');  // 9
        $fieldName = 'field_rsi_reactive'; //dd  LEGACY:  starter_rsi_rea_str
        $formFields['field_wrap_1'][$fieldName] = $this->getFormField($fieldName, ++$fldNum, @$node->{$fieldName}->value, '(E) REACTIVE STRENGTH', 'In'); // 10

        $fieldName = 'field_rsi_reactive_b'; //dd
        $formFields['field_wrap_1'][$fieldName] = $this->getFormField($fieldName, ++$fldNum, @$node->{$fieldName}->value, '(B) REACTIVE STRENGTH', '%tile'); // 11
      }

      //------------------------------------------ professional and elite form
      else if($realFormType == 'Professional' || $realFormType == 'Elite')
      {
        $formFields['field_wrap_1'] = array(
          '#type' => 'fieldset',
          '#prefix' => '<div id="form_fields_wrap" class="form_fields_wrap">',
          '#suffix' => '</div>',
        );

        //~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ ASSESSMENT TABLE 1
        $formFields['field_wrap_1']['#title'] = $this->t('<div style="text-align: center; font-weight: bold;">ASSESSMENT TABLE</div>');
        $fldNum = 0;

        $fieldName = 'field_jump_height_in_reactive'; //dd  LEGACY:  starter_jump_height_rea_str
        $formFields['field_wrap_1'][$fieldName] = $this->getFormField($fieldName, ++$fldNum, @$node->{$fieldName}->value, 'REACTIVE STRENGTH', 'In'); // 5
        $fieldName = 'field_jump_height_in_elastic'; //dd  LEGACY:  starter_jump_height_ela_str
        $formFields['field_wrap_1'][$fieldName] = $this->getFormField($fieldName, ++$fldNum, @$node->{$fieldName}->value, 'ELASTIC STRENGTH', 'In'); // 6
        $fieldName = 'field_jump_height_in_ballistic'; //dd  LEGACY:  starter_jump_height_ballistic
        $formFields['field_wrap_1'][$fieldName] = $this->getFormField($fieldName, ++$fldNum, @$node->{$fieldName}->value, 'BALLISTIC STRENGTH', 'In'); // 7
        $fieldName = 'field_10m_time_sec_sprint'; //dd  LEGACY:  starter_10m
        $formFields['field_wrap_1'][$fieldName] = $this->getFormField($fieldName, ++$fldNum, @$node->{$fieldName}->value, 'ACCELERATION/SPEED', 'Sec'); // 8
        // Note: field_rsi_reactive should be called field_jump_height_in_reactive_e
        // Note: field_rsi_reactive_b should be called field_jump_height_in_reactive_b
        $fieldName = 'field_peak_force_n_maximal'; //dd  LEGACY:  starter_peak_for_max
        $formFields['field_wrap_1'][$fieldName] = $this->getFormField($fieldName, ++$fldNum, @$node->{$fieldName}->value, 'MAXIMAL STRENGTH', 'Lbs');  // 9
        $fieldName = 'field_rsi_reactive'; //dd  LEGACY:  starter_rsi_rea_str
        $formFields['field_wrap_1'][$fieldName] = $this->getFormField($fieldName, ++$fldNum, @$node->{$fieldName}->value, '(E) REACTIVE STRENGTH', 'In'); // 10
        $fieldName = 'field_jump_height_in_elastic_e';
        $formFields['field_wrap_1'][$fieldName] = $this->getFormField($fieldName, ++$fldNum, @$node->{$fieldName}->value, '(E) ELASTIC STRENGTH', 'In'); // 11
        $fieldName = 'field_jump_height_in_ballistic_e';
        $formFields['field_wrap_1'][$fieldName] = $this->getFormField($fieldName, ++$fldNum, @$node->{$fieldName}->value, '(E) BALLISTIC STRENGTH', 'In'); // 12
        $fieldName = 'field_10m_time_sec_sprint_e';
        $formFields['field_wrap_1'][$fieldName] = $this->getFormField($fieldName, ++$fldNum, @$node->{$fieldName}->value, '(E) ACCELERATION/SPEED', 'Sec'); // 13
        $fieldName = 'field_peak_force_n_maximal_e';
        $formFields['field_wrap_1'][$fieldName] = $this->getFormField($fieldName, ++$fldNum, @$node->{$fieldName}->value, '(E) MAXIMAL STRENGTH', 'Lbs'); // 14
        $fieldName = 'field_rsi_reactive_b'; //dd
        $formFields['field_wrap_1'][$fieldName] = $this->getFormField($fieldName, ++$fldNum, @$node->{$fieldName}->value, '(B) REACTIVE STRENGTH', '%tile'); // 15
        $fieldName = 'field_jump_height_in_elastic_b';
        $formFields['field_wrap_1'][$fieldName] = $this->getFormField($fieldName, ++$fldNum, @$node->{$fieldName}->value, '(B) ELASTIC STRENGTH', '%tile'); // 16
        $fieldName = 'field_jump_height_in_ballistic_b';
        $formFields['field_wrap_1'][$fieldName] = $this->getFormField($fieldName, ++$fldNum, @$node->{$fieldName}->value, '(B) BALLISTIC STRENGTH', '%tile'); // 17
        $fieldName = 'field_10m_time_sec_sprint_b';
        $formFields['field_wrap_1'][$fieldName] = $this->getFormField($fieldName, ++$fldNum, @$node->{$fieldName}->value, '(B) ACCELERATION/SPEED', '%tile'); // 18
        $fieldName = 'field_peak_force_n_maximal_b';
        $formFields['field_wrap_1'][$fieldName] = $this->getFormField($fieldName, ++$fldNum, @$node->{$fieldName}->value, '(B) MAXIMAL STRENGTH', '%tile'); // 19
        $fieldName = 'field_elite_age_e';
        $formFields['field_wrap_1'][$fieldName] = $this->getFormField($fieldName, ++$fldNum, @$node->{$fieldName}->value, 'ELITE PERFORMERS AGE', 'range'); // *ELITE PERFORMERS AGE

        //------------------------------------------ elite-only form fields
        if($realFormType == 'Elite')
        {
          //~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ ASSESSMENT TABLE 2    BFS Testing: Performance Tests
          $formFields['field_wrap_1E'] = array(
            '#type' => 'fieldset',
            '#title' => $this->t('<div style="text-align: center; font-weight: bold;">ASSESSMENT TABLE 2</div>'),
            '#prefix' => '<div id="form_fields_wrap" class="form_fields_wrap">',
            '#suffix' => '</div>',
          );
          $fldNum = 0;
          // Fields which will probably be hidden or calculated:    'field_elite_percent_e'

          $fieldName = 'field_static_ball_throw';
          $formFields['field_wrap_1E'][$fieldName] = $this->getFormField($fieldName, ++$fldNum, @$node->{$fieldName}->value, 'STATIC MED BALL', 'W'); // 20
          $fieldName = 'field_rotate_ball_throw';
          $formFields['field_wrap_1E'][$fieldName] = $this->getFormField($fieldName, ++$fldNum, @$node->{$fieldName}->value, 'ROTATIONAL MED BALL', 'W'); // 21
          $fieldName = 'field_single_leg_strength';
          $formFields['field_wrap_1E'][$fieldName] = $this->getFormField($fieldName, ++$fldNum, @$node->{$fieldName}->value, 'SINGLE LEG STRENGTH', '#'); // 22
          $fieldName = 'field_push_ups_num';
          $formFields['field_wrap_1E'][$fieldName] = $this->getFormField($fieldName, ++$fldNum, @$node->{$fieldName}->value, 'PUSH UPS', '#'); // 23
          $fieldName = 'field_chin_ups_num';
          $formFields['field_wrap_1E'][$fieldName] = $this->getFormField($fieldName, ++$fldNum, @$node->{$fieldName}->value, 'CHIN UPS', '#'); // 24
          $fieldName = 'field_agility_sec';
          $formFields['field_wrap_1E'][$fieldName] = $this->getFormField($fieldName, ++$fldNum, @$node->{$fieldName}->value, '5-10-5 AGILITY', 'Secs'); // 25
          $fieldName = 'field_bike_test_time';
          $formFields['field_wrap_1E'][$fieldName] = $this->getFormField($fieldName, ++$fldNum, @$node->{$fieldName}->value, '1.5 MILE BIKE', 'Time'); // 26
          $fieldName = 'field_static_ball_throw_e';
          $formFields['field_wrap_1E'][$fieldName] = $this->getFormField($fieldName, ++$fldNum, @$node->{$fieldName}->value, '(E) STATIC MED BALL', 'W'); // 27
          $fieldName = 'field_rotate_ball_throw_e';
          $formFields['field_wrap_1E'][$fieldName] = $this->getFormField($fieldName, ++$fldNum, @$node->{$fieldName}->value, '(E) ROTATIONAL MED BALL', 'W'); // 28
          $fieldName = 'field_single_leg_strength_e';
          $formFields['field_wrap_1E'][$fieldName] = $this->getFormField($fieldName, ++$fldNum, @$node->{$fieldName}->value, '(E) SINGLE LEG STRENGTH', '#'); // 29
          $fieldName = 'field_push_ups_num_e';
          $formFields['field_wrap_1E'][$fieldName] = $this->getFormField($fieldName, ++$fldNum, @$node->{$fieldName}->value, '(E) PUSH UPS', '#'); // 30
          $fieldName = 'field_chin_ups_num_e';
          $formFields['field_wrap_1E'][$fieldName] = $this->getFormField($fieldName, ++$fldNum, @$node->{$fieldName}->value, '(E) CHIN UPS', '#'); // 31
          $fieldName = 'field_agility_sec_e';
          $formFields['field_wrap_1E'][$fieldName] = $this->getFormField($fieldName, ++$fldNum, @$node->{$fieldName}->value, '(E) 5-10-5 AGILITY', 'Secs'); // 32
          $fieldName = 'field_bike_test_time_e';
          $formFields['field_wrap_1E'][$fieldName] = $this->getFormField($fieldName, ++$fldNum, @$node->{$fieldName}->value, '(E) 1.5 MILE BIKE', 'Time'); // 33
          $fieldName = 'field_static_ball_throw_b';
          $formFields['field_wrap_1E'][$fieldName] = $this->getFormField($fieldName, ++$fldNum, @$node->{$fieldName}->value, '(B) STATIC MED BALL', '%tile'); // 34
          $fieldName = 'field_rotate_ball_throw_b';
          $formFields['field_wrap_1E'][$fieldName] = $this->getFormField($fieldName, ++$fldNum, @$node->{$fieldName}->value, '(B) ROTATIONAL MED BALL', '%tile'); // 35
          $fieldName = 'field_single_leg_strength_b';
          $formFields['field_wrap_1E'][$fieldName] = $this->getFormField($fieldName, ++$fldNum, @$node->{$fieldName}->value, '(B) SINGLE LEG STRENGTH', '%tile'); // 36
          $fieldName = 'field_push_ups_num_b';
          $formFields['field_wrap_1E'][$fieldName] = $this->getFormField($fieldName, ++$fldNum, @$node->{$fieldName}->value, '(B) PUSH UPS', '%tile'); // 37
          $fieldName = 'field_chin_ups_num_b';
          $formFields['field_wrap_1E'][$fieldName] = $this->getFormField($fieldName, ++$fldNum, @$node->{$fieldName}->value, '(B) CHIN UPS', '%tile'); // 38
          $fieldName = 'field_agility_sec_b';
          $formFields['field_wrap_1E'][$fieldName] = $this->getFormField($fieldName, ++$fldNum, @$node->{$fieldName}->value, '(B) 5-10-5 AGILITY', '%tile'); // 39
          $fieldName = 'field_bike_test_time_b';
          $formFields['field_wrap_1E'][$fieldName] = $this->getFormField($fieldName, ++$fldNum, @$node->{$fieldName}->value, '(B) 1.5 MILE BIKE', '%tile'); // 40
        }

        $formFields['field_wrap_2'] = array(
          '#type' => 'fieldset',
          '#prefix' => '<div id="form_fields_wrap" class="form_fields_wrap">',
          '#suffix' => '</div>',
        );
        //~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ REBOUND JUMP
        $formFields['field_wrap_2']['#title'] = $this->t('<div style="text-align: center; font-weight: bold;">REBOUND JUMP</div>');
        $fldNum = 0;

        $fieldName = 'field_rebound_jump_rank';
        $formFields['field_wrap_2'][$fieldName] = $this->getFormField($fieldName, ++$fldNum, @$node->{$fieldName}->value, 'YOUR RANK IS'); // 20 / 41
        $fieldName = 'field_rebound_jump_rsi';
        $formFields['field_wrap_2'][$fieldName] = $this->getFormField($fieldName, ++$fldNum, @$node->{$fieldName}->value, 'RSI SCORE', '#.#'); // 21 / 42
        $fieldName = 'field_rebound_jump_height';
        $formFields['field_wrap_2'][$fieldName] = $this->getFormField($fieldName, ++$fldNum, @$node->{$fieldName}->value, 'JUMP HEIGHT', 'In'); // 22 / 43
        $fieldName = 'field_rj_ground_contact_time';
        $formFields['field_wrap_2'][$fieldName] = $this->getFormField($fieldName, ++$fldNum, @$node->{$fieldName}->value, 'GROUND CONTACT TIME', 'ms'); // 23 / 44
        $fieldName = 'field_rebound_jump_low_rsi';
        $formFields['field_wrap_2'][$fieldName] = $this->getFormField($fieldName, ++$fldNum, @$node->{$fieldName}->value, 'LOW RSI', '>1.5'); // 24 / 45
        $fieldName = 'field_rebound_jump_medium_rsi';
        $formFields['field_wrap_2'][$fieldName] = $this->getFormField($fieldName, ++$fldNum, @$node->{$fieldName}->value, 'MEDIUM RSI', '1.5-2.0'); // 25 / 46
        $fieldName = 'field_rebound_jump_high_rsi';
        $formFields['field_wrap_2'][$fieldName] = $this->getFormField($fieldName, ++$fldNum, @$node->{$fieldName}->value, 'HIGH RSI', '2.0-2.5'); // 26 / 47

        //~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ COUNTER MOVEMENT JUMP
        $formFields['field_wrap_3'] = array(
          '#type' => 'fieldset',
          '#title' => $this->t('<div style="text-align: center; font-weight: bold;">COUNTER MOVEMENT JUMP</div>'),
          '#prefix' => '<div id="form_fields_wrap" class="form_fields_wrap">',
          '#suffix' => '</div>',
        );
        $fldNum = 0;

        $fieldName = 'field_cmj_rank';
        $formFields['field_wrap_3'][$fieldName] = $this->getFormField($fieldName, ++$fldNum, @$node->{$fieldName}->value, 'YOUR RANK IS'); // 27 / 48
        $fieldName = 'field_cmj_height';
        $formFields['field_wrap_3'][$fieldName] = $this->getFormField($fieldName, ++$fldNum, @$node->{$fieldName}->value, 'CMJ HEIGHT', 'In'); // 28 / 49
        $fieldName = 'field_cmj_height_e';
        $formFields['field_wrap_3'][$fieldName] = $this->getFormField($fieldName, ++$fldNum, @$node->{$fieldName}->value, '(E) CMJ HEIGHT', 'In'); // 29 / 50
        $fieldName = 'field_cmj_force';
        $formFields['field_wrap_3'][$fieldName] = $this->getFormField($fieldName, ++$fldNum, @$node->{$fieldName}->value, 'CMJ PEAK FORCE', 'N'); // 30 / 51
        $fieldName = 'field_cmj_force_e';
        $formFields['field_wrap_3'][$fieldName] = $this->getFormField($fieldName, ++$fldNum, @$node->{$fieldName}->value, '(E) CMJ PEAK FORCE', 'N'); // 31 / 52

        //~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ SQUAT JUMP
        $formFields['field_wrap_4'] = array(
          '#type' => 'fieldset',
          '#title' => $this->t('<div style="text-align: center; font-weight: bold;">SQUAT JUMP</div>'),
          '#prefix' => '<div id="form_fields_wrap" class="form_fields_wrap">',
          '#suffix' => '</div>',
        );
        $fldNum = 0;

        $fieldName = 'field_squat_jump_rank';
        $formFields['field_wrap_4'][$fieldName] = $this->getFormField($fieldName, ++$fldNum, @$node->{$fieldName}->value, 'YOUR RANK IS'); // 32 / 53
        $fieldName = 'field_squat_jump_jump_height';
        $formFields['field_wrap_4'][$fieldName] = $this->getFormField($fieldName, ++$fldNum, @$node->{$fieldName}->value, 'JUMP HEIGHT', 'In'); // 33 / 54
        $fieldName = 'field_squat_jump_jump_height_e';
        $formFields['field_wrap_4'][$fieldName] = $this->getFormField($fieldName, ++$fldNum, @$node->{$fieldName}->value, '(E) JUMP HEIGHT', 'In'); // 34 / 55
        $fieldName = 'field_squat_jump_force';
        $formFields['field_wrap_4'][$fieldName] = $this->getFormField($fieldName, ++$fldNum, @$node->{$fieldName}->value, 'PEAK FORCE', 'N'); // 35 / 56
        $fieldName = 'field_squat_jump_force_e';
        $formFields['field_wrap_4'][$fieldName] = $this->getFormField($fieldName, ++$fldNum, @$node->{$fieldName}->value, '(E) PEAK FORCE', 'N'); // 36 / 57

        //~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ EUR SCORE
        $formFields['field_wrap_5'] = array(
          '#type' => 'fieldset',
          '#title' => $this->t('<div style="text-align: center; font-weight: bold;">EUR SCORE</div>'),
          '#prefix' => '<div id="form_fields_wrap" class="form_fields_wrap">',
          '#suffix' => '</div>',
        );
        $fldNum = 0;

        $fieldName = 'field_eur_score';
        $formFields['field_wrap_5'][$fieldName] = $this->getFormField($fieldName, ++$fldNum, @$node->{$fieldName}->value, 'YOUR EUR SCORE'); // 37 / 58

        //~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ 10M/40M SPRINT
        $formFields['field_wrap_6'] = array(
          '#type' => 'fieldset',
          '#title' => $this->t('<div style="text-align: center; font-weight: bold;">10M/40M SPRINT</div>'),
          '#prefix' => '<div id="form_fields_wrap" class="form_fields_wrap">',
          '#suffix' => '</div>',
        );
        $fldNum = 0;

        $fieldName = 'field_sprint_rank';
        $formFields['field_wrap_6'][$fieldName] = $this->getFormField($fieldName, ++$fldNum, @$node->{$fieldName}->value, 'YOUR RANK IS'); // 38 / 59
        $fieldName = 'field_sprint_10m';
        $formFields['field_wrap_6'][$fieldName] = $this->getFormField($fieldName, ++$fldNum, @$node->{$fieldName}->value, '10M SPRINT', 'Sec'); // 39 / 60
        $fieldName = 'field_sprint_10m_e';
        $formFields['field_wrap_6'][$fieldName] = $this->getFormField($fieldName, ++$fldNum, @$node->{$fieldName}->value, '(E) 10M SPRINT', 'Sec'); // 40 / 61
        $fieldName = 'field_sprint_40m';
        $formFields['field_wrap_6'][$fieldName] = $this->getFormField($fieldName, ++$fldNum, @$node->{$fieldName}->value, '40M SPRINT', 'Sec'); // 41 / 62
        $fieldName = 'field_sprint_40m_e';
        $formFields['field_wrap_6'][$fieldName] = $this->getFormField($fieldName, ++$fldNum, @$node->{$fieldName}->value, '(E) 40M SPRINT', 'Sec'); // 42 / 63

        //~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ MID-THIGH PULL
        $formFields['field_wrap_7'] = array(
          '#type' => 'fieldset',
          '#title' => $this->t('<div style="text-align: center; font-weight: bold;">MID-THIGH PULL</div>'),
          '#prefix' => '<div id="form_fields_wrap" class="form_fields_wrap">',
          '#suffix' => '</div>',
        );
        $fldNum = 0;

        $fieldName = 'field_mid_thigh_rank';
        $formFields['field_wrap_7'][$fieldName] = $this->getFormField($fieldName, ++$fldNum, @$node->{$fieldName}->value, 'YOUR RANK IS'); // 43 / 64
        $fieldName = 'field_mid_thigh_your_weight';
        $formFields['field_wrap_7'][$fieldName] = $this->getFormField($fieldName, ++$fldNum, @$node->{$fieldName}->value, 'YOUR WEIGHT', 'Lbs'); // 44 / 65
        $fieldName = 'field_mt_abs_strength_lbs';
        $formFields['field_wrap_7'][$fieldName] = $this->getFormField($fieldName, ++$fldNum, @$node->{$fieldName}->value, 'ABSOLUTE STRENGTH', 'N'); // 45 / 66
        $fieldName = 'field_mt_abs_strength_n';
        $formFields['field_wrap_7'][$fieldName] = $this->getFormField($fieldName, ++$fldNum, @$node->{$fieldName}->value, 'ABSOLUTE STRENGTH', 'Lbs'); // 46 / 67
        $fieldName = 'field_mt_abs_strength_lbs_e';
        $formFields['field_wrap_7'][$fieldName] = $this->getFormField($fieldName, ++$fldNum, @$node->{$fieldName}->value, '(E) ABSOLUTE STRENGTH', 'N'); // 47 / 68
        $fieldName = 'field_mt_abs_strength_n_e';
        $formFields['field_wrap_7'][$fieldName] = $this->getFormField($fieldName, ++$fldNum, @$node->{$fieldName}->value, '(E) ABSOLUTE STRENGTH', 'Lbs'); // 48 / 69
        $fieldName = 'field_mid_thigh_rel_strength';
        $formFields['field_wrap_7'][$fieldName] = $this->getFormField($fieldName, ++$fldNum, @$node->{$fieldName}->value, 'RELATIVE STRENGTH', '%'); // 49 / 70
        $fieldName = 'field_mid_thigh_rel_strength_e';
        $formFields['field_wrap_7'][$fieldName] = $this->getFormField($fieldName, ++$fldNum, @$node->{$fieldName}->value, '(E) RELATIVE STRENGTH', '%'); // 50 / 71

        //~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ RATE OF FORCE
        $formFields['field_wrap_8'] = array(
          '#type' => 'fieldset',
          '#title' => $this->t('<div style="text-align: center; font-weight: bold;">RATE OF FORCE</div>'),
          '#prefix' => '<div id="form_fields_wrap" class="form_fields_wrap">',
          '#suffix' => '</div>',
        );
        $fldNum = 0;

        $fieldName = 'field_force_rate_peak';
        $formFields['field_wrap_8'][$fieldName] = $this->getFormField($fieldName, ++$fldNum, @$node->{$fieldName}->value, 'PEAK FORCE', 'Lbs'); // 51 / 72
        $fieldName = 'field_force_rate_your_weight';
        $formFields['field_wrap_8'][$fieldName] = $this->getFormField($fieldName, ++$fldNum, @$node->{$fieldName}->value, 'YOUR WEIGHT', 'Lbs'); // 52 / 73
        $fieldName = 'field_force_rate_peak_weight';
        $formFields['field_wrap_8'][$fieldName] = $this->getFormField($fieldName, ++$fldNum, @$node->{$fieldName}->value, 'PEAK FORCE/BODY WEIGHT', '#.#x'); // 53 / 74
        $fieldName = 'field_force_rate_force_n';
        $formFields['field_wrap_8'][$fieldName] = $this->getFormField($fieldName, ++$fldNum, @$node->{$fieldName}->value, 'FORCE', 'N'); // 54 / 75
        $fieldName = 'field_force_rate_rfd';
        $formFields['field_wrap_8'][$fieldName] = $this->getFormField($fieldName, ++$fldNum, @$node->{$fieldName}->value, 'RFD', '%'); // 55 / 76
        $fieldName = 'field_force_rate_low_peak';
        $formFields['field_wrap_8'][$fieldName] = $this->getFormField($fieldName, ++$fldNum, @$node->{$fieldName}->value, 'LOW PEAK FORCE'); // 56 / 77
        $fieldName = 'field_force_rate_medium_peak';
        $formFields['field_wrap_8'][$fieldName] = $this->getFormField($fieldName, ++$fldNum, @$node->{$fieldName}->value, 'MEDIUM PEAK FORCE'); // 57 / 78
        $fieldName = 'field_force_rate_high_peak';
        $formFields['field_wrap_8'][$fieldName] = $this->getFormField($fieldName, ++$fldNum, @$node->{$fieldName}->value, 'HIGH PEAK FORCE'); // 58 / 79

        //~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ DYNAMIC STRENGTH INDEX
        $formFields['field_wrap_9'] = array(
          '#type' => 'fieldset',
          '#title' => $this->t('<div style="text-align: center; font-weight: bold;">DYNAMIC STRENGTH INDEX</div>'),
          '#prefix' => '<div id="form_fields_wrap" class="form_fields_wrap">',
          '#suffix' => '</div>',
        );
        $fldNum = 0;

        $fieldName = 'field_dynamic_strength_impt_peak';
        $formFields['field_wrap_9'][$fieldName] = $this->getFormField($fieldName, ++$fldNum, @$node->{$fieldName}->value, 'IMTP PEAK FORCE', 'N'); // 59 / 80
        $fieldName = 'field_dynamic_strength_cmj_peak';
        $formFields['field_wrap_9'][$fieldName] = $this->getFormField($fieldName, ++$fldNum, @$node->{$fieldName}->value, 'CMJ PEAK FORCE', 'N'); // 60 / 81
        $fieldName = 'field_dynamic_strength_dsi_score';
        $formFields['field_wrap_9'][$fieldName] = $this->getFormField($fieldName, ++$fldNum, @$node->{$fieldName}->value, 'YOUR DSI SCORE', '.##'); // 61 / 82


        //------------------------------------------ elite-only form fields
        if($realFormType == 'Elite')
        {
          //~~~~~~~~~~~~~~~~~~~~ Supine Med Ball Chest Throw
          $formFields['field_wrap_9E'] = array(
            '#type' => 'fieldset',
            '#title' => $this->t('<div style="text-align: center; font-weight: bold;">Supine Med Ball Chest Throw</div>'),
            '#prefix' => '<div id="form_fields_wrap" class="form_fields_wrap">',
            '#suffix' => '</div>',
          );
          $fldNum = 0;

          $fieldName = 'field_static_chest_throw';
          $formFields['field_wrap_9E'][$fieldName] = $this->getFormField($fieldName, ++$fldNum, @$node->{$fieldName}->value, 'STATIC CHEST THROW', 'W'); // 83
          $fieldName = 'field_polyometric_chest_throw';
          $formFields['field_wrap_9E'][$fieldName] = $this->getFormField($fieldName, ++$fldNum, @$node->{$fieldName}->value, 'PLYOMETRIC CHEST THROW', 'W'); // 84
          $fieldName = 'field_eccentric_ratio_eur';
          $formFields['field_wrap_9E'][$fieldName] = $this->getFormField($fieldName, ++$fldNum, @$node->{$fieldName}->value, 'EUR', '#.#'); // 85
          $fieldName = 'field_eur_low';
          $formFields['field_wrap_9E'][$fieldName] = $this->getFormField($fieldName, ++$fldNum, @$node->{$fieldName}->value, 'LOW EUR SCORE', ''); // 86
          $fieldName = 'field_eur_medium';
          $formFields['field_wrap_9E'][$fieldName] = $this->getFormField($fieldName, ++$fldNum, @$node->{$fieldName}->value, 'MEDIUM EUR SCORE', ''); // 87
          $fieldName = 'field_eur_high';
          $formFields['field_wrap_9E'][$fieldName] = $this->getFormField($fieldName, ++$fldNum, @$node->{$fieldName}->value, 'HIGH EUR SCORE', ''); // 88

          //~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ Rotational med Ball Throw Test
          $formFields['field_wrap_aE'] = array(
            '#type' => 'fieldset',
            '#title' => $this->t('<div style="text-align: center; font-weight: bold;">Rotational Med Ball Throw</div>'),
            '#prefix' => '<div id="form_fields_wrap" class="form_fields_wrap">',
            '#suffix' => '</div>',
          );
          $fldNum = 0;

          $fieldName = 'field_rot_rank';
          $formFields['field_wrap_aE'][$fieldName] = $this->getFormField($fieldName, ++$fldNum, @$node->{$fieldName}->value, 'YOUR RANK IS', ''); // 89
          $fieldName = 'field_rot_ball_throw';
          $formFields['field_wrap_aE'][$fieldName] = $this->getFormField($fieldName, ++$fldNum, @$node->{$fieldName}->value, 'ROTATIONAL POWER', 'W'); // 90
          $fieldName = 'field_rot_ball_throw_e';
          $formFields['field_wrap_aE'][$fieldName] = $this->getFormField($fieldName, ++$fldNum, @$node->{$fieldName}->value, '(E) ROTATIONAL POWER', 'W'); // 91

          //~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ Strength Endurance Testing
          $formFields['field_wrap_bE'] = array(
            '#type' => 'fieldset',
            '#title' => $this->t('<div style="text-align: center; font-weight: bold;">Strength Endurance Testing</div>'),
            '#prefix' => '<div id="form_fields_wrap" class="form_fields_wrap">',
            '#suffix' => '</div>',
          );
          $fldNum = 0;

          $fieldName = 'field_chin_ups_reps';
          $formFields['field_wrap_bE'][$fieldName] = $this->getFormField($fieldName, ++$fldNum, @$node->{$fieldName}->value, 'CHIN-UPS', 'Reps'); // 92
          $fieldName = 'field_chin_ups_reps_e';
          $formFields['field_wrap_bE'][$fieldName] = $this->getFormField($fieldName, ++$fldNum, @$node->{$fieldName}->value, '(E) CHIN-UPS', 'Reps'); // 93
          $fieldName = 'field_push_ups_reps';
          $formFields['field_wrap_bE'][$fieldName] = $this->getFormField($fieldName, ++$fldNum, @$node->{$fieldName}->value, 'PUSH-UPS', 'Reps'); // 94
          $fieldName = 'field_push_ups_reps_e';
          $formFields['field_wrap_bE'][$fieldName] = $this->getFormField($fieldName, ++$fldNum, @$node->{$fieldName}->value, '(E) PUSH-UPS', 'Reps'); // 95
          $fieldName = 'field_single_leg_squat';
          $formFields['field_wrap_bE'][$fieldName] = $this->getFormField($fieldName, ++$fldNum, @$node->{$fieldName}->value, 'SINGLE LEG SQUAT', 'R)## L)##'); // 96
          $fieldName = 'field_single_leg_squat_e';
          $formFields['field_wrap_bE'][$fieldName] = $this->getFormField($fieldName, ++$fldNum, @$node->{$fieldName}->value, '(E) SINGLE LEG SQUAT', 'R)## L)##'); // 97

          //~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ 5-10-5 AGILITY
          $formFields['field_wrap_cE'] = array(
            '#type' => 'fieldset',
            '#title' => $this->t('<div style="text-align: center; font-weight: bold;">5-10-5 AGILITY</div>'),
            '#prefix' => '<div id="form_fields_wrap" class="form_fields_wrap">',
            '#suffix' => '</div>',
          );
          $fldNum = 0;

          $fieldName = 'field_5_10_5_rank';
          $formFields['field_wrap_cE'][$fieldName] = $this->getFormField($fieldName, ++$fldNum, @$node->{$fieldName}->value, 'YOUR RANK IS', ''); // 98
          $fieldName = 'field_5_10_5_agile';
          $formFields['field_wrap_cE'][$fieldName] = $this->getFormField($fieldName, ++$fldNum, @$node->{$fieldName}->value, '5-10-5 AGILITY', '##.# Secs'); // 99
          $fieldName = 'field_5_10_5_agile_e';
          $formFields['field_wrap_cE'][$fieldName] = $this->getFormField($fieldName, ++$fldNum, @$node->{$fieldName}->value, '(E) 5-10-5 AGILITY', '##.# Secs'); // 100

          //~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ 1.5 MILE BIKE
          $formFields['field_wrap_dE'] = array(
            '#type' => 'fieldset',
            '#title' => $this->t('<div style="text-align: center; font-weight: bold;">1.5 MILE BIKE</div>'),
            '#prefix' => '<div id="form_fields_wrap" class="form_fields_wrap">',
            '#suffix' => '</div>',
          );
          $fldNum = 0;

          $fieldName = 'field_1_5_mile_bike';
          $formFields['field_wrap_dE'][$fieldName] = $this->getFormField($fieldName, ++$fldNum, @$node->{$fieldName}->value, '1.5 MILE BIKE', '##.#'); // 101
          $fieldName = 'field_1_5_mile_bike_e';
          $formFields['field_wrap_dE'][$fieldName] = $this->getFormField($fieldName, ++$fldNum, @$node->{$fieldName}->value, '(E) 1.5 MILE BIKE', '##.#'); // 102
          $fieldName = 'field_heart_rate_recover';
          $formFields['field_wrap_dE'][$fieldName] = $this->getFormField($fieldName, ++$fldNum, @$node->{$fieldName}->value, 'HEART RATE', '## Secs'); // 103
          $fieldName = 'field_heart_rate_recover_e';
          $formFields['field_wrap_dE'][$fieldName] = $this->getFormField($fieldName, ++$fldNum, @$node->{$fieldName}->value, '(E) HEART RATE', '## Secs'); // 104


          /*********** elite fields start ************/
          // if( 0   &&   $realFormType == 'Elite'){
          //   $required = TRUE;
          // }else{
          //   $required = FALSE;
          // }
          // if( 0   &&   $realFormType == 'Elite') {
          //     //UE Power (SPM Ball Throw)
          //     $formFields['form_fields_wrap']['ue_power'] = array(
          //       '#type' => 'fieldset',
          //       '#title' => $this->t('UE Power (SSM Ball Throw)'),
          //       '#prefix' => '<div id="ue_power1" class="sm_cls">',
          //       '#suffix' => '</div>',
          //     );
          //     $formFields['form_fields_wrap']['ue_power']['power'] = array (
          //       '#type' => 'textfield',
          //       '#default_value' => $field_power_w_ssm_ipe,
          //       #'#required' => $required,
          //       '#attributes' => array(
          //         'placeholder' => t('Power (W)'),
          //       ),
          //     );
          //       //UE Power (SPM Ball Throw)
          //       $formFields['form_fields_wrap']['ue_power_spm'] = array(
          //       '#type' => 'fieldset',
          //       '#title' => $this->t('UE Power (SPM Ball Throw)'),
          //       '#prefix' => '<div id="ue_power2" class="sm_cls">',
          //       '#suffix' => '</div>',
          //     );
          //     $formFields['form_fields_wrap']['ue_power_spm']['power_spm'] = array (
          //       '#type' => 'textfield',
          //       '#default_value' => $field_power_w_spm_ipe,
          //       #'#required' => $required,
          //       '#attributes' => array(
          //         'placeholder' => t('Power (W)'),
          //       ),
          //     );
          //     //UE Power (RM Ball Throw)
          //       $formFields['form_fields_wrap']['ue_power_rm'] = array(
          //       '#type' => 'fieldset',
          //       '#title' => $this->t('UE Power (RM Ball Throw)'),
          //       '#prefix' => '<div id="ue_power3" class="sm_cls">',
          //       '#suffix' => '</div>',
          //     );
          //     $formFields['form_fields_wrap']['ue_power_rm']['power_rm'] = array (
          //       '#type' => 'textfield',
          //       '#default_value' => $field_power_w_rm_ipe,
          //       #'#required' => $required,
          //       '#attributes' => array(
          //         'placeholder' => t('Power (W)'),
          //       ),
          //     );
          //     //Strength Endurance (Pull-ups,Push-ups,Single Leg Squats)
          //     $formFields['form_fields_wrap']['strength_endurance'] = array(
          //       '#type' => 'fieldset',
          //       '#title' => $this->t('Strength Endurance (Pull-ups,Push-ups,Single Leg Squats)'),
          //       '#prefix' => '<div id="strength_endurance" class="sm_cls">',
          //       '#suffix' => '</div>',
          //     );
          //       $formFields['form_fields_wrap']['strength_endurance']['repetitions'] = array (
          //       '#type' => 'textfield',
          //       '#default_value' => $field_repetitions_se_ipe,
          //       #'#required' => $required,
          //       '#attributes' => array(
          //         'placeholder' => t('Repetitions (#)'),
          //       ),
          //     );
          //     //Change of Direction (5-10-5 Pro Agility Test)
          //         $formFields['form_fields_wrap']['change_of_direction'] = array(
          //       '#type' => 'fieldset',
          //       '#title' => $this->t('Change of Direction (5-10-5 Pro Agility Test)'),
          //       '#prefix' => '<div id="change_of_direction" class="sm_cls">',
          //       '#suffix' => '</div>',
          //     );
          //       $formFields['form_fields_wrap']['change_of_direction']['power_ch'] = array (
          //       '#type' => 'textfield',
          //       '#default_value' => $field_power_w_cfd_ipe,
          //       #'#required' => $required,
          //       '#attributes' => array(
          //         'placeholder' => t('Power (W)'),
          //       ),
          //     );

        }


        //~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ SUMMARY
        $formFields['field_wrap_m'] = array(
          '#type' => 'fieldset',
          '#title' => $this->t('<div style="text-align: center; font-weight: bold;">SUMMARY</div>'),
          '#prefix' => '<div id="form_fields_wrap" class="form_fields_wrap">',
          '#suffix' => '</div>',
        );
        $fldNum = 0;

        $fieldName = 'field_summary_reactive';
        $formFields['field_wrap_m'][$fieldName] = $this->getFormField($fieldName, ++$fldNum, @$node->{$fieldName}->value, 'REACTIVE STRENGTH', '#.#'); // 62 / 105
        $fieldName = 'field_summary_reactive_e';
        $formFields['field_wrap_m'][$fieldName] = $this->getFormField($fieldName, ++$fldNum, @$node->{$fieldName}->value, '(E) REACTIVE STRENGTH', '#.#'); // 63 / 106
        $fieldName = 'field_summary_reactive_b';
        $formFields['field_wrap_m'][$fieldName] = $this->getFormField($fieldName, ++$fldNum, @$node->{$fieldName}->value, '(B) REACTIVE STRENGTH', '%tile'); // 64 / 107
        $fieldName = 'field_summary_dynamic';
        $formFields['field_wrap_m'][$fieldName] = $this->getFormField($fieldName, ++$fldNum, @$node->{$fieldName}->value, 'DYNAMIC STRENGTH', '##%'); // 65 / 108
        $fieldName = 'field_summary_dynamic_e';
        $formFields['field_wrap_m'][$fieldName] = $this->getFormField($fieldName, ++$fldNum, @$node->{$fieldName}->value, '(E) DYNAMIC STRENGTH', '##%'); // 66 / 109
        $fieldName = 'field_summary_dynamic_b';
        $formFields['field_wrap_m'][$fieldName] = $this->getFormField($fieldName, ++$fldNum, @$node->{$fieldName}->value, '(B) DYNAMIC STRENGTH', 'N/A'); // 67 / 110
        $fieldName = 'field_summary_ecc';
        $formFields['field_wrap_m'][$fieldName] = $this->getFormField($fieldName, ++$fldNum, @$node->{$fieldName}->value, 'ECC. RATIO', '#.##'); // 68 / 111
        $fieldName = 'field_summary_ecc_e';
        $formFields['field_wrap_m'][$fieldName] = $this->getFormField($fieldName, ++$fldNum, @$node->{$fieldName}->value, '(E) ECC. RATIO', '#.##'); // 69 / 112
        $fieldName = 'field_summary_ecc_b';
        $formFields['field_wrap_m'][$fieldName] = $this->getFormField($fieldName, ++$fldNum, @$node->{$fieldName}->value, '(B) ECC. RATIO', 'N/A'); // 70 / 113
        $fieldName = 'field_summary_relative';
        $formFields['field_wrap_m'][$fieldName] = $this->getFormField($fieldName, ++$fldNum, @$node->{$fieldName}->value, 'RELATIVE STRENGTH', 'Lbs'); // 71 / 114
        $fieldName = 'field_summary_relative_e';
        $formFields['field_wrap_m'][$fieldName] = $this->getFormField($fieldName, ++$fldNum, @$node->{$fieldName}->value, '(E) RELATIVE STRENGTH', 'Lbs'); // 72 / 115
        $fieldName = 'field_summary_relative_b';
        $formFields['field_wrap_m'][$fieldName] = $this->getFormField($fieldName, ++$fldNum, @$node->{$fieldName}->value, '(B) RELATIVE STRENGTH', '%tile'); // 73 / 116
      }

      /*********** elite fields start ************/

        //hidden fields

      if($realFormType == 'Elite'){
        $formtype_val = 'Elite';
      }elseif($realFormType == 'Starter'){
        $formtype_val = 'Starter';
      }elseif($realFormType == 'Professional'){
        $formtype_val = 'Professional';
      }

      $formFields['field_assessment_type'] = array(
        '#type' => 'hidden',
        '#value' => 'individual',
      );

      $formFields['field_form_type'] = array(
        '#type' => 'hidden',
        '#value' => $formtype_val,
      );

      $formFields['field_booked_id'] = array(
        '#type' => 'hidden',
        '#value' => $field_booked_id,
        );

      // not used, using field_sport_assessment, but just in case, maybe set default value
      $formFields['sport'] = array(
        '#type' => 'hidden',
        '#value' => $sport,
        );

        $formFields['field_first_name'] = array(
        '#type' => 'hidden',
        '#value' => $first_name,
        );

        $formFields['field_last_name'] = array(
        '#type' => 'hidden',
        '#value' => $last_name,
        );

        $formFields['field_athelete_nid'] = array(
        '#type' => 'hidden',
        '#value' => $nid,
      );


      $formFields['actions'] = array(
        '#type' => 'fieldset',
        '#attributes' => array(
          'style' => 'position: sticky; bottom: 0; background-color: white; padding-top: 4px;',
        ),
        '#prefix' => '<div>',
        '#suffix' => '</div>',
      );

      $formFields['actions']['#type'] = 'actions';


      $formFields['actions']['draft'] = array(
        '#type' => 'submit',
        '#name' => 'save_unpublished',
        '#value' => $this->t('SAVE'),
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


      $formFields['actions']['submit'] = array(
        '#type' => 'submit',
        '#name' => 'save_published',
        '#value' => $this->t('SAVE & PUBLISH'),
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
      // ksm(['formFields', $formFields]);

      return $formFields;
    }
    else {
      return;
    }
  }


  /**
   * {@inheritdoc}
   */
    public function validateForm(array &$formFields, FormStateInterface $form_state) {
    }


  /**
   * {@inheritdoc}
   */
  protected function getFormData(FormStateInterface $form_state, bool $includeUnits) {
    $data = [];
    foreach ($form_state->getValues() as $key => $value) {
      if (!preg_match('/^(.+)_unit$/i', $key)) {
        $data[$key] = $value;
      }
    }
    if ($includeUnits) {
      foreach ($form_state->getValues() as $key => $value) {
        if (preg_match('/^(.+)_unit$/i', $key, $matches)) {
          if (isset($data[$matches[1]]) && $data[$matches[1]] != '') {
            // add units to end - ie " lbs"
            $data[$matches[1]] .= ' ' . $value;
          }
        }
      }
    }
    return $data;
  }


  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$formFields, FormStateInterface $form_state) {
    $param = \Drupal::request()->query->all();
    $triggerElement = $form_state->getTriggeringElement();
    if(!empty($param)){
        $nid = $param['nid'];
        $formtype = $param['formtype'];
        $Assess_type = $param['Assess_type'];
        $field_booked_id = $param['field_booked_id'];
        $st = $param['st'];
        $assess_nid = $param['assess_nid'];
    }
    //check node already exist
    $query1 = \Drupal::entityQuery('node');
    $query1->condition('type', 'athlete_assessment_info');
    $query1->condition('field_booked_id',$field_booked_id, 'IN');
    $nids1 = $query1->execute();


    //current user
    $current_user = \Drupal::currentUser();
    $user_id = $current_user->id();
    $user = \Drupal\user\Entity\User::load($user_id);

    $form_data = $this->getFormData($form_state, false);

    //jjj hack, added all "session" stuff here, to create pdf.
    // session_start();
    // $_SESSION['temp-session-form-values'] = [];
    // $_SESSION['temp-session-form-values'] = $this->getFormData($form_state, true);
    // Form field to pdf field mappings:
    // $_SESSION['temp-session-form-values']['FULL_NAME_TOP'] = $_SESSION['temp-session-form-values']['first_name'] . ' ' . $_SESSION['temp-session-form-values']['last_name'];

    $form_data['title'] = @$form_data['title'] ? $form_data['title'] : 'Starter Assessment'; // $_SESSION['temp-session-form-values']['field_form_type'];

    $pdf_template_fid = AssessmentService::getPdfTemplateId($form_data['field_form_type']);

    $default_entity_id = $form_state->getValue('form_token'); // currently not used

    $message = '<div style="padding: 2px; margin: 0 15px 2px 15px; font-size: 1.1em; font-weight: bold; color:green; background-color: #eee; border-radius: 4px;">';

    if (@$form_data['save_published']) {
      $message .= ' Saved and Published!';
    } else if (@$form_data['save_unpublished']) {
      $message .= ' Saved!';
    }

    // ksm(['$pdf_template_fid, $assess_nid, form_data, $param', $pdf_template_fid, $assess_nid, $form_data, $param]);

    if ($pdf_template_fid) {
      // $fillPdfUrl = AssessmentService::getFillPdfUrl($pdf_template_fid, $assess_nid);
      // $message .= ' &nbsp; &nbsp; <a href="'.$fillPdfUrl.'" target="_blank">Generate PDF</a><br>';
    }
    $message .= "</div>";

    /****
     * TODO
     * error checking below.
     */
    /***/
      // elseif(!is_numeric($form_state->getValue('starter_weight_rea_str')) || empty($form_state->getValue('starter_weight_rea_str'))){
      //   $message = '<p style="color:red;">"Weight (N) Calculated into Ibs" Required or Numeric</p>';
      // }

    if (  0  ) {
      // if (!is_numeric($form_state->getValue('starter_weight_rea_str')) || empty($form_state->getValue('starter_weight_rea_str'))) {
      //   $message = '<p style="color:red;">"Jump Height (ln)" Required or Numeric</p>';
      // }
      // elseif (!is_numeric($form_state->getValue('field_jump_height_in_reactive')) || empty($form_state->getValue('field_jump_height_in_reactive'))) {
      //   $message = '<p style="color:red;">"Jump Height (ln)" Required or Numeric</p>';
      // }
      // elseif(!is_numeric($form_state->getValue('field_rsi_reactive')) || empty($form_state->getValue('field_rsi_reactive'))){
      //   $message = '<p style="color:red;">"RSI" Required or Numeric</p>';
      // }
      // elseif(!is_numeric($form_state->getValue('field_jump_height_in_elastic')) || empty($form_state->getValue('field_jump_height_in_elastic'))){
      //   $message = '<p style="color:red;">"Jump Height (ln)" Required or Numeric</p>';
      // }
      // elseif(!is_numeric($form_state->getValue('field_peak_propulsive_elastic')) || empty($form_state->getValue('field_peak_propulsive_elastic'))){
      //   $message = '<p style="color:red;">"Peak Propulslve Force (N)" Required or Numeric</p>';
      // }
      // elseif(!is_numeric($form_state->getValue('field_peak_power_w_elastic')) || empty($form_state->getValue('field_peak_power_w_elastic'))){
      //   $message = '<p style="color:red;">"Peak Power (W)" Required or Numeric</p>';
      // }
      // elseif(!is_numeric($form_state->getValue('field_jump_height_in_ballistic')) || empty($form_state->getValue('field_jump_height_in_ballistic'))){
      //   $message = '<p style="color:red;">"Jump Height (ln)" Required or Numeric</p>';
      // }
      // elseif(!is_numeric($form_state->getValue('field_peak_propulsive_ballistic')) || empty($form_state->getValue('field_peak_propulsive_ballistic'))){
      //   $message = '<p style="color:red;">"Peak Propulslve Force (N)" Required or Numeric</p>';
      // }
      // elseif(!is_numeric($form_state->getValue('field_10m_time_sec_sprint')) || empty($form_state->getValue('field_10m_time_sec_sprint'))){
      //   $message = '<p style="color:red;">"10 M Time (sec)" Required or Numeric</p>';
      // }
      // elseif(!is_numeric($form_state->getValue('field_40m_time_sec_sprint')) || empty($form_state->getValue('field_40m_time_sec_sprint'))){
      //   $message = '<p style="color:red;">"40 M Time (sec)" Required or Numeric</p>';
      // }
      // elseif(!is_numeric($form_state->getValue('field_peak_force_n_maximal')) || empty($form_state->getValue('field_peak_force_n_maximal'))){
      //   $message = '<p style="color:red;">"Peak Force (N)" Required or Numeric</p>';
      // }elseif(!is_numeric($form_state->getValue('field_rfd_100ms_n_maximal')) || empty($form_state->getValue('field_rfd_100ms_n_maximal'))){
      //   $message = '<p style="color:red;">"RFD @ 100ms (N)" Required or Numeric</p>';
      // }
      // elseif( (!is_numeric($form_state->getValue('power')) || empty($form_state->getValue('power'))) && $formtype == 'Elite' ){
      //   $message = '<p style="color:red;">"Power (W)" Required or Numeric</p>';
      // }
      // elseif((!is_numeric($form_state->getValue('power_spm')) || empty($form_state->getValue('power_spm'))) && $formtype == 'Elite'){

      //   $message = '<p style="color:red;">"Power (W)" Required or Numeric</p>';
      // }
      // elseif( (!is_numeric($form_state->getValue('power_rm')) || empty($form_state->getValue('power_rm'))) && $formtype == 'Elite'){
      //   $message = '<p style="color:red;">"Power (W)" Required or Numeric</p>';
      // }
      // elseif((!is_numeric($form_state->getValue('repetitions')) || empty($form_state->getValue('repetitions'))) && $formtype == 'Elite'){
      //   $message = '<p style="color:red;">"Repetitions (#)" Required or Numeric</p>';
      // }elseif((!is_numeric($form_state->getValue('power_ch')) || empty($form_state->getValue('power_ch'))) && $formtype == 'Elite'){
      //   $message = '<p style="color:red;">"Power (W)" Required or Numeric</p>';
      // }

        }
        else
        {
          if(!$nids1){
            //--------------------------------------------------------------- create db record.
            $node = Node::create([
              'type' => 'athlete_assessment_info',
            ]);
          }else{
            //--------------------------------------------------------------- update db record.
            $node = Node::load($nids1);
          }

          // $node->set('title', $form_data['starter_weight_rea_str']);
          // $node->set('starter_weight_rea_str', $form_data['starter_weight_rea_str']);
          //??? $node->set('field_jump_height_in_reactive', $form_data['starter_weight_rea_str']);

          $node->set('title', 'starter forms'); // $form_data['title']);  -- think this is needed for fillpdf mod.

          foreach ($form_data as $key => $val) {
            if (isset($form_data[$key])) {
              if ($key != 'draft' && $key != 'submit' && $key != 'save_published' && $key != 'save_unpublished' && $key != 'form_build_id' && $key != 'form_token' && $key != 'form_id') {
                $node->set($key, $form_data[$key]);
              }
            }
          }
        }

        if (@$form_data['save_unpublished']) {
          $node->set('field_status', 'incomplete'); //dd
          $node->setPublished(FALSE);
        }
        else
        {
          // if "SAVE - ALL FIELDS COMPLETED" trigger
          $node->set('field_status', 'complete'); //dd
          $node->setPublished(TRUE); // may change to unpublished status below.
        }
        $node->save();

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
