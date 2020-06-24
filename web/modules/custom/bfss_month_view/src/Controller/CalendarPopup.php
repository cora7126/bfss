<?php

namespace Drupal\bfss_month_view\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Component\Utility\UrlHelper;
use Drupal\Core\Database\Database;
use Symfony\Component\HttpFoundation\JsonResponse;
use Drupal\Core\Render\Markup;

use Drupal\bfss_assessment\AssessmentService;
use Drupal\node\Entity\Node;
Use Drupal\node\NodeInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Drupal\Core\Config\ConfigFactoryInterface;
use \Drupal\user\Entity\User;
Use Drupal\paragraphs\Entity\Paragraph;
class CalendarPopup extends ControllerBase {

		public function calendar_modal_show($nid)
		  {	
			global $base_url;
		    $current_path = \Drupal::service('path.current')->getPath();

		    $data['current_page'] = $base_url;
		    $uid = \Drupal::currentUser()->id();
		    $user = User::load($uid);
		    $roles = $user->getRoles();
		    $param = \Drupal::request()->query->all();
		    //$nid = \Drupal::request()->get('node_id');
		    //print_r($nid);
		    $data = [];
		 
		    $node = Node::load($nid);
		    $assessment_type = $node->field_type_of_assessment->value;
		    $data['title'] = $node->getTitle();
		     if ($node->hasField('body')) {
		      $data['body'] = t($node->get('body')->value);
		    }
		     if ($node->hasField('field_location')) {
		          $data['field_location'] = t($node->get('field_location')->value);
		        }

		    if ($node->hasField('field_image')) {
		      $imageurl = $node->get('field_image')->entity->uri->value;
		      if(isset($imageurl)){
		        $data['field_image'] = file_create_url($imageurl);
		      }
		    }

		    if ($node->hasField('field_schedules')) {
		      $field_schedules = $node->get('field_schedules')->getValue();
		      $latest_timing = null;
		      $latest_duration = null;
		      if ($field_schedules) {
		        foreach ( $field_schedules as $element ) {
		          if (isset($element['target_id'])) {
		            $pGraph = Paragraph::load($element['target_id'] );
		            if ($pGraph->hasField('field_timing') && $pGraph->hasField('field_duration')) {
		              $timing = (int) $pGraph->get('field_timing')->value;
		              $duration = $pGraph->get('field_duration')->value;
		              if ($duration) {
		                $duration = date('h:i A',strtotime('+'.$duration.' minutes',$timing));
		              }
		              if (empty($latest_timing)) {
		                $latest_timing = $timing;
		                $latest_duration = $duration;
		              }else{
		                if ($latest_timing > $timing) {
		                  $latest_timing = $timing;
		                  $latest_duration = $duration;
		                }
		              }

		              if ($timing > time()) {
		                $data['schedules'][] = [
		                  'field_timing' => $timing,
		                  'field_duration' => $duration,
		                ];
		              }
		            }
		          }
		        }
		        #get the latest upcoming schedule
		        $data['latest_timing'] = $latest_timing;
		        $data['latest_duration'] = $latest_duration;
		      }
		    }

		    $html = '<div class="modal fade" id="myModal" role="dialog">
		            <div class="modal-dialog assessments-popup-md">
		            
		              <!-- Modal content-->
		              <div class="modal-content">
		              <div class="modal-header ui-dialog-titlebar ui-draggable-handle" id="drupal-modal--header">
		              <button class="close ui-dialog-titlebar-close" aria-label="Close" data-dismiss="modal" type="button"><span aria-hidden="true">×</span></button>
		              </div>
		                    <div class="calender_event_pop_up">
		                      <div class="container">
		                        <div class="tophead">
		                          <div class="image">
		                            <img src="'.$data['field_image'].'" alt="'.$data['title'].'" />
		                          </div>
		                          <div class="title-timing">
		                            <div>
		                                <span class="timing-date">
		                              '.date("M",$data['latest_timing']).'</span>
		                                <span class="timing-info">'.date("d",$data['latest_timing']).'</span>
		                            </div>
		                            <div>
		                              <h2>
		                               '.$data['title'].' 
		                              </h2>
		                              <span class="loca">'.$data['field_location'].'</span>
		                            </div>
		                          </div>
		                        </div>';
		        if(!in_array('bfss_administrator', $roles)){                
		        $html .=  '<div class="ticketing">
		                          <div class="share">
		                          <span><a class="share_assessment"><i class="fas fa-share"></i>  Share</a></span>
		                              <div class="social_share_links" style="display:none;">
		                            <ul>
		                            <li><a target="_blank" rel="nooopener noreffer" class="facebook-share share" href="http://www.facebook.com/share.php?u="'.$data['current_page'].'"&amp;title='.$data['title'].'" title="Facebook">
		                                                  <img alt="Facebook" src="/modules/contrib/social_media/icons/facebook_share.svg"> </a>
		                                             
		                            </li>
		                             <li>
		                                    <a target="_blank" rel="nooopener noreffer" class="linkedin share" href="http://www.linkedin.com/shareArticle?mini=true&amp;url={{data.current_page}}&amp;title='.$data['title'].'&amp;source='.$data['current_page'].'" title="Linkedin">
		                                            <img alt="Linkedin" src="/modules/contrib/social_media/icons/linkedin.svg">
		                                          </a>
		                            </li>
		                            <li>
                                        <a target="_blank" rel="nooopener noreffer" class="twitter share" href="https://twitter.com/intent/tweet?url='.$data['current_page'].'&amp;url="'.$data['current_page'].'"&amp;hashtags='.$data['title'].'" title="Twitter">
                                              <img alt="Twitter" src="/modules/contrib/social_media/icons/twitter.svg">
                                          </a>
                                    </li>
                                    <li>
                                      <a target="_blank" rel="nooopener noreffer" class="pinterest share" href="https://www.pinterest.com/pin/create/button/?url="'.$data['current_page'].'"&amp;description='.$data['title'].'" title="Pinterest">
                                              <img alt="Pinterest" src="/modules/contrib/social_media/icons/pinterest.svg">
                                            </a>
                                    </li>
                                    <li>
                                    <a class="email share" href="mailto:?subject='.$data['title'].'&amp;body=Check out this site '.$data['current_page'].' title="Email">
                                            <img alt="Email" src="http://5ppsystem.com/modules/contrib/social_media/icons/email.svg">
                                            </a>
                                    </li>

		                            </ul>
		                                   </div>
		                          </div>
		                          <div class="ticket">
		                          <span><i class="fal fa-ticket-alt"></i> TICKETING</span>
		                          </div>
		                     </div>';
		                 }
		                 $html .= '<div class="details">
		                          <div class="body">
		                            
		                            <div class="node-body">
		                            '.$data['body'].'
		                            </div>
		                          </div>
		                          <div class="date-location">
		                            <div class="date-and-time">
		                              <p>DATE AND TIME</p><span class="timing-date">
		                           '.date("l",$data['latest_timing']).'<br/>'.date("M d, Y h:i A",$data['latest_timing']).'
		                                </span>
		                              <p>UNTIL</p>
		                              <span>
		                          '.$data['field_location'].'
		                              </span>
		                            <div>
		                            <div class="location">
		                              <p>LOCATION</p>
		                             '.$data['field_location'].'
		                            <div>';

		                            if(in_array('athlete', $roles)){
		                            $html .= '<div class="ticketing">
		                              <p>TICKETING</p>
		                              <a href="/assessment/type/'.$nid.'" class="book-now">Book Now</a>
		                            <div>';
		                           	}else{
		                           	$html .= '<div class="ticketing">
		                              <p>ASSESSMENT TYPE</p>
		                             <p style="color:#000;">'.ucfirst($assessment_type).'</p>
		                            <div>';
		                           	}
		                        $html .=  '</div>
		                        </div>
		                      </div>
		                      </div>
		                 </div>
		              
		            </div>
		          </div>';
		    //print_r($nid);
		    $response = array('nid' => $nid,'modal' => Markup::create($html));
		    return new JsonResponse($response);
		}

		public function calendar_modal_show_scheduled($nid){
			global $base_url;
		    $current_path = \Drupal::service('path.current')->getPath();

		    $data['current_page'] = $base_url;
		    $uid = \Drupal::currentUser()->id();
		    $user = User::load($uid);
		    $roles = $user->getRoles();
		    $param = \Drupal::request()->query->all();
		    //$nid = \Drupal::request()->get('node_id');
		    //print_r($nid);
		    $data = [];
		 
		    $node = Node::load($nid);
		    $assessment_type = $node->field_type_of_assessment->value;
		    $data['title'] = $node->getTitle();
		     if ($node->hasField('body')) {
		      $data['body'] = t($node->get('body')->value);
		    }
		     if ($node->hasField('field_location')) {
		          $data['field_location'] = t($node->get('field_location')->value);
		        }

		    if ($node->hasField('field_image')) {
		      $imageurl = $node->get('field_image')->entity->uri->value;
		      if(isset($imageurl)){
		        $data['field_image'] = file_create_url($imageurl);
		      }
		    }

		    if ($node->hasField('field_schedules')) {
		      $field_schedules = $node->get('field_schedules')->getValue();
		      $latest_timing = null;
		      $latest_duration = null;
		      if ($field_schedules) {
		        foreach ( $field_schedules as $element ) {
		          if (isset($element['target_id'])) {
		            $pGraph = Paragraph::load($element['target_id'] );
		            if ($pGraph->hasField('field_timing') && $pGraph->hasField('field_duration')) {
		              $timing = (int) $pGraph->get('field_timing')->value;
		              $duration = $pGraph->get('field_duration')->value;
		              if ($duration) {
		                $duration = date('h:i A',strtotime('+'.$duration.' minutes',$timing));
		              }
		              if (empty($latest_timing)) {
		                $latest_timing = $timing;
		                $latest_duration = $duration;
		              }else{
		                if ($latest_timing > $timing) {
		                  $latest_timing = $timing;
		                  $latest_duration = $duration;
		                }
		              }

		              if ($timing > time()) {
		                $data['schedules'][] = [
		                  'field_timing' => $timing,
		                  'field_duration' => $duration,
		                ];
		              }
		            }
		          }
		        }
		        #get the latest upcoming schedule
		        $data['latest_timing'] = $latest_timing;
		        $data['latest_duration'] = $latest_duration;
		      }
		    }

		    $html = '<div class="modal fade" id="myModal" role="dialog">
		            <div class="modal-dialog assessments-popup-md">
		            
		              <!-- Modal content-->
		              <div class="modal-content">
		              <div class="modal-header ui-dialog-titlebar ui-draggable-handle" id="drupal-modal--header">
		              <button class="close ui-dialog-titlebar-close" aria-label="Close" data-dismiss="modal" type="button"><span aria-hidden="true">×</span></button>
		              </div>
		                    <div class="calender_event_pop_up">
		                      <div class="container">
		                        <div class="tophead">
		                          <div class="image">
		                            <img src="'.$data['field_image'].'" alt="'.$data['title'].'" />
		                          </div>
		                          <div class="title-timing">
		                            <div>
		                                <span class="timing-date">
		                              '.date("M",$data['latest_timing']).'</span>
		                                <span class="timing-info">'.date("d",$data['latest_timing']).'</span>
		                            </div>
		                            <div>
		                              <h2>
		                               '.$data['title'].' 
		                              </h2>
		                              <span class="loca">'.$data['field_location'].'</span>
		                            </div>
		                          </div>
		                        </div>
		                     
		                        <div class="details">
		                          <div class="body">
		                            
		                            <div class="node-body">
		                            '.$data['body'].'
		                            </div>
		                          </div>
		                          <div class="date-location">
		                            <div class="date-and-time">
		                              <p>DATE AND TIME</p><span class="timing-date">
		                           '.date("l",$data['latest_timing']).'<br/>'.date("M d, Y h:i A",$data['latest_timing']).'
		                                </span>
		                              <p>UNTIL</p>
		                              <span>
		                          '.$data['field_location'].'
		                              </span>
		                            <div>
		                            <div class="location">
		                              <p>LOCATION</p>
		                             '.$data['field_location'].'
		                            <div>';
		                           
		                            $html .= '<div class="ticketing">
		                              <p>ASSESSMENT TYPE</p>
		                             <p style="color:#000;">'.ucfirst($assessment_type).'</p>
		                            <div>';
		                           	
		                        $html .=  '</div>
		                        </div>
		                      </div>
		                      </div>
		                 </div>
		              
		            </div>
		          </div>';
		    //print_r($nid);
		    $response = array('nid' => $nid,'modal' => Markup::create($html));
		    return new JsonResponse($response);
		}
   
}
