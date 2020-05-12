<?php

namespace Drupal\edit_form\Controller;

use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Drupal\Component\Utility\Tags;
use Drupal\Component\Utility\Unicode;
use \Drupal\node\Entity\Node;
use Drupal\node\NodeInterface;
/**
 * Defines a route controller for entity autocomplete form elements.
 */
class AutocompleteController extends ControllerBase {

  /**
   * Handler for autocomplete request.
   */
  public function handleAutocomplete(Request $request, $field_name, $count) {
    $results = [];

    // Get the typed string from the URL, if it exists.
    if ($input = $request->query->get('q')) {
      $typed_string = Tags::explode($input);
      $typed_string = Unicode::strtolower(array_pop($typed_string));
      // @todo: Apply logic for generating results based on typed_string and other
      // arguments passed.
        $query = \Drupal::entityQuery('node');
        $query->condition('type', 'bfss_organizations');
        $query->condition('field_type', $field_name, 'IN');
        $nids = $query->execute();
        $org_name=[];
        foreach($nids as $nid){
          $node = Node::load($nid);
          $org_name[]= $node->field_organization_name->value;
        }
    }

    return new JsonResponse($org_name);
  }


    public function Get_Org_Name($type){
      if(isset($type)){
        $query = \Drupal::entityQuery('node');
        $query->condition('type', 'bfss_organizations');
        $query->condition('field_type', $type, 'IN');
        $nids = $query->execute();
        $org_name=[];
        foreach($nids as $nid){
          $node = Node::load($nid);
          $org_name[]= $node->field_organization_name->value;
        }
        $result = implode(",",$org_name);
      }
      return $result;
    }

}
