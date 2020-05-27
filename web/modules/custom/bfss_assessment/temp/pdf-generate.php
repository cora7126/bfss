<?php




error_reporting(0);

/***************************
  Sample using an FDF file
****************************/
$fdfDoc = "";

$fdfDoc .= getFdc("top");

$fdfDoc .= getFdc("FULL_NAME_TOP");
$fdfDoc .= getFdc("AGE");
$fdfDoc .= getFdc("SPORT");
$fdfDoc .= getFdc("WEIGHT");
$fdfDoc .= getFdc("SEX");
$fdfDoc .= getFdc("YOU_REACTIVE");
$fdfDoc .= getFdc("YOU_ELASTIC");
$fdfDoc .= getFdc("YOU_BALLISTIC");
$fdfDoc .= getFdc("YOU_ACCELERATION");
$fdfDoc .= getFdc("YOU_MAXIMAL");
$fdfDoc .= getFdc("ELITE_REACTIVE");
$fdfDoc .= getFdc("BENCHMARK_REACTIVE");

$fdfDoc .= getFdc("bottom");

/***
 * $var (string) - Three possibilities:
 *    1.  "top"
 *    2.  POST variable name
 *    3.  "bottom"
 */
function getFdc($var) {
  $output = '';

  switch ($var) {
    case "top":
      $output = <<< FDF
%FDF-1.2
%����
1 0 obj
<<
/FDF <</Fields [
FDF;
    break;

    case "bottom":
      $output = <<< FDF
] /F (template.pdf)>>
>>
endobj
trailer
<<
/Root 1 0 R
>>
%%EOF
FDF;
    break;

    default:
      $output = '<</T ('.$var.') /V (V_'.@$_POST[$var].')>>'."\n";
    break;
  }

  return $output;
}


require('fpdm.php');

$pdf = new FPDM('./starter-report-pdftk.pdf', 'starter-report-pdftk.fdf');
$pdf->Merge();
$pdf->Output();
?>
