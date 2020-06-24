<?php

namespace Drupal\Tests\fillpdf_legacy\Kernel;

use Drupal\fillpdf_legacy\Plugin\PdfBackend\LegacyProviderPdfBackend;
use Drupal\Tests\fillpdf\Kernel\FillPdfKernelTestBase;

/**
 * Tests that backend-related functions work.
 *
 * @group fillpdf
 * @group legacy
 */
class LegacyBackendTest extends FillPdfKernelTestBase {

  /**
   * {@inheritdoc}
   */
  public static $modules = ['fillpdf_legacy'];

  /**
   * Tests the legacy test backend.
   */
  public function testTestBackend() {
    $backend_manager = $this->container->get('plugin.manager.fillpdf.pdf_backend');
    $test_backend = $backend_manager->createInstance('test');
    self::assertInstanceOf(LegacyProviderPdfBackend::class, $test_backend);
  }

}
