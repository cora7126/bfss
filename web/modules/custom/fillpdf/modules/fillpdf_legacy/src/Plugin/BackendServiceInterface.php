<?php

namespace Drupal\fillpdf_legacy\Plugin;

use Drupal\Component\Plugin\PluginInspectionInterface;

/**
 * Defines an interface for FillPDF BackendService plugins.
 *
 * @deprecated in fillpdf:8.x-4.9 and is removed from fillpdf:8.x-5.0.
 *   Extend PdfBackendBase instead.
 * @see https://www.drupal.org/node/3059476
 * @see \Drupal\fillpdf\Plugin\PdfBackendBase
 */
interface BackendServiceInterface extends PluginInspectionInterface {

  /**
   * Parse a PDF and return a list of its fields.
   *
   * @param string $pdf_content
   *   The PDF whose fields are going to be parsed. This should be the contents
   *   of a PDF loaded with something like file_get_contents() or equivalent.
   *
   * @return array[]
   *   An array of arrays containing metadata about the fields in the PDF. These
   *   can be iterated over and saved by the caller.
   */
  public function parse($pdf_content);

  /**
   * Populate a PDF file with field data.
   *
   * @param string $pdf_content
   *   The PDF into which to merge the field values specified in the mapping.
   * @param \Drupal\fillpdf\FieldMapping[] $field_mappings
   *   An array of FieldMapping-derived objects mapping PDF field keys to the
   *   values with which they should be replaced. Strings are also acceptable
   *   and converted to TextFieldMapping objects.
   *   Example array:
   *   @code
   *   [
   *     'Foo' => new TextFieldMapping('bar'),
   *     'Foo2' => new TextFieldMapping('bar2'),
   *     'Image1' => new ImageFieldMapping(base64_encode(file_get_contents($image)), 'jpg'),
   *   ]
   *   @endcode
   * @param array $context
   *   The request context as returned by
   *   FillPdfLinkManipulatorInterface::parseLink().
   *
   * @return string|null
   *   The raw file contents of the new PDF, or NULL if merging failed. The
   *   caller has to handle saving or serving the file accordingly.
   *
   * @see \Drupal\fillpdf\FieldMapping
   * @see \Drupal\fillpdf\FieldMapping\TextFieldMapping
   * @see \Drupal\fillpdf\FieldMapping\ImageFieldMapping
   * @see \Drupal\fillpdf\FillPdfLinkManipulatorInterface::parseLink()
   */
  public function merge($pdf_content, array $field_mappings, array $context);

}
