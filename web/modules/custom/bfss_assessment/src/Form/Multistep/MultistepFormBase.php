<?php
/**
 * @file
 * Contains \Drupal\bfss_assessment\Form\Multistep\MultistepFormBase.
 */

namespace Drupal\bfss_assessment\Form\Multistep;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Session\SessionManagerInterface;
use Drupal\user\PrivateTempStoreFactory;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\bfss_assessment\AssessmentService;
use Drupal\node\Entity\Node;
Use Drupal\node\NodeInterface;

use Symfony\Component\HttpFoundation\RequestStack;
use Drupal\bfss_assessment\BfssPaymentService;

abstract class MultistepFormBase extends FormBase {
  /**
   * Drupal\bfss_assessment\BfssPaymentService definition.
   *
   *
   * @var Drupal\bfss_assessment\BfssPaymentService
   */
  protected $payment;

  /**
   * @var \Drupal\user\PrivateTempStoreFactory
   */
  protected $tempStoreFactory;

  /**
   * @var \Drupal\Core\Session\SessionManagerInterface
   */
  private $sessionManager;

  /**
   * @var \Drupal\Core\Session\AccountInterface
   */
  private $currentUser;

  /**
   * @var \Drupal\user\PrivateTempStore
   */
  protected $store;
  /**
  * Drupal\bfss_assessment\AssessmentService definition.
  *
  * @var \Drupal\bfss_assessment\AssessmentService
  */
  protected $assessmentService;
  /**
   * Constructs a \Drupal\bfss_assessment\Form\Multistep\MultistepFormBase.
   *
   * @param \Drupal\user\PrivateTempStoreFactory $temp_store_factory
   * @param \Drupal\Core\Session\SessionManagerInterface $session_manager
   * @param \Drupal\Core\Session\AccountInterface $current_user
   */
  public function __construct(PrivateTempStoreFactory $temp_store_factory, SessionManagerInterface $session_manager, AccountInterface $current_user, AssessmentService $assessment_service,BfssPaymentService $payment) {
    $this->tempStoreFactory = $temp_store_factory;
    $this->sessionManager = $session_manager;
    $this->currentUser = $current_user;

    $this->store = $this->tempStoreFactory->get('multistep_data');

    $this->assessmentService = $assessment_service;
    //payment
    $this->payment = $payment;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('user.private_tempstore'),
      $container->get('session_manager'),
      $container->get('current_user'),
      $container->get('bfss_assessment.default'),
      $container->get('bfss_assessment.payment')
    );
  }

  /**
   * {@inheritdoc}.
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    // Start a manual session for anonymous users.
    if ($this->currentUser->isAnonymous() && !isset($_SESSION['multistep_form_holds_session'])) {
      $_SESSION['multistep_form_holds_session'] = true;
      $this->sessionManager->start();
    }

    $form = array();
    $assessmentType = 'Scheduled';
    if (empty(\Drupal::request()->get('node_id')) || $this->store->get('is_private')) {
      $assessmentType = 'Private';
    }
    $form['actions']['#type'] = 'actions';
    $form['#prefix'] = $this->t('
      <div class="wrapper">
      <div class="dash-main-right">
            <h1><i class="fas fa-home"></i> &gt; <a href="/dashboard" class="edit_dash" style="margin-right:5px;font-weight: bold; color: #333333;">Dashboard</a> > Your Scheduled Assessment</h1>
            <div class="dash-sub-main">
              <i class="far fa-calendar-alt edit_image_solid"></i>
               <h2><span>YOUR</span><br> Scheduled Assessment  </h2>
            </div>
         </div>
         ');
    $form['#suffix'] = $this->t('</div>');
    $form['heading'] = [
      '#type' => 'markup',
      '#markup' => $this->t('
        <div class="main-head">
          <div class="first">
            <h6>1.&nbsp;&nbsp;Assessment Type</h6>
            <div class="bar"></div>
          </div>
          <div class="second">
            <h6>2.&nbsp;&nbsp;Time</h6>
            <div class="bar"></div>
          </div>
          <div class="third">
            <h6>3.&nbsp;&nbsp;Details</h6>
            <div class="bar"></div>
          </div>
          <div class="fourth">
            <h6>4.&nbsp;&nbsp;Payment</h6>
            <div class="bar"></div>
          </div>
          <div class="fifth">
            <h6>5.&nbsp;&nbsp;Done</h6>
            <div class="bar"></div>
          </div>
        </div>
        ')
    ];
    #attach library for styling
    $form['#attached']['library'][] = 'bfss_assessment/assessment_mulitform_lib';
    $form['actions']['submit'] = array(
      '#type' => 'submit',
      '#value' => $this->t('Submit'),
      '#button_type' => 'primary',
      '#weight' => 10,
    );

    return $form;
  }

  /**
   * Saves the data from the multistep form.
   */
  protected function saveData() {
    // Logic for saving data goes here...
    $keys = [
      'assessment',
      'service',
      'time',
      'first_name',
      'last_name',
      'phone',
      'email',
      'name_on_card',
      'credit_card_number',
      'expiration_month',
      'expiration_year',
      'cvv',
      'address_1',
      'address_2',
      'city',
      'state',
      'zip',
      'country',
      'is_private',
    ];
    // $key[] = 'assessment_title';
    // $key[] = 'payment_status';
    // $key[] = 'extra';
    // $key[] = 'notes';
    // $key[] = 'user_id';
    // $key[] = 'user_name';
    // $key[] = 'until';
    $data = [];
    foreach ($keys as $key) {
      $data[$key] = $this->store->get($key);
    }
    if (isset($data['assessment']) && !empty($data['assessment'])) {
      $node = Node::load($data['assessment']);
      if ($node instanceof NodeInterface) {
        $data['assessment_title'] = $node->getTitle();
      }
    }
    
    #current logged in user data
    $currentUserId =  \Drupal::currentUser()->id();
    if ($currentUserId) {
      $data['user_id'] = $currentUserId;
      $currentUserAcc = \Drupal\user\Entity\User::load($currentUserId);
      if ($currentUserAcc) {
        $data['user_name'] = $currentUserAcc->get('name')->value;
      }
    }
    #get duration
    $until = $this->assessmentService->checkDuration($this->store->get('assessment'), $this->store->get('time'));
    $data['until'] = $until;
    
    #payment code  start
   $pay_data['amount'] = (!empty($data['service']) ? $data['service'] : $data['service']);
   $pay_data['amount_text'] = 'payment';
   $pay_data['fname'] = $data['first_name'];
   $pay_data['lname'] = $data['last_name'];
   $pay_data['address'] = $data['address_1'];
   $pay_data['city'] = $data['city'];
   $pay_data['state'] = $data['state'];
   $pay_data['country'] = $data['country'];
   $pay_data['zip'] = $data['zip'];
   $pay_data['phone'] = $data['phone'];

   $pay_data['expiration_month'] = $data['expiration_month'];
   $pay_data['expiration_year'] = $data['expiration_year'];
   $pay_data['cvv'] = $data['cvv'];
   $pay_data['credit_card_number'] = $data['credit_card_number'];


    if (!empty($pay_data['amount'])) {
      $paymentdone = $this->payment->createTransaction($pay_data);
      // echo "<pre>";
      // print_r($paymentdone);
      // die;
       if (is_array($paymentdone) && isset($paymentdone['status'])) {
        if ($paymentdone['status'] == true) {
          drupal_set_message('Payment successfully received.');
        } else {
          drupal_set_message('Something went wrong! Payment was interrupted. Please try again.','error');
          drupal_set_message( (isset($paymentdone['message'])? $paymentdone['message'] : ''),'error');
        }
      }
    }
    $data['payment_status'] = ((isset($paymentdone['status']) && $paymentdone['status'] == true) ? 'paid' : 'unpaid');
    #payment code  end

    # load entity and save payment
    $pay = \Drupal\bfss_assessment\Entity\BfssPayments::create($data);
    $pay->save();
    #delete temp storage
    $this->deleteStore();
    drupal_set_message($this->t('Thank you!'));

  }

  /**
   * Helper method that removes all the keys from the store collection used for
   * the multistep form.
   */
  protected function deleteStore() {
    $keys = [
      'assessment',
      'service',
      'time',
      'first_name',
      'last_name',
      'phone',
      'email',
      'name_on_card',
      'credit_card_number',
      'expiration_month',
      'expiration_year',
      'cvv',
      'address_1',
      'address_2',
      'city',
      'state',
      'zip',
      'country',
      'is_private',
    ];
    #payment card fields
    $key[] = 'name_on_card';
    $key[] = 'credit_card_number';
    $key[] = 'expiration_month';
    $key[] = 'expiration_year';
    $key[] = 'cvv';
    foreach ($keys as $key) {
      $this->store->delete($key);
    }
  }
}