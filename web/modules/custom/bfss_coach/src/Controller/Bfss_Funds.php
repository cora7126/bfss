<?php

namespace Drupal\bfss_coach\Controller;

use Drupal\Core\Controller\ControllerBase;
use \Drupal\node\Entity\Node;
use  \Drupal\user\Entity\User;
use Drupal\Core\Render\Markup;
use Drupal\Core\Database\Database;

class Bfss_Funds extends ControllerBase {
	  public function content() {
	   
	  	$booked_ids = \Drupal::entityQuery('bfsspayments')
			->condition('payment_status','pending', '=')
        	->execute();
        $TableRowsPending = [];
    	foreach ($booked_ids as $booked_id) {
    		$entity = \Drupal\bfss_assessment\Entity\BfssPayments::load($booked_id);
    		$user_id = $entity->user_id->value;
    		//print_r($user_id);
    		$timestamp = $entity->time->value;
    		
    		$booking_date = date("Y/m/d",$timestamp);
    		$firstname = $entity->first_name->value;
    		
    		if(!empty($firstname)){
				$firstname = '<a href="/preview/profile?uid='.$user_id.'">'.$firstname.'</a>';	
			}
			$lastname = $entity->last_name->value;
			if(!empty($lastname)){
				$lastname = '<a href="/preview/profile?uid='.$user_id.'">'.$lastname.'</a>';	
			}
    		

    		$price = $entity->service->value;
    		$payment_status = $entity->payment_status->value;
    		

    		$query5 = \Drupal::database()->select('athlete_school', 'ats');
            $query5->fields('ats');
            $query5->condition('athlete_uid', $user_id,'=');
            $results5 = $query5->execute()->fetchAssoc();            
            $sport = $results5['athlete_school_sport'];

    		$TableRowsPending[] = [
		    		'booking_date'	=> $booking_date,
		    		'firstname'	=> $firstname,
		    		'lastname'	=> $lastname,
		    		'sport'	=> $sport,
		    		'price'	=> $price,
		    		'payment_status'=> $payment_status,
    		];
    		
    	}

	  	$tb1 = '<div class="search_athlete_main user_pro_block">
          <div class="wrapped_div_main">
           <h2>BFSS Payments Pending</h2>
          <div class="block-bfss-assessors">
          <div class="table-responsive">
         <table id="bfss_payment_pending_pxl" class="table table-hover table-striped" cellspacing="0" width="100%" >
            <thead>
              <tr>
               	<th class="th-hd"><a><span></span>Date</a>
                </th>  
                <th class="th-hd"><a><span></span>First Name</a>
                </th>
                <th class="th-hd"><a><span></span>Last Name</a>
                </th> 
                <th class="th-hd"><a><span></span>Sport</a>
                </th>  
                <th class="th-hd"><a><span></span>Amount</a>
                </th> 
                 <th class="th-hd"><a><span></span>Paid</a>
                </th> 
              </tr>
            </thead>
            <tbody>';
        foreach ($TableRowsPending as $RowValue) {
        	$tb1 .= '<tr>
                <td>'.$RowValue['booking_date'].'</td>
                <td>'.$RowValue['firstname'].'</td>
                <td>'.$RowValue['lastname'].'</td>
                <td>'.$RowValue['sport'].'</td>
                <td>$'.$RowValue['price'].'</td>
                <td>'.$RowValue['payment_status'].'</td>
        	</tr>';
        }
	      $tb1 .= '</tbody>
	          </table>
	           </div>
	          </div>
	           </div>
	          </div>';
	



	    $booked_ids_paid = \Drupal::entityQuery('bfsspayments')
			->condition('payment_status','paid', '=')
        	->execute();
        $TableRowsPaid = [];
    	foreach ($booked_ids_paid as $booked_id) {
    		$entity = \Drupal\bfss_assessment\Entity\BfssPayments::load($booked_id);
    		$timestamp = $entity->time->value;
    		$booking_date = date("Y/m/d",$timestamp);
    		$firstname = $entity->first_name->value;
    		$lastname = $entity->last_name->value;
    		$user_id = $entity->user_id->value;
    		if(!empty($firstname)){
				$firstname = '<a href="/preview/profile?uid='.$user_id.'">'.$firstname.'</a>';	
			}
			if(!empty($lastname)){
				$lastname = '<a href="/preview/profile?uid='.$user_id.'">'.$lastname.'</a>';	
			}
    		$price = $entity->service->value;
    		$payment_status = $entity->payment_status->value;
    		$created = date("Y/m/d",$entity->created->value);
    		

    		$query5 = \Drupal::database()->select('athlete_school', 'ats');
            $query5->fields('ats');
            $query5->condition('athlete_uid', $user_id,'=');
            $results5 = $query5->execute()->fetchAssoc();            
            $sport = $results5['athlete_school_sport'];

    		$TableRowsPaid[] = [
		    		'booking_date'	=> $booking_date,
		    		'firstname'	=> $firstname,
		    		'lastname'	=> $lastname,
		    		'sport' => $sport,
		    		'price'	=> $price,
		    		'payment_status'=> $created,
    		];
    		
    	}
	    $tb2 = '<div class="search_athlete_main user_pro_block">
          <div class="wrapped_div_main">
           <h2>BFSS Payments Paid</h2>
          <div class="block-bfss-assessors">
          <div class="table-responsive">
         <table id="bfss_payment_paid_pxl" class="table table-hover table-striped" cellspacing="0" width="100%" >
            <thead>
 			 <tr>
               	<th class="th-hd"><a><span></span>Date</a>
                </th>  
                <th class="th-hd"><a><span></span>First Name</a>
                </th>
                <th class="th-hd"><a><span></span>Last Name</a>
                </th>  
                <th class="th-hd"><a><span></span>Sport</a>
                </th>
                <th class="th-hd"><a><span></span>Amount</a>
                </th> 
                 <th class="th-hd"><a><span></span>Paid</a>
                </th> 
            </tr>
            </thead>
            <tbody>';
      foreach ($TableRowsPaid as $RowValue) {
        	$tb2 .= '<tr>
                <td>'.$RowValue['booking_date'].'</td>
                <td>'.$RowValue['firstname'].'</td>
                <td>'.$RowValue['lastname'].'</td>
                <td>'.$RowValue['sport'].'</td>
                <td>$'.$RowValue['price'].'</td>
                <td>'.$RowValue['payment_status'].'</td>
        	</tr>';
        }
	    $tb2 .= '</tbody>
			      </table>
			       </div>
			      </div>
			       </div>
			      </div>';    
	    return [
	      '#cache' => ['max-age' => 0,],
	      '#theme' => 'bfss_funds_page',
	      '#bfss_payment_pending_block' => Markup::create($tb1),
	      '#bfss_payment_paid_block' => Markup::create($tb2),
	      '#attached' => [
	        'library' => [
	          'acme/acme-styles', //include our custom library for this response
	        ]
	      ]
	    ];
  	}
}