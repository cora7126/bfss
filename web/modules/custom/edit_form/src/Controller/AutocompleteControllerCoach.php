<?php

namespace Drupal\edit_form\Controller;

use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Drupal\Component\Utility\Xss;
use Drupal\Core\Entity\Element\EntityAutocomplete;
use  \Drupal\user\Entity\User;
/**
 * Defines a route controller for entity autocomplete form elements.
 */
class AutocompleteControllerCoach extends ControllerBase {



/**
   * Handler for autocomplete request.
   */
  public function handleAutocomplete(Request $request,$parm_1, $count) {
    $results = [];
    $input = $request->query->get('q');
    if (!$input) {
      return new JsonResponse($results);
    }
    $input = Xss::filter($input);
    $coach_ids = \Drupal::entityQuery('user')
    ->condition('field_first_name', $input, 'CONTAINS')
    ->condition('roles', 'coach', 'CONTAINS')
    ->execute();

    foreach ($coach_ids as $coach_id) {
      $user = User::load($coach_id);
      $label = [
              $user->field_first_name->value.' '.$user->field_last_name->value,
      ];
      $results[] = [
          'value' => $user->uid->value,
          'label' => implode(' ', $label),
      ];
    }
    return new JsonResponse($results);
  }

}
