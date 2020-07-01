<?php

namespace Drupal\fillpdf\Service;

use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\file\Entity\File;
use Drupal\fillpdf\Component\Helper\FillPdfMappingHelper;
use Drupal\fillpdf\FieldMapping\TextFieldMapping;
use Drupal\fillpdf\FillPdfFormInterface;
use Drupal\fillpdf\Plugin\PdfBackendManager;
use Drupal\fillpdf\TokenResolverInterface;
use Drupal\node\Entity\Node;


/**
 * BackendProxy service.
 */
class BackendProxy implements BackendProxyInterface {

  /**
   * The fillpdf.token_resolver service.
   *
   * @var \Drupal\fillpdf\TokenResolverInterface
   */
  protected $tokenResolver;

  /**
   * The plugin.manager.fillpdf.pdf_backend service.
   *
   * @var \Drupal\fillpdf\Plugin\PdfBackendManager
   */
  protected $backendManager;
  /**
   * The configuration factory.
   *
   * @var \Drupal\Core\Config\ConfigFactoryInterface
   */
  protected $configFactory;

  /**
   * Constructs a BackendProxy object.
   *
   * @param \Drupal\fillpdf\TokenResolverInterface $tokenResolver
   *   The fillpdf.token_resolver service.
   * @param \Drupal\fillpdf\Plugin\PdfBackendManager $backendManager
   *   The plugin.manager.fillpdf.pdf_backend service.
   * @param \Drupal\Core\Config\ConfigFactoryInterface $configFactory
   *   The configuration factory.
   */
  public function __construct(TokenResolverInterface $tokenResolver, PdfBackendManager $backendManager, ConfigFactoryInterface $configFactory) {
    $this->tokenResolver = $tokenResolver;
    $this->backendManager = $backendManager;
    $this->configFactory = $configFactory;
  }

  /**
   * {@inheritdoc}
   *
   * @throws \Drupal\Component\Plugin\Exception\PluginException
   */
  public function merge(FillPdfFormInterface $fillPdfForm, array $entities, array $mergeOptions = []): string {
    $config = $this->configFactory->get('fillpdf.settings');

    $form_replacements = FillPdfMappingHelper::parseReplacements($fillPdfForm->replacements->value);

    $mergeOptions += [
      'fid' => $fillPdfForm->id(),
      'sample' => FALSE,
      'flatten' => TRUE,
    ];

    // Populate mappings array.
    $fieldMappings = [];

    //jjj hack, added all "session" stuff here, to create pdf.
    // session_start();

    foreach ($fillPdfForm->getFormFields() as $pdf_key => $field) {
      // if (isset($_SESSION['temp-session-form-values'])) {
      //   $fieldMappings[$pdf_key] = new TextFieldMapping($_SESSION['temp-session-form-values'][$pdf_key]);
      // } else if ($mergeOptions['sample']) {
      if ($mergeOptions['sample']) {
        $fieldMappings[$pdf_key] = new TextFieldMapping("<$pdf_key>");
      }
      else {
        $options = [];
        // Our pdftk backend doesn't support image stamping, so at least for
        // this backend we already know which type of content we can expect.
        $options['content'] = $config->get('backend') === 'pdftk' ? 'text' : '';

        // Prepare transformations with field-level replacements taking
        // precedence over form-level replacements.
        $options['replacements'] = FillPdfMappingHelper::parseReplacements($field->replacements->value) + $form_replacements;

        // Add prefix and suffix.
        $options['prefix'] = $field->prefix->value;
        $options['suffix'] = $field->suffix->value;

        // Resolve tokens.
        $text = count($field->value) ? $field->value->value : '';
        $fieldMappings[$pdf_key] = $this->tokenResolver->replace($text, $entities, $options);
      }
    }

    /** custom fieldmappings redo for bfss, as tokenResolver->replace has issues. by Jody Brabec
     * Notes on this issue:
     *  $entityNodeType->getEntityTypeId() is "node"
     *  $entityNodeType->getEntityType() is an object of type Drupal\Core\Entity\ContentEntityType
     *  When I do  var_export($entities['node']['333']) returns lots, including correct age of 1122:  'field_age' => array ('x-default' => array ( 0 =>  array ('value' => '1122',),),)
     * $entities['node']['333']->getEntityType()->get('field_age') returns NULL
     * $entities is of type:  \Drupal\Core\Entity\EntityInterface[][]
     */

    $fieldMappings = $this->My_assessments($_GET['entity_id'], $fieldMappings);
    // ksm($fieldMappings['field_peak_force_n_maximal'], $fieldMappings);

    // Now load the backend plugin.
    /** @var \Drupal\fillpdf\FillPdfBackendPluginInterface|\Drupal\fillpdf\Plugin\PdfBackendInterface $backend */
    $backend = $this->backendManager->createInstance($config->get('backend'), $config->get());

    // @todo: Emit event (or call alter hook?) before populating PDF.
    // Rename fillpdf_merge_fields_alter() to fillpdf_populate_fields_alter().
    /** @var \Drupal\file\FileInterface $templateFile */
    $templateFile = File::load($fillPdfForm->file->target_id);

    $mergedPdf = $backend->mergeFile($templateFile, $fieldMappings, $mergeOptions);

    if (!is_string($mergedPdf)) {
      // Make sure we return a string as not to get an error. The underlying
      // backend will already have set more detailed errors.
      $mergedPdf = '';
    }

    return $mergedPdf;
  }


  // protected function My_assessments($booked_id_param, $fieldMappings){
  //   $query1 = \Drupal::entityQuery('node');
  //   $query1->condition('type', 'athlete_assessment_info');
  //   $query1->condition('field_booked_id',$booked_id_param, 'IN');
  //   $nids1 = $query1->execute();
  //   if(!empty($nids1)) {
  //     foreach ($nids1 as $key => $value) {
  //       $node1 = Node::load($value);
  //       foreach ($node1->getFields() as $fieldName => $value) {
  //         $assAry[$fieldName] = $value->getValue($fieldName)[0]['value'];
  //         //No workie: $assAry[$fieldName] = $value->get($fieldName)->getValue();
  //       }
  //       $mappingsWithValues = $fieldMappings;
  //       foreach ($fieldMappings as $pdf_key => $emptyValue) {
  //         if (isset($assAry[$pdf_key])) {
  //           $mappingsWithValues[$pdf_key] =  new TextFieldMapping($assAry[$pdf_key]);
  //         }
  //       }
  //       return $mappingsWithValues;
  //     }
  //   }
  // }

  protected function My_assessments($booked_id_param, $fieldMappings){
    // And get latest recorded status (and $assess_nid) for current assessment - see if complete or incomplete.
    $nidPrev = 0;
    $queryStat = \Drupal::entityQuery('node');
    $queryStat->condition('type', 'athlete_assessment_info');
    $queryStat->condition('field_booked_id',$booked_id_param, 'IN');
    $nidsStat = $queryStat->execute();
    if(!empty($nidsStat)) {
      foreach ($nidsStat as $nid) {
        if ($nid > $nidPrev) {
          $nidPrev = $nid;
          $assessmentNode = Node::load($nid);
          $assAry = [];
          foreach ($assessmentNode->getFields() as $fieldName => $nid) {
            $assAry[$fieldName] = $nid->getValue($fieldName)[0]['value'];
            //No workie: $assAry[$fieldName] = $nid->get($fieldName)->getValue();
          }
          // ksm($assAry);
          $mappingsWithValues = [];//$fieldMappings;
          foreach ($fieldMappings as $pdf_key => $emptyValue) {
            if (isset($assAry[$pdf_key])) {
              $tmp = new TextFieldMapping($assAry[$pdf_key]);
              $mappingsWithValues[$pdf_key] = $tmp; // ->getData();
            }
          }
        }
      }
      return $mappingsWithValues;
    }
  }


  /** TODO: make utility class
   * Use this to extract "professional" because $param['formtype'] only contains 'starter' OR 'elite'
   * @param string $assessmentPrice
   */
  public function getFormTypeFromPrice($assessmentPrice) {
    if($assessmentPrice == '299.99'){
      return 'elite';
    }elseif($assessmentPrice == '29.99'){
      return 'starter';
    }elseif($assessmentPrice == '69.99'){
      return 'professional';
    }else{
      return 'UNKNOWN';
    }
  }
}

