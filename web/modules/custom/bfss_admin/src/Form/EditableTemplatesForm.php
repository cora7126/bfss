<?php
namespace Drupal\bfss_admin\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Ajax\AjaxResponse;
use Drupal\Core\Ajax\AlertCommand;
use \Drupal\node\Entity\Node;
use Drupal\node\NodeInterface;
Use Drupal\paragraphs\Entity\Paragraph;
use Drupal\Core\Render\Markup;
use Drupal\Core\Ajax\InvokeCommand;
use \Drupal\user\Entity\User;
use Drupal\Core\Ajax\HtmlCommand;
use Drupal\Core\Database\Database;
/**
 * Class EditableTemplatesForm.
 */
class EditableTemplatesForm extends FormBase {
 /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'editable_templates_form';
  }

  public function buildForm(array $form, FormStateInterface $form_state) {

		$forgetpass = $this->get_data(195);
		$reg_success = $this->get_data(196);
		$change_pass = $this->get_data(197);
		$ticketing_initial = $this->get_data(198);
		$ticketing_resolved = $this->get_data(199);


  		$form['message'] = [ //for custom message "like: ajax msgs"
		      '#type' => 'markup',
		      '#markup' => '<div class="result_message"></div>',
    	];

	  	$form['forget_pass_subject'] = [
	        '#type' => 'textfield',
	        '#placeholder' => t('Subject'),
	        '#title' => $this->t('Subject:'),
	        #'#required' => TRUE,
	        '#default_value' => $forgetpass['subject'],
	        '#prefix' => '<div class="faqsec">
	      					<ul class="faq faqct">
	      					 <li class="q"><div class="faq-left">Forgot / Change Password</div><div class="faq-right faq faqct"><img class="arrowimg" src="/modules/custom/bfss_assessment/img/o-arrow.png"/></div></li>
	      					 <li class="a">',
	        '#suffix' => '',

	    ];

	    $form['forget_pass_body'] = [
	        '#type' => 'text_format',
	        '#placeholder' => t('Email Content'),
	        '#title' => $this->t('Email Content:'),
	        #'#required' => TRUE,
	        #'#default_value' => '',
	        #'#access' => TRUE,
	        '#default_value' => $forgetpass['value'],
    		'#format' => $forgetpass['format'],
	       '#suffix' => '</li></ul>
					</div>',
	        
	    ];


		 //Registration - Success
		$form['reg_success_subject'] = [
	        '#type' => 'textfield',
	        '#placeholder' => t('Subject'),
	        '#title' => $this->t('Subject:'),
	        #'#required' => TRUE,
	        '#default_value' => $reg_success['subject'],
	        '#prefix' => '<div class="faqsec">
	      					<ul class="faq faqct">
	      					 <li class="q"><div class="faq-left">Registration - Success</div><div class="faq-right faq faqct"><img class="arrowimg" src="/modules/custom/bfss_assessment/img/o-arrow.png"/></div></li><li class="a">',

	    ];

	    $form['reg_success_body'] = [
	        '#type' => 'text_format',
	        '#placeholder' => t('Email Content'),
	        '#title' => $this->t('Email Content:'),
	        #'#required' => TRUE,
	        '#default_value' => $reg_success['value'],
	        '#format' => $reg_success['format'],
	     
	       '#suffix' => '</li></ul>
					</div>',
	        
	    ];


		// //Password Change
		$form['pass_change_subject'] = [
	        '#type' => 'textfield',
	        '#placeholder' => t('Subject'),
	        '#title' => $this->t('Subject:'),
	        #'#required' => TRUE,
	        '#default_value' => $change_pass['subject'],
	        '#prefix' => '<div class="faqsec">
	      					<ul class="faq faqct">
	      					 <li class="q"><div class="faq-left">Password Change</div><div class="faq-right faq faqct"><img class="arrowimg" src="/modules/custom/bfss_assessment/img/o-arrow.png"/></div></li><li class="a">',

	    ];

	    $form['pass_change_body'] = [
	        '#type' => 'text_format',
	        '#placeholder' => t('Email Content'),
	        '#title' => $this->t('Email Content:'),
	        #'#required' => TRUE,
	        '#default_value' => $change_pass['value'],
	        '#format' => $change_pass['format'],
	       '#suffix' => '</li></ul>
					</div>',
	        
	    ];



		//Ticketing - Initial Ticket
		$form['ticketing_init_subject'] = [
	        '#type' => 'textfield',
	        '#placeholder' => t('Subject'),
	        '#title' => $this->t('Subject:'),
	        #'#required' => TRUE,
	        '#default_value' => $ticketing_initial['subject'],
	        '#prefix' => '<div class="faqsec">
	      					<ul class="faq faqct">
	      					 <li class="q"><div class="faq-left">Ticketing - Initial Ticket</div><div class="faq-right faq faqct"><img class="arrowimg" src="/modules/custom/bfss_assessment/img/o-arrow.png"/></div></li><li class="a">',

	    ];

	    $form['ticketing_init_body'] = [
	        '#type' => 'text_format',
	        '#placeholder' => t('Email Content'),
	        '#title' => $this->t('Email Content:'),
	        #'#required' => TRUE,
	        '#default_value' => $ticketing_initial['value'],
	        '#format' => $ticketing_initial['format'],
	       '#suffix' => '</li></ul>
					</div>',
	        
	    ];


		// //Ticketing - Ticket Resolved
		$form['ticketing_resolved_subject'] = [
	        '#type' => 'textfield',
	        '#placeholder' => t('Subject'),
	        '#title' => $this->t('Subject:'),
	        #'#required' => TRUE,
	        '#default_value' => $ticketing_resolved['subject'],
	        '#prefix' => '<div class="faqsec">
	      					<ul class="faq faqct">
	      					 <li class="q"><div class="faq-left">Ticketing - Ticket Resolved</div><div class="faq-right faq faqct"><img class="arrowimg" src="/modules/custom/bfss_assessment/img/o-arrow.png"/></div></li><li class="a">',
	    ];

	    $form['ticketing_resolved_body'] = [
	        '#type' => 'text_format',
	        '#placeholder' => t('Email Content'),
	        '#title' => $this->t('Email Content:'),
	        #'#required' => TRUE,
	        '#default_value' => $ticketing_resolved['value'],
	        '#format' => $ticketing_resolved['format'],
	        '#suffix' => '</li></ul>
					</div>',
	        
	    ];

		

  	$form['actions']['#type'] = 'actions';
      	$form['actions']['submit'] = [
          '#type' => 'submit',
          '#value' => $this->t('save'),
          '#prefix' => '<div id="athlete_submit" class="athlete_submit">',
          '#suffix' => '</div>',
          '#button_type' => 'primary',
           '#ajax' => [
              'callback' => '::DataSaveAjax', // don't forget :: when calling a class method.
              //'callback' => [$this, 'myAjaxCallback'], //alternative notation
              'disable-refocus' => FALSE, // Or TRUE to prevent re-focusing on the triggering element.
              'event' => 'click',
              'wrapper' => 'edit-output', // This element is updated with this AJAX callback.
              'progress' => [
                'type' => 'throbber',
                'message' => $this->t('Verifying entry...'),
              ],
            ]
      	];

  	return $form;
  }

  public function validateForm(array &$form, FormStateInterface $form_state) {
    
  }

  public function submitForm(array &$form, FormStateInterface $form_state) {

   
  }

  public function DataSaveAjax(array &$form, FormStateInterface $form_state){
  	//ID - 195
  	$nid1 = 195;
  	$subject1 = $form_state->getValue('forget_pass_subject');
  	$body_val1 = $form_state->getValue('forget_pass_body')['value'];
  	$body_format1 = $form_state->getValue('forget_pass_body')['format'];
  	if($nid1){
	  	$this->save_data($nid1,$body_val1,$body_format1,$subject1);
 	}

 	//ID - 196 	 Registration - Success
  	$nid2 = 196;
  	$subject2 = $form_state->getValue('reg_success_subject');
  	$body_val2 = $form_state->getValue('reg_success_body')['value'];
  	$body_format2 = $form_state->getValue('reg_success_body')['format'];
  	if($nid2){
	  	$this->save_data($nid2,$body_val2,$body_format2,$subject2);
 	}


	//ID - 197  Password Change
  	$nid3 = 197;
  	$subject3 = $form_state->getValue('pass_change_subject');
  	$body_val3 = $form_state->getValue('pass_change_body')['value'];
  	$body_format3 = $form_state->getValue('pass_change_body')['format'];
  	if($nid3){
	  	$this->save_data($nid3,$body_val3,$body_format3,$subject3);
 	}

 	//ID - 198  Ticketing - Initial Ticket
  	$nid4 = 198; 
  	$subject4 = $form_state->getValue('ticketing_init_subject');
  	$body_val4 = $form_state->getValue('ticketing_init_body')['value'];
  	$body_format4 = $form_state->getValue('ticketing_init_body')['format'];
  	if($nid4){
	  	$this->save_data($nid4,$body_val4,$body_format4,$subject4);
 	}


 	//ID - 199  Ticketing - Ticket Resolved
  	$nid5 = 199;
  	$subject5 = $form_state->getValue('ticketing_resolved_subject');
  	$body_val5 = $form_state->getValue('ticketing_resolved_body')['value'];
  	$body_format5 = $form_state->getValue('ticketing_resolved_body')['format'];
  	if($nid5){
	  	$this->save_data($nid5,$body_val5,$body_format5,$subject5);
 	}


  	// for success message show
    $message = "successfully saved!";
    $response = new AjaxResponse();
	$response->addCommand(
		new HtmlCommand(
		  '.result_message',
		  '<div class="success_message">'.$message.'</div>'
		)
	      );
	return $response;
  	
  }

  

	 public function save_data($nid,$body_val,$body_format,$subject){
	 		if($nid){
		  	$node = Node::load($nid);
		  	$node->body->value = !empty($body_val)?$body_val:'';
		  	$node->body->format = !empty($body_format)?$body_format:'';
		  	$node->field_subject->value = !empty($subject)?$subject:'';
		  	$node->save();
	 	}
	 } 

	 public function get_data($nid){
	 	if($nid){
	 		$node = Node::load($nid);
	 		
	 		$data =[
	 		'value' => $node->body->value,
	 		'format' => $node->body->format,
	 		'subject' => $node->field_subject->value,
	 		];
	 	}
	 		
	 		return $data;
	 }

}