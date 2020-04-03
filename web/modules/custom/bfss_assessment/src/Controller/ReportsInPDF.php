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
   
    $mpdf = new \Mpdf\Mpdf(['tempDir' => 'modules/custom/bfss_assessment/pdftemp']); 
    $mpdf->WriteHTML($stylesheet,\Mpdf\HTMLParserMode::HEADER_CSS);

    $mpdf->imageVars['myvariable'] = file_get_contents($logo);
    $mpdf->Image('var:myvariable');
    $html = '<div>
              <div class="logo-image" style="text-align:center;"><img src="var:myvariable" style="text-align:center;" /></div>
              <table>
                      <tr>
                        <th>Date</th>
                        <td>'.$param['date'].'</td>
                      </tr>

                       <tr>
                        <th>Location</th>
                        <td>'.$param['location'].'</td>
                      </tr>

                       <tr>
                        <th>Status</th>
                         <td>'.$param['status'].'</td>
                      </tr>
                      <tr>
                        <th>User Name</th>
                         <td>'.$param['user_name'].'</td>
                      </tr>
                      <tr>
                        <th>Assessment Type</th>
                         <td>'.$param['type'].'</td>
                      </tr>
                       <tr>
                        <th>Sport</th>
                         <td>'.$param['sport'].'</td>
                      </tr>
                      <tr>
                        <th>Time</th>
                         <td>'.$param['time'].'</td>
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