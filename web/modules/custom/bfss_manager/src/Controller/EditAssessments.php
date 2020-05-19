<?php

namespace Drupal\bfss_manager\Controller;

use Drupal\Core\Controller\ControllerBase;
use \Drupal\node\Entity\Node;
use  \Drupal\user\Entity\User;
use Drupal\Core\Render\Markup;
use Drupal\Core\Database\Database;
use Drupal\user\Entity\Role;

class EditAssessments extends ControllerBase {

  public function edit_assessments() {

    $uid = \Drupal::currentUser()->id();

    $query = \Drupal::entityQuery('node');
    $query->condition('type', 'assessment');
    $query->condition('uid', $uid,'=');
    $nids = $query->execute();
    $data = [];
    foreach ($nids as $nid) {
     $node = Node::load($nid);
     $data[] = [
      'nid' => $nid,
      'title' => $node->title->value,
      'type' => $node->field_type_of_assessment->value,
      'location' => $node->field_location->value,
     ]; 
    }


     $tb = '<div class="wrapped_div_main user_pro_block">
      <h2>Assessments</h2>
      <div class="block-bfss-assessors">
      <div class="table-responsive">
     <table id="dtBasicExample" class="table table-hover table-striped" cellspacing="0" width="100%" >
        <thead>
          <tr>
            <th class="th-hd"><a><span></span> Title</a>
            </th>
            <th class="th-hd"><a><span></span> Type</a>
            </th>
            <th class="th-hd"><a><span></span> Location</a>
            </th>
            <th class="th-hd"><a><span></span> Edit</a>
            </th>
            <th class="th-hd"><a><span></span> DELETE</a>
            </th>
          
          </tr>
        </thead>
        <tbody>';

        foreach ($data as $key => $value) {
          $url= '/edit-assessments-data?nid='.$value['nid'];
          $title = Markup::create($value['title']);
          $Edit = Markup::create('<p><a href="'.$url.'"  class="edit-assess-pl">EDIT</a></p>');
          $Delete = Markup::create('<p><a class="delete-assess-pl" data-nid="'.$value['nid'].'">DELETE</a></p>');
          $tb .= '<tr>
                <td>'.$title.'</td>
                <td>'.$value['type'].'</td>
                <td>'.$value['location'].'</td>
                <td>'.$Edit.'</td>
                <td>'.$Delete.'</td>
              </tr>';
        }
        
         $tb .= '</tbody>
          </table>
           </div>
          </div>
           </div>';
    // echo "<pre>";
    // print_r($data);
    // die;
   // $form = \Drupal::formBuilder()->getForm('Drupal\bfss_manager\Form\EditAssessmentsForm');
    
    return [
      '#cache' => ['max-age' => 0,],
      '#theme' => 'edit_assessments_page',
      '#edit_assessments_block' => Markup::create($tb),
      '#attached' => [
        'library' => [
          'acme/acme-styles', //include our custom library for this response
        ]
      ]
    ];   
  
  } 
}
