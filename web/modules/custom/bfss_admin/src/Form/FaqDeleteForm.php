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
 * Class FaqDeleteForm.
 */
class FaqDeleteForm extends FormBase {
  public function getFormId() {
    return 'faq_delete_form';
  }

  public function buildForm(array $form, FormStateInterface $form_state) {
  	    $param = \Drupal::request()->query->all();
  	    $form['message_delete'] = [ //for custom message "like: ajax msgs"
	        '#type' => 'markup',
	        '#markup' => '<div class="result_message_delete"></div>',
      	];
  	    $form['faq_nid'] =[
	        '#type' => 'hidden',
	        '#value' => $param['nid'],
      	];

      	$form['faqs_role'] =[
        '#type' => 'hidden',
        '#value' => $param['role'],
      	];
      	$form['html_left'] = [
	        '#type' => 'markup',
	        '#markup' => '<div class="left_section popup_left_section">
							 <div class="athlete_left"> 
								<h3><div class="toggle_icon"><i class="fa fa-minus"></i><i class="fa fa-plus hide"></i></div>DELETE FAQ?</h3>
							                    <div class="items_div">
							                    <p>Are you sure you want to delete this FAQ permanently?</p>',
      	];
  	    $form['actions']['#type'] = 'actions';
      	$form['actions']['submit'] = [
          '#type' => 'submit',
          '#value' => $this->t('Yes Delete'),
          '#prefix' => '<div  class="athlete_submit">',
          '#suffix' => '</div> </div>
					</div>
				</div>',
          '#button_type' => 'primary',
           '#ajax' => [
              'callback' => '::myAjaxCallback', // don't forget :: when calling a class method.
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

  public function myAjaxCallback(array &$form, FormStateInterface $form_state) {
   	$param = \Drupal::request()->query->all();
   	$conn = Database::getConnection();
   	$query = $this->Get_Data_From_Tables('bfss_faqs_nids','at',$form_state->getValue('faqs_role'));
   	$nids_arr = explode(",",$query['faq_nids']);
   	if(isset($param['nid'])){
        $node = Node::load($param['nid']);
    	$node->delete();
    	$message = "Deleted!"; 

    	if(in_array($param['nid'], $nids_arr))
    	{
    		$key = array_search($param['nid'], $nids_arr);
			unset($nids_arr[$key]);
			$nids_str = implode(",",$nids_arr);
			$conn->update('bfss_faqs_nids')->condition('role', $form_state->getValue('faqs_role'), '=')->fields(
              [
                'faq_nids' => $nids_str,
              ]
            )->execute(); 
    	}

    }

   	$response = new AjaxResponse();
    $response->addCommand(
        new HtmlCommand(
          '.result_message_delete',
          '<div class="success_message_delete">'.$message.'</div>'
        )
    );
    return $response;
  }

  	public function Get_Data_From_Tables($TableName,$atr,$user_role){
  		if($TableName){
  			$conn = Database::getConnection();
			$query = $conn->select($TableName, $atr);
		    $query->fields($atr);
		    $query->condition('role', $user_role, '=');
		    $results = $query->execute()->fetchAssoc();
  		}
  		return $results;
	}

}