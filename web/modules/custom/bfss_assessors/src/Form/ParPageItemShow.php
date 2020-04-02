<?php
namespace Drupal\bfss_assessors\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Ajax\AjaxResponse;
use Drupal\Core\Ajax\HtmlCommand;
class ParPageItemShow extends FormBase {
	//from id(unique)
	public function getFormId() {
		return 'par_page_item_show';
	}
	
	  public function buildForm(array $form, FormStateInterface $form_state) {
		$form['#method'] = 'get'; 
	  	$options = [
	  		'2' => '2',
	  		'5' => '5',
	  		'10' => '10',
	  		'20' => '20',
	  		'25' => '25',
	  	];
	  	if(!empty($_GET['par_page_item'])){
	  		$default_value = $_GET['par_page_item'];
	  	}else{
	  		$default_value = 10;
	  	}
		$form['par_page_item'] = [
			  '#type' => 'select',
			  '#options' => $options,
			  '#default_value' => $default_value,
			  '#title' => $this->t('Items/Page'),
			  '#prefix' => '<span id="PageItems_ixp">',
			  '#suffix' => '</span>'
			];
 		 // $form['actions']['#type'] = 'actions';
 		 // $form['actions']['submit'] = [
		 //      '#type' => 'submit',
		 //      '#value' => $this->t('SAVE - ALL FIELDS COMPLETED'),
		 //      '#button_type' => 'primary',    
   		 //];
        return $form;
		
	}
    public function validateForm(array &$form, FormStateInterface $form_state) {
    
    }
	public function submitForm(array &$form, FormStateInterface $form_state) {
	
	}

}

?>