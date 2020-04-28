<?php

namespace Drupal\bfss_admin\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Render\Markup;

class AddFaqs extends ControllerBase {
	  public function add_faqs() {

	  	$form = \Drupal::formBuilder()->getForm('Drupal\bfss_admin\Form\AddFaqsForm');
	  	$HTML = '<ul class="faq faqct">
						 <li class="q">
						  	<div class="faq-left"><p>Section 1.10.32 of "de Finibus Bonorum et Malorum", written by Cicero in 45 BC</p></div><div class="faq-right faq faqct"><img class="arrowimg" src="/modules/custom/bfss_assessment/img/o-arrow.png"></div>
						 </li>
						<li class="a"><p>Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium </p>
						</li>

						<li class="q">
						  	<div class="faq-left"><p>Section 1.10.32 of "de Finibus Bonorum et Malorum", written by Cicero in 45 BC</p></div><div class="faq-right faq faqct"><img class="arrowimg" src="/modules/custom/bfss_assessment/img/o-arrow.png"></div>
						 </li>
						<li class="a"><p>Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium </p>
						</li>
					</ul>';
	  	$out =  Markup::create($HTML);
	    return [
		    '#cache' => ['max-age' => 0,],
		    '#theme' => 'add_faqs_page',
		    '#add_faqs_block' => $form,
		    '#reorder_faqs_block' => $out,
		    '#attached' => [
		      'library' => [
		        'acme/acme-styles', //include our custom library for this response
      			]
    		]
  		]; 
  	}
}