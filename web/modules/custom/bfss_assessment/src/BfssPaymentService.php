<?php
namespace Drupal\bfss_assessment;

use Symfony\Component\HttpFoundation\RequestStack;
use Drupal\Core\Config\ConfigFactoryInterface;
use net\authorize\api\contract\v1 as AnetAPI;
use net\authorize\api\controller as AnetController;

/**
 * Class BfssPaymentService.
 */
class BfssPaymentService {

  /**
   * Symfony\Component\HttpFoundation\RequestStack definition.
   *
   * @var \Symfony\Component\HttpFoundation\RequestStack
   */
  protected $requestStack;
  /**
   * Constructs a new BfssPaymentService object.
   */
  /**
   * Config factory.
   *
   * @var \Drupal\Core\Config\ConfigFactoryInterface
   */
  protected $configFactory;

	  /**
	   * Constructs a new ExactService object.
	   */
	  public function __construct(RequestStack $request_stack, ConfigFactoryInterface $config_factory) {
	    $this->requestStack = $request_stack;
	    $this->configFactory = $config_factory;
	  }

		 public function createTransaction($data) {

		    $config = $this->configFactory->get('bfss_assessment.settings');
		    $auth_login = $config->get('api_login_id');
		    $auth_key = $config->get('transaction_key');
		 	// $auth_login = '7B4rcJ4G';
		  //   $auth_key = '2G5sDdvF798D8D9h';
		    
		    if (is_array($data) && !empty($auth_login) && !empty($auth_key)) {
		      $amount =  $data['amount'];

		      if (!empty($amount)) {
		        $creditCard = $this->createCreditCardData($data);
		        // Add the payment data to a paymentType object
		        $payment = new AnetAPI\PaymentType();
		        $payment->setCreditCard($creditCard);
		        $order = $this->createOrderInformation($data);
		        $customerAddress = $this->setBillToAddress($data);
		        $customerData = $this->setCustomerIdentifyingInformation($data);
		        $duplicateWindowSetting = $this->setTransactionSettings($data);
		        $merchantAuthentication = $this->setMerchantAuthentication($auth_login, $auth_key);

		        if ($data['recurring'] == 1) {
		          $response = $this->createSubscription($data, $amount, $payment, $order, $customerAddress, $merchantAuthentication);
		        }
		        else {
		          $response = $this->createOneTimeTransaction($data, $amount, $payment, $order, $customerAddress, $merchantAuthentication, $customerData, $duplicateWindowSetting);
		        }

		        return $response;
		      }
		      else {
		        return  ["status" => false, "message" => "Amount was not defined \n"];
		      }
		    }

		    return  ["status" => false,"message" => "Something went wrong! Please contact site administration."];
		  }

  protected function createOneTimeTransaction($data, $amount, $payment, $order, $customerAddress, $merchantAuthentication, $customerData, $duplicateWindowSetting) {
    $response = $this->createTransactionRequestType($amount, $order, $payment, $customerAddress, $customerData, $duplicateWindowSetting, $merchantAuthentication);

    if ($response != null) {
      // Check to see if the API request was successfully received and acted upon
      if ($response->getMessages()->getResultCode() == "Ok") {
        // Since the API request was successful, look for a transaction response
        // and parse it to display the results of authorizing the card
        $tresponse = $response->getTransactionResponse();
        if ($tresponse != null && $tresponse->getMessages() != null) {
          return [
            "transaction_status" => $response->getMessages()->getResultCode(),
            "status" => true,
            "message" =>  " Successfully created transaction." . $tresponse->getMessages()[0]->getDescription() . "\n",
            "transaction_id" => $tresponse->getTransId()
          ];
        }
        else {
          if ($tresponse->getErrors() != null) {
            return [
              "transaction_status"=> $response->getMessages()->getResultCode(),
              "status" => false,
              "message" =>  "Transaction Failed. \n " . $tresponse->getErrors()[0]->getErrorText() . "\n"
            ];
          }
          return ["status" => false,"message" =>  "Transaction Failed. \n"];
        }
      }
      else {
        $tresponse = $response->getTransactionResponse();
        if ($tresponse != null && $tresponse->getErrors() != null) {
          return [
            "transaction_status"=> $response->getMessages()->getResultCode(),
            "status" => false,
            "message" => "Transaction Failed. \n " . $tresponse->getErrors()[0]->getErrorText()
          ];
        } else {
          return [
            "transaction_status"=> $response->getMessages()->getResultCode(),
            "status" => false,
            "message" => "Transaction Failed. \n " . $response->getMessages()->getMessage()[0]->getText()
          ];
        }
      }
    } else {
      return [
        "transaction_status"=> $response->getMessages()->getResultCode(),
        "status" => false,
        "message" => "No response returned \n"
      ];
    }
  }

  protected function createTransactionRequestType($amount, $order, $payment, $customerAddress, $customerData, $duplicateWindowSetting, $merchantAuthentication) {
    $refId = 'ref' . time();
    $config = $this->configFactory->get('bfss_assessment.settings');
    $is_live = $config->get('is_live');

    // Create a TransactionRequestType object and add the previous objects to it
    $transactionRequestType = new AnetAPI\TransactionRequestType();
    $transactionRequestType->setTransactionType("authCaptureTransaction");
    $transactionRequestType->setAmount($amount);
    $transactionRequestType->setOrder($order);
    $transactionRequestType->setPayment($payment);
    $transactionRequestType->setBillTo($customerAddress);
    $transactionRequestType->setCustomer($customerData);
    $transactionRequestType->addToTransactionSettings($duplicateWindowSetting);

    $request = new AnetAPI\CreateTransactionRequest();
    $request->setMerchantAuthentication($merchantAuthentication);
    $request->setRefId($refId);
    $request->setTransactionRequest($transactionRequestType);

    $controller = new AnetController\CreateTransactionController($request);
    if ($is_live == 1) {
      $response = $controller->executeWithApiResponse(\net\authorize\api\constants\ANetEnvironment::PRODUCTION);
    }
    else{
      $response = $controller->executeWithApiResponse(\net\authorize\api\constants\ANetEnvironment::SANDBOX);
    }

    return $response;
  }

  protected function createSubscription($data, $amount, $payment, $order, $customerAddress, $merchantAuthentication) {
    $subscription = new AnetAPI\ARBSubscriptionType();
    $subscription->setName("Bfss Subscription");

    $interval = new AnetAPI\PaymentScheduleType\IntervalAType();
    $interval->setLength(1);
    $interval->setUnit("months");

    $paymentSchedule = new AnetAPI\PaymentScheduleType();
    $paymentSchedule->setInterval($interval);
    $paymentSchedule->setStartDate(new \DateTime(date('Y-m-d')));


    $subscription->setPaymentSchedule($paymentSchedule);
    $subscription->setAmount($amount);
    $subscription->setPayment($payment);
    $subscription->setOrder($order);
    $subscription->setBillTo($customerAddress);

    $response = $this->createSubscriptionRequest($merchantAuthentication, $subscription);

    if (($response != null) && ($response->getMessages()->getResultCode() == "Ok")) {
      return [
        "status" => true,
        "message" => "Successfully subscribed.",
        "subscription_id" => $response->getSubscriptionId()
      ];
    }
    else {
      return [
        "status" => false,
        "message" => "Subscription Failed. \n " . $response->getMessages()->getMessage()[0]->getText()
      ];
    }
  }

  protected function createSubscriptionRequest($merchantAuthentication, $subscription) {
    $refId = 'ref' . time();
    $config = $this->configFactory->get('bfss_assessment.settings');
    $is_live = $config->get('is_live');

    $request = new AnetAPI\ARBCreateSubscriptionRequest();
    $request->setMerchantAuthentication($merchantAuthentication);
    $request->setRefId($refId);
    $request->setSubscription($subscription);
    $controller = new AnetController\ARBCreateSubscriptionController($request);

    if ($is_live == 1) {
      $response = $controller->executeWithApiResponse(\net\authorize\api\constants\ANetEnvironment::PRODUCTION);
    }
    else{
      $response = $controller->executeWithApiResponse(\net\authorize\api\constants\ANetEnvironment::SANDBOX);
    }
   
    return $response;
  }

  protected function setMerchantAuthentication($auth_login, $auth_key) {
    $merchantAuthentication = new AnetAPI\MerchantAuthenticationType();
    $merchantAuthentication->setName($auth_login);
    $merchantAuthentication->setTransactionKey($auth_key);

    return $merchantAuthentication;
  }

  protected function createCreditCardData($data) {
    // $card_number = $data['credit_card_number'];
    // $card_expiry_date = $data['expiration_date'];
    // $card_cvv = $data['credit_card_cvv'];
  	$card_number = $data['credit_card_number'];
    $card_expiry_date = $data['expiration_year'].'-'.$data['expiration_month'];
    $card_cvv = $data['cvv'];
    // Create the payment data for a credit card
    $creditCard = new AnetAPI\CreditCardType();
    $creditCard->setCardNumber($card_number);
    $creditCard->setExpirationDate($card_expiry_date);
    $creditCard->setCardCode($card_cvv);

    return $creditCard;
  }

  protected function createOrderInformation($data) {
    $order_invc_num = rand(99,999).time();
    $order_invc_desc = $data['amount_text'];

    // Create order information
    $order = new AnetAPI\OrderType();
    $order->setInvoiceNumber($order_invc_num);
    $order->setDescription($order_invc_desc);

    return $order;
  }

  protected function setBillToAddress($data) {
    // Set the customer's Bill To address
    $customerAddress = new AnetAPI\CustomerAddressType();
    $customerAddress->setFirstName($data['fname']);
    $customerAddress->setLastName($data['lname']);
    $customerAddress->setCompany($data['address']);
    $customerAddress->setAddress($data['address']);
    $customerAddress->setCity($data['city']);
    $customerAddress->setState($data['state']);
    $customerAddress->setZip($data['zip']);
    $customerAddress->setCountry($data['country']);

    return $customerAddress;
  }

  protected function setCustomerIdentifyingInformation($data) {
    // Set the customer's identifying information
    $customerData = new AnetAPI\CustomerDataType();
    $customerData->setType("individual");
    // $customerData->setId($data['billing_phone_number']);
    // $customerData->setEmail($data['billing_email']);

    return $customerData;
  }

  protected function setTransactionSettings() {
    // Add values for transaction settings
    $duplicateWindowSetting = new AnetAPI\SettingType();
    $duplicateWindowSetting->setSettingName("duplicateWindow");
    $duplicateWindowSetting->setSettingValue("60");

    return $duplicateWindowSetting;
  }



}