<?php

namespace Drupal\my_module\Form;

use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Entity\Element\EntityAutocomplete;

/**
 * Form to handle article autocomplete.
 */
class ArticleAutocompleteForm extends FormBase {



  public function getFormId() {
    return 'multistep_form_four';
  }


  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $form['article'] = [
      '#type' => 'textfield',
      '#title' => $this->t('My Autocomplete'),
      '#autocomplete_route_name' => 'my_module.autocomplete.articles',
    ];

    $form['actions'] = ['#type' => 'actions'];
    $form['actions']['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Save'),
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    // Extracts the entity ID from the autocompletion result.
    $article_id = EntityAutocomplete::extractEntityIdFromAutocompleteInput($form_state->getValue('article'));
  }
}
