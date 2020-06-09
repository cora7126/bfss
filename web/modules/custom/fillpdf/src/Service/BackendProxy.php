<?php

namespace Drupal\fillpdf\Service;

use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\file\Entity\File;
use Drupal\fillpdf\Component\Helper\FillPdfMappingHelper;
use Drupal\fillpdf\FieldMapping\TextFieldMapping;
use Drupal\fillpdf\FillPdfFormInterface;
use Drupal\fillpdf\Plugin\PdfBackendManager;
use Drupal\fillpdf\TokenResolverInterface;

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


    //jjj added all "session" stuff here, to create pdf.
    session_start();

    foreach ($fillPdfForm->getFormFields() as $pdf_key => $field) {
      if (isset($_SESSION['temp-session-form-values'])) {
        $fieldMappings[$pdf_key] = new TextFieldMapping($_SESSION['temp-session-form-values'][$pdf_key]);
      }
      elseif ($mergeOptions['sample']) {
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

}
