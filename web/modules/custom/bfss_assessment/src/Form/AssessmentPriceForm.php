<?php

namespace Drupal\bfss_assessment\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Config\ConfigFactoryInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Config\ConfigManagerInterface;

/**
 * Class AssessmentPriceForm.
 */
class AssessmentPriceForm extends ConfigFormBase {

  /**
   * Constructs a new AssessmentPriceForm object.
   */
  public function __construct(ConfigFactoryInterface $config_factory) {
    parent::__construct($config_factory);
  }

  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('config.factory')
    );
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return [
      'bfss_assessment.settings',
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'assessment_price_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    #get default set values
    $config = $this->config('bfss_assessment.settings');
    $saved_prices = $config->get('assessment_prices');
    $saved_pricesArr = [];
    $default_fields = 1;
    if ($saved_prices) {
    	$saved_pricesArr = json_decode($saved_prices, true);
    	if (count($saved_pricesArr)) {
    		$default_fields = count($saved_pricesArr);
    	}
    }
    $form['#tree'] = TRUE;
    #attach lib for design
    $form['#attached']['library'][] = 'bfss_assessment/custom';
    if (!$form_state->get('total_values')) {
      $form_state->set('total_values', $default_fields);
    }
    #form container for fields    
    $form['field_container'] = [
      '#type' => 'container',
    ];
    # create all fields saved or added
    for ($x = 0; $x < $form_state->get('total_values'); $x++) {
      #plan title
      $form['field_container'][$x]['plan'] = [
        '#type' => 'textfield',
        '#title' => $this->t('Plan Title'),
        '#size' => 25,
        // '#title' => $this->t('Plan @num', ['@num' => ($x + 1)]),
      	'#prefix' => "<div class='grouped'>",
      ];
      #set default value from settings
      if (isset($saved_pricesArr[$x]['plan'])) {
	    $form['field_container'][$x]['plan']['#default_value'] = $saved_pricesArr[$x]['plan'];
      }
      #price field
      $form['field_container'][$x]['price'] = [
        '#type' => 'textfield',
        '#title' => $this->t('Price'),
  		'#element_validate' => [
  			[$this, 'check_field_isdecimal'],
  		],
        '#min' => 1,
        '#max' => 5000,
      	'#suffix' => "</div>",
      ];
      #set default value from settings
      if (isset($saved_pricesArr[$x]['price'])) {
	    $form['field_container'][$x]['price']['#default_value'] = $saved_pricesArr[$x]['price'];
      }
    }
    #show a note
    $form['note'] = [
      '#type' => 'markup',
      '#markup' => $this->t('<p class="note"><small>If <strong>plan title</strong> or <strong>price</strong> is empty, that value will be skipped!</small></p>'),
    ];
    #add more fieldset
    $form['addmore'] = [
      '#type' => 'submit',
      '#value' => $this->t('Add More'),
    ];
    # Submit button.
    $form['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Save'),
      '#attributes' => [
        'class' => ['button--primary']
      ],
    ];
    return $form;
  }

  /**
   * validation to check field is decimal only
   */
   public function check_field_isdecimal($element, &$form_state) {
   	if (isset($element['#value']) && !empty($element['#value'])) {
   		$val = $element['#value'];
   		if (!is_float($val) && !is_numeric($val)) {
   			$name = isset($element['#name']) ? $element['#name'] : 'price';
  			$form_state->setErrorByName($name, 'Price Field should be numeric/decimal only!');
   		}
   	}
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    parent::validateForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    // parent::submitForm($form, $form_state);
 $values = $form_state->getValues();
    
    // Decide what action to take based on which button the user clicked.
    switch ($values['op']) {
      case 'Add More':
        $this->addNewFields($form, $form_state);
        break;
        
      default:
        $this->finalSubmit($form, $form_state);
    }

  }


   /**
   * Handle adding new.
   */
  private function addNewFields(array &$form, FormStateInterface $form_state) {
    #get total fieldset values
    $total_values = $form_state->get('total_values');
    $form_state->set('total_values', ($total_values + 1));
    // Rebuild the form.
    $form_state->setRebuild();
  }

  /**
   * Handle submit custom.
   */
  private function finalSubmit(array &$form, FormStateInterface $form_state) {
    $data = $form_state->getValue('field_container');
    if ($data) {
    	foreach ($data as $key => $value) {
    		if (isset($value['plan']) && !empty($value['plan']) && isset($value['price']) && !empty($value['price'])) {
    		}else{
    			unset($data[$key]);
    		}
    	}
    	$data = array_values($data);
    	if ($data) {
    		$this->config('bfss_assessment.settings')
		      ->set('assessment_prices', json_encode($data, true))
		      ->save();
    		drupal_set_message($this->t('Configrations has been saved.'), 'status');
    		return true;
    	}
    }
    drupal_set_message($this->t('No data found hence there is no change in Configrations'), 'error');

  }

}
