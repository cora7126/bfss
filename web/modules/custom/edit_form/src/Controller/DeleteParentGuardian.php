<?php

namespace Drupal\edit_form\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Component\Utility\UrlHelper;
use Drupal\Core\Database\Database;
use Symfony\Component\HttpFoundation\JsonResponse;
Use Drupal\paragraphs\Entity\Paragraph;

class DeleteParentGuardian extends ControllerBase {
	public function delete_parent_guardian($tid)
	 {		
		if(isset($tid)){
			$paragraph = Paragraph::load($tid);
			$paragraph->delete();	
		}	
		$response = array($tid);
		return new JsonResponse($response);
	 }
}
