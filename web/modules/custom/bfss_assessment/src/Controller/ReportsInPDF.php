<?php

namespace Drupal\bfss_assessment\Controller;

use Drupal\Core\Controller\ControllerBase;

/**
 * Defines ReportsInPDF class.
 */
class ReportsInPDF extends ControllerBase {

  /**
   * Display the markup.
   *
   * @return array
   *   Return markup array.
   */
  public function get_reports() {

    $path = 'http://5ppsystem.com/modules/custom/bfss_assessment/css/pdf-style.css';
    $logo = 'http://5ppsystem.com/modules/custom/bfss_assessment/img/logo.png';
    $param = \Drupal::request()->query->all();
  
    $stylesheet = file_get_contents($path);
   
    $mpdf = new \Mpdf\Mpdf(['tempDir' => 'sites/default/files/tmp']); 
    $mpdf->WriteHTML($stylesheet,\Mpdf\HTMLParserMode::HEADER_CSS);

    $mpdf->imageVars['myvariable'] = file_get_contents($logo);
    $mpdf->Image('var:myvariable');
    $html = '<div>
              <div class="logo-image" style="text-align:center;"><img src="var:myvariable" style="text-align:center;" /></div>
              <table>
                      <tr>
                        <th>Date</th>
                        <th>Location</th>
                        <th>Status</th>
                      </tr>
                      <tr>
                        <td>'.$param['date'].'</td>
                        <td>'.$param['location'].'</td>
                        <td>'.$param['status'].'</td>
                      </tr>
              </table>
            </div>';

    $mpdf->WriteHTML($html);
    $mpdf->Output('Records.pdf', 'D');
    Exit;
    // return [
    //   '#type' => 'markup',
    //   '#markup' => $this->t('Hello, World!'),
    // ];
  }

}