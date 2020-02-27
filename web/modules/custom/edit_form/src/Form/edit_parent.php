<?php
/**
 * @file
 * Contains \Drupal\edit_form\Form\ContributeForm.
 */

namespace Drupal\edit_form\Form;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Component\Utility\UrlHelper;
use Drupal\Core\Database\Database;
use Drupal\file\Entity\File;

/**
 * Contribute form.
 */
class edit_parent extends FormBase {	
  /**
   * {@inheritdoc}
   */
  public function getFormId() {
     return 'edit_parent';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
	$current_user = \Drupal::currentUser()->id();
    $conn = Database::getConnection();
    $query1 = \Drupal::database()->select('user__field_parent_first_name', 'fpfnv');
	$query1->join('parent_mobiles', 'pm', 'fpfnv.delta = pm.parent_id');
		$query1->fields('pm');
		$query1->addField('fpfnv','field_parent_first_name_value');
		$query1->condition('fpfnv.entity_id', $current_user,'=');
		$query1->condition('pm.entity_id', $current_user,'=');
		$results1 = $query1->execute()->fetchAll(); 
		$count = count($results1);
		// echo "<pre>"; print_r($results1);die;
	$query2 = \Drupal::database()->select('user__field_parent_first_name', 'fpfnv');
	$query2->join('user__field_parent_last_name', 'ufpln', 'fpfnv.delta = ufpln.delta');
		$query2->addField('ufpln','field_parent_last_name_value');
		$query2->condition('fpfnv.entity_id', $current_user,'=');
		$query2->condition('ufpln.entity_id', $current_user,'=');
		$results2 = $query2->execute()->fetchAll(); 
	// echo "<pre>"; print_r($results2);die;
	$form['prefix'] = "<div class=athlete_edit_class>";
	$form['suffix'] = "</div>";
    // $query3 = \Drupal::database()->select('user__field_parent_first_name', 'fpfnv');
	// $query3->fields('fpfnv');
	  // $query3->condition('fpfnv.entity_id', $current_user,'=');
	  // $results3 = $query3->execute()->fetchAll();
	  // $count = count($results3);
	  // echo "<pre>"; print_r($results3);die;
	  $form['fname1'] = array(
      '#type' => 'textfield',
      '#default_value' => $results1[0]->field_parent_first_name_value,
	  '#attributes' => array('disabled'=>true),
	  '#prefix'=> '<div class="left_section"><div class="athlete_left"><h3><div class="toggle_icon"><i class="fa fa-minus"></i><i class="fa fa-plus hide"></i></div>PARENT / GUARDIAN</h3><div class=items_div>',
	  '#placeholder' => t('First Name'),
      );
	  $form['lname1'] = array(
      '#type' => 'textfield',
      '#placeholder' => t('Last Name'),
      '#default_value' => $results2[0]->field_parent_last_name_value,
	  '#attributes' => array('disabled'=>true),
      ); 
	  $form['cellphone1'] = array(
      '#type' => 'textfield',
	  '#placeholder' => 'Cell Phone',
      '#default_value' => $results1[0]->mobile1,
      ); 
	  $form['homephone1'] = array(
      '#type' => 'textfield',
	  '#placeholder' => 'Home Phone',
      '#default_value' => $results1[0]->mobile2,
      ); 
	  if($count > 1){
			  $form['fname2'] = array(
			  '#type' => 'textfield',
			  '#default_value' => $results1[1]->field_parent_first_name_value,
			  '#attributes' => array('disabled'=>true),
			  '#prefix'=> '</div></div><div class="athlete_left"><h3><div class="toggle_icon"><i class="fa fa-minus"></i><i class="fa fa-plus hide"></i></div>PARENT / GUARDIAN</h3><div class=items_div>',
			  '#placeholder' => t('First Name'),
			  );
			  $form['lname2'] = array(
			  '#type' => 'textfield',
			  '#placeholder' => t('Last Name'),
			  '#default_value' => $results2[1]->field_parent_last_name_value,
			  '#attributes' => array('disabled'=>true),
			  ); 
			  $form['cellphone2'] = array(
			  '#type' => 'textfield',
			  '#placeholder' => 'Cell Phone',
			  '#default_value' => $results1[1]->mobile1,
			  ); 
			  $form['homephone2'] = array(
			  '#type' => 'textfield',
			  '#placeholder' => 'Home Phone',
			  '#default_value' => $results1[1]->mobile2,
			  ); 
	  }else{
		 $form['fname2'] = array(
			  '#type' => 'textfield',
			  '#default_value' => $results1['field_first_name_value'],
			  '#attributes' => array('style'=>'display:none'),
			  '#prefix'=> '</div></div><div class="athlete_left" style="display:none !important;"><h3><div class="toggle_icon"><i class="fa fa-minus"></i><i class="fa fa-plus hide"></i></div>PARENT / GUARDIAN</h3><div class=items_div>',
			  '#placeholder' => t('First Name'),
			  );
			  $form['lname2'] = array(
			  '#type' => 'textfield',
			  '#placeholder' => t('Last Name'),
			  '#default_value' => $results2['field_last_name_value'],
			  '#attributes' => array('style'=>'display:none'),
			  ); 
			  $form['cellphone2'] = array(
			  '#type' => 'textfield',
			  '#placeholder' => 'Cell Phone',
			  '#default_value' => $results5['field_mobile_value'],
			  '#attributes' => array('style'=>'display:none'),
			  ); 
			  $form['homephone2'] = array(
			  '#type' => 'textfield',
			  '#placeholder' => 'Home Phone',
			  '#default_value' => $results6['field_mobile_2_value'],
			  '#attributes' => array('style'=>'display:none'),
			  ); 
			  
	  }
	  if($count >2){
			  $form['fname3'] = array(
			  '#type' => 'textfield',
			  '#default_value' => $results1[2]->field_parent_first_name_value,
			  '#attributes' => array('disabled'=>true),
			  '#prefix'=> '</div></div><div class="athlete_left"><h3><div class="toggle_icon"><i class="fa fa-minus"></i><i class="fa fa-plus hide"></i></div>PARENT / GUARDIAN</h3><div class=items_div>',
			  '#placeholder' => t('First Name'),
			  );
			  $form['lname3'] = array(
			  '#type' => 'textfield',
			  '#placeholder' => t('Last Name'),
			  '#default_value' => $results2[2]->field_parent_last_name_value,
			  '#attributes' => array('disabled'=>true),
			  ); 
			  $form['cellphone3'] = array(
			  '#type' => 'textfield',
			  '#placeholder' => 'Cell Phone',
			  '#default_value' => $results1[2]->mobile1,
			  ); 
			  $form['homephone3'] = array(
			  '#type' => 'textfield',
			  '#placeholder' => 'Home Phone',
			  '#default_value' => $results1[2]->mobile2,
			  ); 
	  }else{
		  $form['fname3'] = array(
			  '#type' => 'textfield',
			  '#default_value' => $results1['field_first_name_value'],
			  '#attributes' => array('style'=>'display:none'),
			  '#prefix'=> '</div></div><div class="athlete_left" style="display:none !important;"><h3><div class="toggle_icon"><i class="fa fa-minus"></i><i class="fa fa-plus hide"></i></div>PARENT / GUARDIAN</h3><div class=items_div>',
			  '#placeholder' => t('First Name'),
			  );
			  $form['lname3'] = array(
			  '#type' => 'textfield',
			  '#placeholder' => t('Last Name'),
			  '#default_value' => $results2['field_last_name_value'],
			  '#attributes' => array('style'=>'display:none'),
			  ); 
			  $form['cellphone3'] = array(
			  '#type' => 'textfield',
			  '#placeholder' => 'Cell Phone',
			  '#default_value' => $results5['field_mobile_value'],
			  '#attributes' => array('style'=>'display:none'),
			  ); 
			  $form['homephone3'] = array(
			  '#type' => 'textfield',
			  '#placeholder' => 'Home Phone',
			  '#default_value' => $results6['field_mobile_2_value'],
			  '#attributes' => array('style'=>'display:none'),
			  ); 
			  
	  }
	  if($count >3){
			  $form['fname4'] = array(
			  '#type' => 'textfield',
			  '#default_value' => $results1[3]->field_parent_first_name_value,
			  '#attributes' => array('disabled'=>true),
			  '#prefix'=> '</div></div><div class="athlete_left"><h3><div class="toggle_icon"><i class="fa fa-minus"></i><i class="fa fa-plus hide"></i></div>PARENT / GUARDIAN</h3><div class=items_div>',
			  '#placeholder' => t('First Name'),
			  );
			  $form['lname4'] = array(
			  '#type' => 'textfield',
			  '#placeholder' => t('Last Name'),
			  '#default_value' => $results2[3]->field_parent_last_name_value,
			  '#attributes' => array('disabled'=>true),
			  ); 
			  $form['cellphone4'] = array(
			  '#type' => 'textfield',
			  '#placeholder' => 'Cell Phone',
			  '#default_value' => $results1[3]->mobile1,
			  ); 
			  $form['homephone4'] = array(
			  '#type' => 'textfield',
			  '#placeholder' => 'Home Phone',
			  '#default_value' => $results1[3]->mobile2,
			  );
	  }else{
		 $form['fname4'] = array(
			  '#type' => 'textfield',
			  '#default_value' => $results1['field_first_name_value'],
			  '#attributes' => array('style'=>'display:none'),
			  '#prefix'=> '</div></div><div class="athlete_left" style="display:none !important;"><h3><div class="toggle_icon"><i class="fa fa-minus"></i><i class="fa fa-plus hide"></i></div>PARENT / GUARDIAN</h3><div class=items_div>',
			  '#placeholder' => t('First Name'),
			  );
			  $form['lname4'] = array(
			  '#type' => 'textfield',
			  '#placeholder' => t('Last Name'),
			  '#default_value' => $results2['field_last_name_value'],
			  '#attributes' => array('style'=>'display:none'),
			  ); 
			  $form['cellphone4'] = array(
			  '#type' => 'textfield',
			  '#placeholder' => 'Cell Phone',
			  '#default_value' => $results5['field_mobile_value'],
			  '#attributes' => array('style'=>'display:none'),
			  ); 
			  $form['homephone4'] = array(
			  '#type' => 'textfield',
			  '#placeholder' => 'Home Phone',
			  '#default_value' => $results6['field_mobile_2_value'],
			  '#attributes' => array('style'=>'display:none'),
			  );
			  
	  }
		
    $form['submit'] = [
        '#type' => 'submit',
        '#value' => 'save',
		'#prefix' =>'	</div></div><div id="athlete_submit">',
		'#suffix' => '</div>',
        //'#value' => t('Submit'),
    ];
	
    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    // Validate video URL.
    // if (!UrlHelper::isValid($form_state->getValue('video'), TRUE)) {
      // $form_state->setErrorByName('video', $this->t("The video url '%url' is invalid.", array('%url' => $form_state->getValue('video'))));
    // }
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    
	 //echo '<pre>';print_r($form_state->getValues()['jodi']);die;
      
	$current_user = \Drupal::currentUser()->id();
    $conn = Database::getConnection();
	
	$query1 = \Drupal::database()->select('user__field_parent_first_name', 'fpfnv');
	$query1->join('parent_mobiles', 'pm', 'fpfnv.delta = pm.parent_id');
		$query1->fields('pm');
		$query1->addField('fpfnv','field_parent_first_name_value');
		$query1->condition('fpfnv.entity_id', $current_user,'=');
		$query1->condition('pm.entity_id', $current_user,'=');
		$results1 = $query1->execute()->fetchAll(); 
		// echo "<pre>"; print_r($results1);die;
	$query2 = \Drupal::database()->select('user__field_parent_first_name', 'fpfnv');
	$query2->join('user__field_parent_last_name', 'ufpln', 'fpfnv.delta = ufpln.delta');
		$query2->addField('ufpln','field_parent_last_name_value');
		$query2->condition('fpfnv.entity_id', $current_user,'=');
		$query2->condition('ufpln.entity_id', $current_user,'=');
		$results2 = $query2->execute()->fetchAll(); 
	$query3 = \Drupal::database()->select('user__field_parent_first_name', 'fpfnv');
		$query3->addExpression("max(delta)");
		$results3 = $query3->execute()->fetchAll(); 
	$query4 = \Drupal::database()->select('user__field_parent_first_name', 'fpfnv');
	$query4->join('user__field_parent_last_name', 'ufpln', 'fpfnv.delta = ufpln.delta');
		$query4->addField('fpfnv','field_parent_first_name_value');
		$query4->addField('ufpln','field_parent_last_name_value');
		$query4->condition('fpfnv.field_parent_first_name_value', $fname1,'=');
		$query4->condition('ufpln.field_parent_last_name_value', $lname1,'=');
		$results4 = $query4->execute()->fetchAll();
	$query5 = \Drupal::database()->select('user__field_parent_first_name', 'fpfnv');
	$query5->join('user__field_parent_last_name', 'ufpln', 'fpfnv.delta = ufpln.delta');
		$query5->addField('fpfnv','field_parent_first_name_value');
		$query5->addField('ufpln','field_parent_last_name_value');
		$query5->condition('fpfnv.field_parent_first_name_value', $fname2,'=');
		$query5->condition('ufpln.field_parent_last_name_value', $lname2,'=');
		$results5 = $query5->execute()->fetchAll();
	$query6 = \Drupal::database()->select('user__field_parent_first_name', 'fpfnv');
	$query6->join('user__field_parent_last_name', 'ufpln', 'fpfnv.delta = ufpln.delta');
		$query6->addField('fpfnv','field_parent_first_name_value');
		$query6->addField('ufpln','field_parent_last_name_value');
		$query6->condition('fpfnv.field_parent_first_name_value', $fname3,'=');
		$query6->condition('ufpln.field_parent_last_name_value', $lname3,'=');
		$results6 = $query6->execute()->fetchAll();
	$query7 = \Drupal::database()->select('user__field_parent_first_name', 'fpfnv');
	$query7->join('user__field_parent_last_name', 'ufpln', 'fpfnv.delta = ufpln.delta');
		$query7->addField('fpfnv','field_parent_first_name_value');
		$query7->addField('ufpln','field_parent_last_name_value');
		$query7->condition('fpfnv.field_parent_first_name_value', $fname4,'=');
		$query7->condition('ufpln.field_parent_last_name_value', $lname4,'=');
		$results7 = $query7->execute()->fetchAll(); 
	
		$fname1 = $form_state->getValue('fname1');
		$lname1 = $form_state->getValue('lname1');
		$cellphone1 = $form_state->getValue('cellphone1');
		$homephone1 = $form_state->getValue('homephone1');
		$fname2 = $form_state->getValue('fname2');
		$lname2 = $form_state->getValue('lname2');
		$cellphone2 = $form_state->getValue('cellphone2');
		$homephone2 = $form_state->getValue('homephone2');
		$fname3 = $form_state->getValue('fname3');
		$lname3 = $form_state->getValue('lname3');
		$cellphone3 = $form_state->getValue('cellphone3');
		$homephone3 = $form_state->getValue('homephone3');
		$fname4 = $form_state->getValue('fname4');
		$lname4 = $form_state->getValue('lname4');
		$cellphone4 = $form_state->getValue('cellphone4');
		$homephone4 = $form_state->getValue('homephone4');
		
		if(!empty($results4)){
			$conn->insert('user__field_parent_first_name')->fields(
							array(
							'entity_id' => $current_user,
							'bundle' => 'user',
							'deleted' => '0',
							'revision_id' => $current_user,
							'langcode' => 'en',
							'delta' => $results3[0]->expression,
							'field_parent_first_name_value' => $fname1,
							)
					)->execute();
			$conn->insert('user__field_parent_last_name')->fields(
							array(
							'entity_id' => $current_user,
							'bundle' => 'user',
							'deleted' => '0',
							'revision_id' => $current_user,
							'langcode' => 'en',
							'delta' => $results3[0]->expression,
							'field_parent_last_name_value' => $lname1,
							)
					)->execute();
			$conn->insert('parent_mobiles')->fields(
							array(
							'entity_id' => $current_user,
							'deleted' => '0',
							'parent_id' => $results3[0]->expression,
							'mobile1' => $cellphone1,
							'mobile2' => $homephone1,
							)
					)->execute();
		}else{
			$conn->update('parent_mobiles')
						->condition('entity_id',$current_user,'=')
						->fields(
							array(
							'parent_id' => $results3[0]->expression,
							'mobile1' => $cellphone1,
							'mobile2' => $homephone1,
							)
						)
						->execute();
			$conn->update('user__field_parent_first_name')
						->condition('entity_id',$current_user,'=')
						->fields(
							array(
							'bundle' => 'user',
							'deleted' => '0',
							'langcode' => 'en',
							'delta' => $results3[0]->expression,
							'field_parent_first_name_value' => $fname1,
							)
						)
						->execute();
						
			$conn->update('user__field_parent_last_name')
						->condition('entity_id',$current_user,'=')
						->fields(
							array(
							'bundle' => 'user',
							'deleted' => '0',
							'langcode' => 'en',
							'delta' => $results3[0]->expression,
							'field_parent_last_name_value' => $lname1,
							)
						)
						->execute();
		}
		
		if(!empty($results5)){
			$conn->insert('user__field_parent_first_name')->fields(
							array(
							'entity_id' => $current_user,
							'bundle' => 'user',
							'deleted' => '0',
							'revision_id' => $current_user,
							'langcode' => 'en',
							'delta' => $results3[0]->expression,
							'field_parent_first_name_value' => $fname2,
							)
					)->execute();
			$conn->insert('user__field_parent_last_name')->fields(
							array(
							'entity_id' => $current_user,
							'bundle' => 'user',
							'deleted' => '0',
							'revision_id' => $current_user,
							'langcode' => 'en',
							'delta' => $results3[0]->expression,
							'field_parent_last_name_value' => $lname2,
							)
					)->execute();
			$conn->insert('parent_mobiles')->fields(
							array(
							'entity_id' => $current_user,
							'deleted' => '0',
							'parent_id' => $results3[0]->expression,
							'mobile1' => $cellphone2,
							'mobile2' => $homephone2,
							)
					)->execute();
		}else{
			$conn->update('parent_mobiles')
						->condition('entity_id',$current_user,'=')
						->fields(
							array(
							'parent_id' => $results3[0]->expression,
							'mobile1' => $cellphone2,
							'mobile2' => $homephone2,
							)
						)
						->execute();
			$conn->update('user__field_parent_first_name')
						->condition('entity_id',$current_user,'=')
						->fields(
							array(
							'bundle' => 'user',
							'deleted' => '0',
							'langcode' => 'en',
							'delta' => $results3[0]->expression,
							'field_parent_first_name_value' => $fname2,
							)
						)
						->execute();
						
			$conn->update('user__field_parent_last_name')
						->condition('entity_id',$current_user,'=')
						->fields(
							array(
							'bundle' => 'user',
							'deleted' => '0',
							'langcode' => 'en',
							'delta' => $results3[0]->expression,
							'field_parent_last_name_value' => $lname2,
							)
						)
						->execute();
		}
		
		if(!empty($results6)){
			$conn->insert('user__field_parent_first_name')->fields(
							array(
							'entity_id' => $current_user,
							'bundle' => 'user',
							'deleted' => '0',
							'revision_id' => $current_user,
							'langcode' => 'en',
							'delta' => $results3[0]->expression,
							'field_parent_first_name_value' => $fname3,
							)
					)->execute();
			$conn->insert('user__field_parent_last_name')->fields(
							array(
							'entity_id' => $current_user,
							'bundle' => 'user',
							'deleted' => '0',
							'revision_id' => $current_user,
							'langcode' => 'en',
							'delta' => $results3[0]->expression,
							'field_parent_last_name_value' => $lname3,
							)
					)->execute();
			$conn->insert('parent_mobiles')->fields(
							array(
							'entity_id' => $current_user,
							'deleted' => '0',
							'parent_id' => $results3[0]->expression,
							'mobile1' => $cellphone3,
							'mobile2' => $homephone3,
							)
					)->execute();
		}else{
			$conn->update('parent_mobiles')
						->condition('entity_id',$current_user,'=')
						->fields(
							array(
							'parent_id' => $results3[0]->expression,
							'mobile1' => $cellphone3,
							'mobile2' => $homephone3,
							)
						)
						->execute();
			$conn->update('user__field_parent_first_name')
						->condition('entity_id',$current_user,'=')
						->fields(
							array(
							'bundle' => 'user',
							'deleted' => '0',
							'langcode' => 'en',
							'delta' => $results3[0]->expression,
							'field_parent_first_name_value' => $fname3,
							)
						)
						->execute();
						
			$conn->update('user__field_parent_last_name')
						->condition('entity_id',$current_user,'=')
						->fields(
							array(
							'bundle' => 'user',
							'deleted' => '0',
							'langcode' => 'en',
							'delta' => $results3[0]->expression,
							'field_parent_last_name_value' => $lname3,
							)
						)
						->execute();
		}
		
		if(!empty($results7)){
			$conn->insert('user__field_parent_first_name')->fields(
							array(
							'entity_id' => $current_user,
							'bundle' => 'user',
							'deleted' => '0',
							'revision_id' => $current_user,
							'langcode' => 'en',
							'delta' => $results3[0]->expression,
							'field_parent_first_name_value' => $fname4,
							)
					)->execute();
			$conn->insert('user__field_parent_last_name')->fields(
							array(
							'entity_id' => $current_user,
							'bundle' => 'user',
							'deleted' => '0',
							'revision_id' => $current_user,
							'langcode' => 'en',
							'delta' => $results3[0]->expression,
							'field_parent_last_name_value' => $lname4,
							)
					)->execute();
			$conn->insert('parent_mobiles')->fields(
							array(
							'entity_id' => $current_user,
							'deleted' => '0',
							'parent_id' => $results3[0]->expression,
							'mobile1' => $cellphone4,
							'mobile2' => $homephone4,
							)
					)->execute();
		}else{
			$conn->update('parent_mobiles')
						->condition('entity_id',$current_user,'=')
						->fields(
							array(
							'parent_id' => $results3[0]->expression,
							'mobile1' => $cellphone4,
							'mobile2' => $homephone4,
							)
						)
						->execute();
			$conn->update('user__field_parent_first_name')
						->condition('entity_id',$current_user,'=')
						->fields(
							array(
							'bundle' => 'user',
							'deleted' => '0',
							'langcode' => 'en',
							'delta' => $results3[0]->expression,
							'field_parent_first_name_value' => $fname4,
							)
						)
						->execute();
						
			$conn->update('user__field_parent_last_name')
						->condition('entity_id',$current_user,'=')
						->fields(
							array(
							'bundle' => 'user',
							'deleted' => '0',
							'langcode' => 'en',
							'delta' => $results3[0]->expression,
							'field_parent_last_name_value' => $lname4,
							)
						)
						->execute();
		}
  }
}
?>