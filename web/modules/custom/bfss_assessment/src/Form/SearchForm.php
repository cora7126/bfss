<?php
namespace Drupal\bfss_assessment\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Ajax\AjaxResponse;
use Drupal\Core\Ajax\AlertCommand;
use \Drupal\node\Entity\Node;
use Drupal\node\NodeInterface;
Use Drupal\paragraphs\Entity\Paragraph;
use Drupal\Core\Render\Markup;
use Drupal\Core\Ajax\InvokeCommand;
use \Drupal\user\Entity\User;
use Drupal\Core\Ajax\RedirectCommand;

/**
 * Contribute form.
 */
class SearchForm extends FormBase {

	  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'search_query_from';
  }

  /**
   * {@inheritdoc}
   */
  	public function buildForm(array $form, FormStateInterface $form_state) {
      $param = \Drupal::request()->query->all();
 			$form['search_query'] = [
		      '#type' => 'textfield',
		      //'#title' => t('Search'),
		      '#default_value' => '',
		      '#required' => TRUE,
		      '#attributes' => array(
		       	'placeholder' => t('Search'),
		      ),
          '#prifix'=>'<div class="search-input-main-px">'
            ];

        $form['actions']['#type'] = 'actions';
		    $form['actions']['submit'] = [
		      '#type' => 'submit',
		      '#value' => $this->t('Search'),
		      '#button_type' => 'primary',
		      // '#prefix' => '<div class="filter_btn">',
		      // '#suffix' => '</div>',
		      '#ajax' => [
	              'callback' => '::SearchAjaxCallback', 
	              'disable-refocus' => FALSE, 
	              'event' => 'click',
                '#suffix' => '</div>',
	              'wrapper' => 'edit-output',
		              'progress' => [
		                'type' => 'throbber',
		                'message' => $this->t('Verifying entry...'),
		          ],
       			],
		    ];

            return $form;
    }


      /**
       * {@inheritdoc}
       */
    public function validateForm(array &$form, FormStateInterface $form_state) {
         
    }

      /**
       * {@inheritdoc}
       */
    public function submitForm(array &$form, FormStateInterface $form_state) {

    }

    public function SearchAjaxCallback(array &$form, FormStateInterface $form_state){


      global $base_url;
      $current_path = \Drupal::service('path.current')->getPath();
      $serach_name = $form_state->getValue('search_query');

      $response = new \Drupal\Core\Ajax\AjaxResponse();
      $url = $base_url.$current_path.'?SearchAssessments='.$serach_name;
      $response->addCommand(new RedirectCommand($url));    
	  return $response;
  	}


}//class end