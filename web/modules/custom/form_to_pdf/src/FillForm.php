<?php
// namespace Drupal\form_to_pdf;

/***
 * Fills a PDF's form fields with data from html form.
 */
class FillPdf {

  // Pdf template name is key, value is array of pdf form field names (keys) and units (values) - the units will always be at the end.
  public $template_fields = [
    'starter-report.pdf' => [
      'FULL_NAME_TOP'    => ' ',
      'AGE'    => ' y/o',
      'SPORT'    => ' ',
      'WEIGHT'    => ' lbs',
      'SEX'    => ' ',
      'YOU_REACTIVE'    => ' in',
      'YOU_ELASTIC'    => ' in',
      'YOU_BALLISTIC'    => ' in',
      'YOU_ACCELERATION'    => ' secs',
      'YOU_MAXIMAL'    => ' lbs',
      'ELITE_REACTIVE'    => ' in',
      'BENCHMARK_REACTIVE'    => ' in',
    ],
    'professional-report.pdf' => ['TOP'],
    'elite-report.pdf' => ['TOP'],
  ];
  // private $pdf_templates = ['starter-report.pdf', 'professional-report.pdf', 'elite-report.pdf'];

  public function getPostArray($pdfTemplate, $postAry = []) {

    // require_once 'fpdm_start.php';

    $fillArray = [];

    if ($this->template_fields[$pdfTemplate]) {
      foreach ($this->template_fields[$pdfTemplate] as $fieldName => $unit) {
        $fillArray[$fieldName] = @$postAry[$fieldName] . $unit;
      }
    }
    else {
      $errMsg .= "Error: Template name not correct<br>";
    }
    return $fillArray;
    // $pdf->Load($fillArray, false); // second parameter: false if field values are in ISO-8859-1, true if UTF-8
    // $pdf->Merge();
    // $pdf->Output();
  }
}
