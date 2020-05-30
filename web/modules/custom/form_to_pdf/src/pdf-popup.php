<?php
session_start();
error_reporting(0);

foreach ($_SESSION as $pKey => $pVal) {
  $_POST[$pKey] = $pVal;
}

require('fpdm_start.php');

require('FillForm.php');

$pdf = new FPDM('../pdf_templates/starter-report-pdftk.pdf');

$fillForm = new FillPdf;

$postAry = $_POST;
$postAry['FULL_NAME_TOP'] = 'Jilly Bean';

$fillArray = $fillForm->getPostArray('starter-report.pdf', $postAry);

$pdf->Load($fillArray, false); // second parameter: false if field values are in ISO-8859-1, true if UTF-8
$pdf->Merge();
$pdf->Output();

