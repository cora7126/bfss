<?php

namespace Drupal\fillpdf;

use Drupal\Core\Entity\EntityTypeManagerInterface;
use Symfony\Component\Serializer\Serializer as SymfonySerializer;

/**
 * Class Serializer.
 *
 * @package Drupal\fillpdf
 */
class Serializer implements SerializerInterface {

  /**
   * Symfony\Component\Serializer\Serializer definition.
   *
   * @var \Symfony\Component\Serializer\Serializer
   */
  protected $serializer;

  /**
   * The entity type manager.
   *
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected $entityTypeManager;

  /**
   * Constructs a Serializer object.
   *
   * @param \Symfony\Component\Serializer\Serializer $serializer
   *   The FillPdf Form to serialize.
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entity_type_manager
   *   The entity type manager.
   */
  public function __construct(SymfonySerializer $serializer, EntityTypeManagerInterface $entity_type_manager) {
    $this->serializer = $serializer;
    $this->entityTypeManager = $entity_type_manager;
  }

  /**
   * {@inheritdoc}
   */
  public function getFormExportCode(FillPdfFormInterface $fillpdf_form) {
    $fields = $fillpdf_form->getFormFields();

    $form_config = [
      'form' => $this->serializer->normalize($fillpdf_form),
      'fields' => $this->serializer->normalize($fields),
    ];

    $code = $this->serializer->serialize($form_config, 'json');
    return $code;
  }

  /**
   * {@inheritdoc}
   */
  public function deserializeForm($code) {
    $mappings_raw = json_decode($code, TRUE);
    $decoded_fillpdf_form = $this->serializer->denormalize($mappings_raw['form'], 'Drupal\fillpdf\Entity\FillPdfForm');

    // Denormalization is a pain; we have to iterate over the fields to actually
    // recompose the $fields array.
    $field_json = $mappings_raw['fields'];
    $decoded_fields = [];

    foreach ($field_json as $normalized_field) {
      $field = $this->serializer->denormalize($normalized_field, 'Drupal\fillpdf\Entity\FillPdfFormField');
      // @todo: Exported fields are now already keyed by PDF key. For now, we're
      // not using the array keys to remain compatible with previous exports,
      // but should do so that at some later point.
      $decoded_fields[$field->pdf_key->value] = $field;
    }

    $return = ['form' => $decoded_fillpdf_form, 'fields' => $decoded_fields];
    return $return;
  }

  /**
   * {@inheritdoc}
   */
  public function importForm(FillPdfFormInterface $fillpdf_form, FillPdfFormInterface $imported_form, array $imported_fields) {
    $properties_to_import = $imported_form->getPropertiesToExport();
    foreach ($imported_form->getFields() as $name => $data) {
      if (in_array($name, $properties_to_import, TRUE)) {
        $fillpdf_form->{$name} = $data;
      }
    }
    $fillpdf_form->save();

    // Key the existing FillPDF fields on PDF keys.
    $existing_fields = $fillpdf_form->getFormFields();
    $unmatched_pdf_keys = $this->importFormFields($imported_fields, $existing_fields);
    return $unmatched_pdf_keys;
  }

  /**
   * {@inheritdoc}
   */
  public function importFormFields(array $keyed_fields, array &$existing_fields = [], $save_existing_fields = TRUE): array {
    $affected_fields = [];
    $unmatched_pdf_keys = [];
    foreach ($keyed_fields as $pdf_key => $keyed_field) {
      // If the imported field's PDF key matching the PDF key of the
      // existing field, then copy the constituent entity properties.
      if (in_array($pdf_key, array_keys($existing_fields), TRUE)) {
        $properties_to_import = $keyed_field->getPropertiesToExport();
        foreach ($keyed_field->getFields() as $keyed_field_name => $keyed_field_data) {
          if (in_array($keyed_field_name, $properties_to_import, TRUE)) {
            $existing_fields[$pdf_key]->{$keyed_field_name} = $keyed_field_data;
          }
        }
        $affected_fields[] = $pdf_key;
      }
      else {
        $unmatched_pdf_keys[] = $pdf_key;
      }
    }
    // Save changed fields.
    foreach ($affected_fields as $pdf_key) {
      $existing_fields[$pdf_key]->save();
    }
    return $unmatched_pdf_keys;
  }

  /**
   * Overwrites empty new field values with previous existing values.
   *
   * @param \Drupal\fillpdf\FillPdfFormFieldInterface[] $form_fields
   *   Associative array of saved FillPdfFormField objects keyed by entity ID.
   * @param string[] $existing_fields
   *   (optional) Array of existing PDF keys.
   *
   * @return string[]
   *   Array of unmatched PDF keys.
   *
   * @deprecated in fillpdf:8.x-4.7 and is removed from fillpdf:8.x-5.0.
   *   Field lists are already keyed by pdf_key now, so rekeying them is
   *   unnecessary. Use ::importFormFields instead.
   * @see https://www.drupal.org/project/fillpdf/issues/3055097
   * @see \Drupal\fillpdf\SerializerInterface::importFormFields()
   */
  public function importFormFieldsByKey(array $form_fields, array $existing_fields = []) {
    @trigger_error('SerializerInterface::importFormFieldsByKey() is deprecated in fillpdf:8.x-4.7 and is removed from fillpdf:8.x-5.0. Use \Drupal\fillpdf\SerializerInterface::importFormFields() instead. See https://www.drupal.org/project/fillpdf/issues/3055097', E_USER_DEPRECATED);
    $keyed_fields = [];
    foreach ($form_fields as $form_field) {
      $keyed_fields[$form_field->pdf_key->value] = $form_field;
    }
    return $this->importFormFields($keyed_fields, $existing_fields);
  }

}
