<?php
namespace Drupal\bfss_assessment\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Drupal\bfss_assessment\BfssPaymentService;


/**
 * Class PaymentTestForm.
 */
class PaymentTestForm extends FormBase {
  /**
   * Drupal\bfss_assessment\BfssPaymentService definition.
   *
   *
   * @var Drupal\bfss_assessment\BfssPaymentService
   */
  protected $payment;


  /**
   * Constructs a new DonationForm object.
   */
	  public function __construct(BfssPaymentService $payment) {
	    $this->payment = $payment;
	  }

	  public static function create(ContainerInterface $container) {
	    return new static(
	      $container->get('bfss_assessment.payment')
	    );
	  }
   /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'angelview_donation_form';
  }


  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
  	$form['special_note'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Special Note'),
      '#placeholder' => $this->t('Special Note'),
      '#maxlength' => 64,
      '#size' => 64,
      '#weight' => 1,
    ];

    $form['fname'] = [
      '#type' => 'textfield',
      '#title' => $this->t('fname'),
    ];

    $form['lname'] = [
      '#type' => 'textfield',
      '#title' => $this->t('lname'),
    ];
    $form['address'] = [
      '#type' => 'textfield',
      '#title' => $this->t('address'),
    ];

    $form['city'] = [
      '#type' => 'textfield',
      '#title' => $this->t('city'),
    ];
    
    $form['state'] = [
      '#type' => 'textfield',
      '#title' => $this->t('state'),
    ];
    
    $form['country'] = [
      '#type' => 'textfield',
      '#title' => $this->t('country'),
    ];

    $form['zip'] = [
      '#type' => 'textfield',
      '#title' => $this->t('zip'),
    ];

    $form['phone'] = [
      '#type' => 'textfield',
      '#title' => $this->t('phone'),
    ];

    $form['price'] = [
      '#type' => 'number',
      '#title' => $this->t('price $'),
    ];
    $form['actions']['#type'] = 'actions';
    $form['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Submit'),
      '#weight' => 50,
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
  	 $formState = $form_state->cleanValues()->getValues();
  	 $data['amount'] = (!empty($formState['price']) ? $formState['price'] : $formState['price']);
  	 $data['amount_text'] = $formState['special_note'];
 	 $data['fname'] = $formState['fname'];
 	 $data['lname'] = $formState['lname'];
 	 $data['address'] = $formState['address'];
 	 $data['city'] = $formState['city'];
 	 $data['state'] = $formState['state'];
 	 $data['country'] = $formState['country'];
 	 $data['zip'] = $formState['zip'];
 	 $data['phone'] = $formState['phone'];
 	 if (!empty($data['amount'])) {
      $paymentdone = $this->payment->createTransaction($data);
       if (is_array($paymentdone) && isset($paymentdone['status'])) {
        if ($paymentdone['status'] == true) {
          drupal_set_message('Payment successfully received.');
        } else {
          drupal_set_message('Something went wrong! Payment was interrupted. Please try again.','error');
          drupal_set_message( (isset($paymentdone['message'])? $paymentdone['message'] : ''),'error');
        }
      }
  	 }

  	// echo "<pre>";
  	// print_r($data['price']);
  	// die;
  }

}