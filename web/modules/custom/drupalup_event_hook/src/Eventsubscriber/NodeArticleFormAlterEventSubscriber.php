<?php

namespace Drupal\drupalup_event_hook\Eventsubscriber;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Drupal\hook_event_dispatcher\HookEventDispatcherInterface;

class NodeArticleFormAlterEventSubscriber implements EventSubscriberInterface {
	
	public static function getSubscribedEvents(){
		//echo 'herer';die;
		
		return [
		HookEventDispatcherInterface::FORM_ALTER => 'hookFormAlter'
		];
	}
	
	public function hookFormAlter($event){
		// echo $event->getFormId(); die;
		 if($event->getFormId() == 'user_form'){
			 $form = $event->getForm();
			// $form['special_title'] = [
			// '#type' => 'markup',
			// '#markup' => '<div> test title</div>'
			// ];
			
			 $form['custom_content_block_image'] = array(
				'#type' => 'managed_file',
				'#name' => 'custom_content_block_image',
				'#title' => t('Block image'),
				'#size' => 40,
				'#description' => t("Image should be less than 400 pixels wide and in JPG format."),
				'#upload_location' => 'public://'
			  ); 
			  // echo '<pre>';print_r($form['custom_content_block_image']);die;
			$event->setForm($form);
		} 
	}
	
	function drupalup_event_hook_submit($form, &$form_state) {
	  if (isset($form_state['values']['custom_content_block_image'])) {
		$file = file_load($form_state['values']['custom_content_block_image']);

		$file->status = FILE_STATUS_PERMANENT;

		$file_saved =file_save($file);
		// Record that the module is using the file. 
		file_usage_add($file_saved, 'hookFormAlter', 'custom_content_block_image', $file_saved->fid); 
    }
}
	
	
	
}