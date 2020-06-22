<?php

namespace Drupal\fillpdf\Service;

use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\file\Entity\File;
use Drupal\fillpdf\Component\Helper\FillPdfMappingHelper;
use Drupal\fillpdf\FieldMapping\TextFieldMapping;
use Drupal\fillpdf\FillPdfFormInterface;
use Drupal\fillpdf\Plugin\PdfBackendManager;
use Drupal\fillpdf\TokenResolverInterface;

<<<<<<< bfss-integration-mindimage-2
use Drupal\Core\Controller\ControllerBase;
use  \Drupal\user\Entity\User;
use \Drupal\node\Entity\Node;
use Drupal\node\NodeInterface;
use Drupal\Core\Render\Markup;
Use Drupal\paragraphs\Entity\Paragraph;


=======
>>>>>>> jody-ryan-integration
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

<<<<<<< bfss-integration-mindimage-2
    //jjj hack, added all "session" stuff here, to create pdf.
    // session_start();

    foreach ($fillPdfForm->getFormFields() as $pdf_key => $field) {
      // if (isset($_SESSION['temp-session-form-values'])) {
      //   $fieldMappings[$pdf_key] = new TextFieldMapping($_SESSION['temp-session-form-values'][$pdf_key]);
      // } else
      if ($mergeOptions['sample']) {
=======

    //jjj added all "session" stuff here, to create pdf.
    session_start();

    foreach ($fillPdfForm->getFormFields() as $pdf_key => $field) {
      if (isset($_SESSION['temp-session-form-values'])) {
        $fieldMappings[$pdf_key] = new TextFieldMapping($_SESSION['temp-session-form-values'][$pdf_key]);
      }
      elseif ($mergeOptions['sample']) {
>>>>>>> jody-ryan-integration
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
<<<<<<< bfss-integration-mindimage-2

        // ksm(['$pdf_key, $fieldMappings[$pdf_key], $text, $options', $pdf_key, $fieldMappings[$pdf_key], $text, $options]);
      }
    }

    // $entityNodeType = $entities['node'][$_GET['entity_id']];

    /** custom fieldmappings redo for bfss, as tokenResolver->replace has issues. by Jody Brabec
     * Notes on this issue:
     *  $entityNodeType->getEntityTypeId() is "node"
     *  $entityNodeType->getEntityType() is an object of type Drupal\Core\Entity\ContentEntityType
     *  When I do  var_export($entities['node']['333']) returns lots, including correct age of 1122:  'field_age' => array ('x-default' => array ( 0 =>  array ('value' => '1122',),),)
     * $entities['node']['333']->getEntityType()->get('field_age') returns NULL
     * $entities is of type:  \Drupal\Core\Entity\EntityInterface[][]
     */

    //assessment get by current assessors
    $uid = \Drupal::currentUser();
    $user = \Drupal\user\Entity\User::load($uid->id());
    // $roles = $user->getRoles();

    $fieldMappings = $this->My_assessments($uid, $_GET['entity_id'], $fieldMappings);

    // ksm('$myAssessments', $myAssessments);


=======
      }
    }

>>>>>>> jody-ryan-integration
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


  protected function My_assessments($uid, $booked_id_param, $fieldMappings){
    $query = \Drupal::entityQuery('node');
    $query->condition('type', 'assessment');
    $query->range(0, 50);
    $nids = $query->execute();

    /**
     * TODO: Fix this mess!  So many old bastardized vars.
     * Note: this only loops once, so yeah, fix Jody.
     */
    // ksm('$nid', $nids);
    foreach ($nids as $nid) {
      $booked_ids = \Drupal::entityQuery('bfsspayments')
        ->condition('assessment', $nid,'IN')
        ->condition('user_id',$uid->id(),'IN')
        ->sort('time','DESC')
        ->execute();
      // ksm('$booked_ids', $booked_ids);

      foreach ($booked_ids  as $key => $booked_id) {

        $entity = \Drupal\bfss_assessment\Entity\BfssPayments::load($booked_id_param);
        // ksm('$entity', $entity);
        $address_1 = $entity->address_1->value;

        $timestamp = $entity->time->value;
        $booking_date = date("F d,Y",$timestamp);
        $booking_time = date("h:i a",$timestamp);

        $query1 = \Drupal::entityQuery('node');
        $query1->condition('type', 'athlete_assessment_info');
        $query1->condition('field_booked_id',$booked_id_param, 'IN');
        $nids1 = $query1->execute();

          //sport
        $query5 = \Drupal::database()->select('athlete_school', 'ats');
        $query5->fields('ats');
        $query5->condition('athlete_uid', $uid->id(),'=');
        $results5 = $query5->execute()->fetchAssoc();
        $sport = $results5['athlete_school_sport'];

        $realFormType = $this->getFormTypeFromPrice($entity->service->value);

        if(!empty($entity->assessment->value)){
          $Assess_type = 'individual';
        }else{
          $Assess_type = 'private';
        }

        $st ='';
        $assess_nid = '';
        if(!empty($nids1)) {
          $st = 1;
          foreach ($nids1 as $key => $value) {
            $node1 = Node::load($value);
            $assess_nid = $value;

            $field_status = $node1->field_status->value;
            foreach ($node1->getFields() as $fieldName => $value) {
              $assAry[$fieldName] = $value->getValue($fieldName)[0]['value'];
              //No workie: $assAry[$fieldName] = $value->get($fieldName)->getValue();
            }
            $mappingsWithValues = $fieldMappings;
            foreach ($fieldMappings as $pdf_key => $emptyValue) {
              if (isset($assAry[$pdf_key])) {
                $mappingsWithValues[$pdf_key] =  new TextFieldMapping($assAry[$pdf_key]);
              }
            }

            if ($entity->first_name->value) {
              $mappingsWithValues['first_name'] =  new TextFieldMapping($entity->first_name->value);
              $mappingsWithValues['last_name'] =  new TextFieldMapping($entity->last_name->value);
            }
            $mappingsWithValues['sport'] =  new TextFieldMapping($sport);


            $oldResult = array(
              'id' => $entity->id->value,
              'user_name' =>$entity->user_name->value,
              'nid' => $nid,
              'formtype' => $realFormType,
              // 'Assess_type' => $Assess_type,
              'booking_date'  => $booking_date,
              'booking_time'  => $booking_time,
              'booked_id' => $booked_id_param,
              'st' =>  $st,
              'assess_nid' => $assess_nid,
              'address_1' => $address_1,
              'sport' => $sport,
              'status' => $field_status,
              'time' => $booking_time,
            );

            // ksm('$mappingsWithValues, $assAry, $oldResult', $mappingsWithValues, $assAry, $oldResult);

            return $mappingsWithValues;
          }
        }else{
          // ksm('error in BackendProxy');
        }
      }
    }
    return $fieldMappings;
  }

    /** TODO: make utility class
   * Use this to extract "professional", because $param['formtype'] only contains 'starter' OR 'elite'
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

