<?php

namespace Drupal\fillpdf\Component\Helper;

/**
 * Class FillPdfMappingHelper.
 *
 * @package Drupal\fillpdf\Component\Helper
 */
class FillPdfMappingHelper {

  /**
   * Parses replacements.
   *
   * @param string $replacements_string
   *   The replacements string.
   *
   * @return string[]
   *   Associative array of replacement values, keyed by the value to be
   *   replaced.
   */
  public static function parseReplacements($replacements_string) {
    if (!empty($replacements_string)) {
      $standardized_replacements = str_replace([
        "\r\n",
        "\r",
      ], "\n", $replacements_string);
      $lines = explode("\n", $standardized_replacements);
      $return = [];
      foreach ($lines as $replacement) {
        if (!empty($replacement)) {
          $split = explode('|', $replacement);
          // Sometimes it isn't; don't know why.
          if (count($split) == 2) {
            $return[$split[0]] = preg_replace('|<br />|', '
', $split[1]);
          }
        }
      }
      return $return;
    }
    else {
      return [];
    }
  }

  /**
   * Applies form and field level replacements to a string.
   *
   * @param string $value
   *   The value to replace. Must match the key in a replacements field exactly.
   * @param array $form_replacements
   *   A list of form-level replacements.
   * @param array $field_replacements
   *   A list of field-level replacements. These have precedence.
   *
   * @return string
   *   $value with any matching replacements applied.
   *
   * @deprecated in fillpdf:8.x-4.7 and is removed from fillpdf:8.x-5.0.
   * Deprecated in core.
   * @see https://www.drupal.org/project/fillpdf/issues/3044743
   */
  public static function transformString($value, array $form_replacements, array $field_replacements) {
    @trigger_error('transformString is deprecated in fillpdf:8.x-4.7 and is removed from fillpdf:8.x-5.0. Deprecated in core. See https://www.drupal.org/project/fillpdf/issues/3044743', E_USER_DEPRECATED);
    // Merge both with field-level replacements taking precedence.
    $replacements = array_merge($form_replacements, $field_replacements);
    return isset($replacements[$value]) ? $replacements[$value] : $value;
  }

}
