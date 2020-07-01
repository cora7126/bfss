<?php

namespace Drupal\Tests\fillpdf\Functional;

use Drupal\file\Entity\File;
use Drupal\fillpdf\Component\Utility\FillPdf;
use Drupal\fillpdf\Entity\FillPdfForm;
use Drupal\fillpdf\FieldMapping\ImageFieldMapping;
use Drupal\fillpdf\FieldMapping\TextFieldMapping;
use Drupal\fillpdf_test\Plugin\FillPdfBackend\TestFillPdfBackend;
use Drupal\Tests\taxonomy\Traits\TaxonomyTestTrait;
use Drupal\user\Entity\Role;

// When 8.7.x is fully EOL, this can be removed.
if (!trait_exists('\Drupal\Tests\taxonomy\Traits\TaxonomyTestTrait')) {
  class_alias('\Drupal\Tests\taxonomy\Functional\TaxonomyTestTrait', '\Drupal\Tests\taxonomy\Traits\TaxonomyTestTrait');
}


/**
 * Tests Core entity population and image stamping.
 *
 * @group fillpdf
 */
class PdfPopulationTest extends FillPdfTestBase {

  use TaxonomyTestTrait;
  /**
   * Modules to enable.
   *
   * The test runner will merge the $modules lists from this class, the class
   * it extends, and so on up the class hierarchy. It is not necessary to
   * include modules in your list that a parent class has already declared.
   *
   * @var string[]
   *
   * @see \Drupal\Tests\BrowserTestBase::installDrupal()
   */
  public static $modules = ['filter', 'taxonomy'];

  /**
   * A test vocabulary.
   *
   * @var \Drupal\taxonomy\Entity\Vocabulary
   */
  protected $testVocabulary;

  /**
   * {@inheritdoc}
   */
  protected function setUp() {
    parent::setUp();

    // Grant additional permissions to the logged-in admin user.
    $existing_user_roles = $this->adminUser->getRoles(TRUE);
    $role_to_modify = Role::load(end($existing_user_roles));
    $this->grantPermissions($role_to_modify, [
      'administer image styles',
      'use text format restricted_html',
    ]);

    $this->testVocabulary = $this->createVocabulary();

    $this->configureFillPdf();
  }

  /**
   * Tests Core entity population and image stamping.
   */
  public function testPdfPopulation() {
    $this->uploadTestPdf('fillpdf_test_v3.pdf');
    $this->assertSession()->pageTextContains('New FillPDF form has been created.');

    // Load the FillPdf Form and add a form-level replacement.
    $fillpdf_form = FillPdfForm::load($this->getLatestFillPdfForm());
    $fillpdf_form->replacements = 'Hello & how are you?|Hello & how is it going?';
    $fillpdf_form->save();

    // Get the field definitions for the form that was created and configure
    // them.
    FillPdfTestBase::mapFillPdfFieldsToEntityFields('node', $fillpdf_form->getFormFields());

    // Create a node to populate the FillPdf Form.
    $node = $this->createNode([
      'title' => 'Hello & how are you?',
      'type' => 'article',
      'body' => [
        [
          'value' => "<p>PDF form fields don't accept <em>any</em> HTML.</p>",
          'format' => 'restricted_html',
        ],
      ],
    ]);

    // Hit the generation route, check the results from the test backend plugin.
    $url = $this->linkManipulator->generateLink([
      'fid' => $fillpdf_form->id(),
      'entity_ids' => ['node' => [$node->id()]],
    ]);
    $this->drupalGet($url);

    // We don't actually care about downloading the fake PDF. We just want to
    // check what happened in the backend.
    $populate_result = $this->container->get('state')
      ->get('fillpdf_test.last_populated_metadata');

    self::assertEquals(
      'Hello & how are you doing?',
      $populate_result['field_mapping']['fields']['TextField1'],
      'PDF is populated with the title of the node with all HTML stripped.'
    );

    self::assertEquals(
      "PDF form fields don't accept any HTML.\n",
      $populate_result['field_mapping']['fields']['TextField2'],
      'PDF is populated with the node body. HTML is stripped but a newline
       replaces the <p> tags.'
    );
  }

  /**
   * Tests sample mapping.
   */
  public function testSamplePdf() {
    $this->uploadTestPdf('fillpdf_test_v3.pdf');

    // Load the FillPdf Form.
    $fillpdf_form = FillPdfForm::load($this->getLatestFillPdfForm());

    // Hit the generation route, check the results from the test backend plugin.
    $url = $this->linkManipulator->generateLink([
      'fid' => $fillpdf_form->id(),
      'sample' => 1,
    ]);
    $this->drupalGet($url);

    // We don't actually care about downloading the fake PDF. We just want to
    // check what happened in the backend.
    $populate_result = $this->container->get('state')
      ->get('fillpdf_test.last_populated_metadata');

    self::assertEquals(
      '<TextField1>',
      $populate_result['field_mapping']['fields']['TextField1'],
      'Sample field mapped properly.'
    );
  }

  /**
   * Tests Core image stamping.
   */
  public function testImageStamping() {
    $this->uploadTestPdf('fillpdf_test_v3.pdf');
    $this->assertSession()->pageTextContains('New FillPDF form has been created.');
    $fillpdf_form = FillPdfForm::load($this->getLatestFillPdfForm());

    $testCases = [
      'node' => 'article',
      'taxonomy_term' => $this->testVocabulary->id(),
      'user' => 'user',
    ];
    foreach ($testCases as $entity_type => $bundle) {
      $this->createImageField('field_fillpdf_test_image', $entity_type, $bundle);

      $storage = \Drupal::entityTypeManager()->getStorage($entity_type);
      $entity = $storage->load($this->createImageFieldEntity(
        $this->testImage,
        'field_fillpdf_test_image',
        $entity_type,
        $bundle,
        'FillPDF Test image'
      ));

      // Get the field definitions for the form that was created and configure
      // them.
      FillPdfTestBase::mapFillPdfFieldsToEntityFields($entity_type, $fillpdf_form->getFormFields());

      // Hit the generation route, check results from the test backend plugin.
      $url = $this->linkManipulator->generateLink([
        'fid' => $fillpdf_form->id(),
        'entity_ids' => [$entity_type => [$entity->id()]],
      ]);
      $this->drupalGet($url);

      // We don't actually care about downloading the fake PDF. We just want to
      // check what happened in the backend.
      $populate_result = $this->container->get('state')
        ->get('fillpdf_test.last_populated_metadata');

      $file = File::load($entity->field_fillpdf_test_image->target_id);

      self::assertArrayHasKey('ImageField', $populate_result['field_mapping']['images'], "$entity_type isn't populated with an image.");
      self::assertEquals(
        $populate_result['field_mapping']['images']['ImageField']['data'],
        base64_encode(file_get_contents($file->getFileUri())),
        'Encoded image matches known image.'
      );

      $path_info = pathinfo($file->getFileUri());
      $expected_file_hash = md5($path_info['filename']) . '.' . $path_info['extension'];
      self::assertEquals(
        $populate_result['field_mapping']['images']['ImageField']['filenamehash'],
        $expected_file_hash,
        'Hashed filename matches known hash.'
      );

      self::assertEquals(
        $populate_result['field_mapping']['fields']['ImageField'],
        "{image}{$file->getFileUri()}",
        'URI in metadata matches expected URI.'
      );
    }
  }

  /**
   * Test plugin APIs directly to make sure third-party consumers can use them.
   */
  public function testPluginApi() {
    $this->uploadTestPdf('fillpdf_test_v3.pdf');
    $fillpdf_form = FillPdfForm::load($this->getLatestFillPdfForm());

    // Get the field definitions from the actually created form and sort.
    $actual_keys = [];
    foreach (array_keys($fillpdf_form->getFormFields()) as $pdf_key) {
      $actual_keys[] = $pdf_key;
    }
    sort($actual_keys);

    // Get the fields from the fixture and sort.
    $expected_keys = [];
    foreach (TestFillPdfBackend::getParseResult() as $expected_field) {
      $expected_keys[] = $expected_field['name'];
    }
    sort($expected_keys);

    // Now compare. InputHelper::attachPdfToForm() filtered out the duplicate,
    // so the count differs, but not the actual values.
    $this->assertCount(5, $expected_keys);
    $this->assertCount(4, $actual_keys);
    $differences = array_diff($expected_keys, $actual_keys);
    self::assertEmpty($differences, 'Parsed fields are in fixture match.');

    // Now create an instance of the backend service and test directly.
    /** @var \Drupal\fillpdf_test\Plugin\BackendService\Test $backend_service */
    $backend_service = $this->backendServiceManager->createInstance('test');
    $original_pdf = file_get_contents($this->getTestPdfPath('fillpdf_test_v3.pdf'));

    // Get the fields from the backend service and sort.
    $actual_keys = [];
    foreach ($backend_service->parse($original_pdf) as $parsed_field) {
      $actual_keys[] = $parsed_field['name'];
    }
    sort($actual_keys);

    // Compare the values.
    $this->assertCount(5, $expected_keys);
    $this->assertCount(5, $actual_keys);
    $differences = array_diff($expected_keys, $actual_keys);
    self::assertEmpty($differences, 'Parsed fields from plugin are in fixture match.');

    // Test the merge method. We'd normally pass in values for $webform_fields
    // and $options, but since this is a stub anyway, there isn't much point.
    // @todo: Test deeper using the State API.
    $merged_pdf = $backend_service->merge($original_pdf, [
      'Foo' => new TextFieldMapping('bar'),
      'Foo2' => new TextFieldMapping('bar2'),
      'Image1' => new ImageFieldMapping(file_get_contents($this->testImage->getFileUri()), 'png'),
    ], []);
    self::assertEquals($original_pdf, $merged_pdf);

    $merge_state = $this->container->get('state')
      ->get('fillpdf_test.last_populated_metadata');

    // Check that fields are set as expected.
    self::assertInstanceOf(TextFieldMapping::class, $merge_state['field_mapping']['Foo'], 'Field "Foo" was mapped to a TextFieldMapping object.');
    self::assertInstanceOf(TextFieldMapping::class, $merge_state['field_mapping']['Foo2'], 'Field "Foo2" was mapped to a TextFieldMapping object.');
    self::assertInstanceOf(ImageFieldMapping::class, $merge_state['field_mapping']['Image1'], 'Field "Image1" was mapped to an ImageFieldMapping object.');
  }

  /**
   * Tests that merging with the backend proxy works.
   */
  public function testProxyMerge() {
    $this->uploadTestPdf('fillpdf_test_v3.pdf');
    $fillpdf_form = FillPdfForm::load($this->getLatestFillPdfForm());

    // Instantiate the backend proxy (which uses the configured backend).
    /** @var \Drupal\fillpdf\Service\BackendProxyInterface $merge_proxy */
    $merge_proxy = $this->container->get('fillpdf.backend_proxy');

    $original_pdf = file_get_contents($this->getTestPdfPath('fillpdf_test_v3.pdf'));

    FillPdfTestBase::mapFillPdfFieldsToEntityFields('node', $fillpdf_form->getFormFields());

    // Create a node to populate the FillPdf Form.
    // The content of this node is not important; we just need an entity to
    // pass.
    $node = $this->createNode([
      'title' => 'Hello & how are you?',
      'type' => 'article',
      'body' => [
        [
          'value' => "<p>PDF form fields don't accept <em>any</em> HTML.</p>",
          'format' => 'restricted_html',
        ],
      ],
    ]);
    $entities['node'] = [$node->id() => $node];

    // Test merging via the proxy.
    $merged_pdf = $merge_proxy->merge($fillpdf_form, $entities);
    self::assertEquals($original_pdf, $merged_pdf);

    $merge_state = $this->container->get('state')
      ->get('fillpdf_test.last_populated_metadata');
    self::assertInternalType('array', $merge_state, 'Test backend was used.');
    self::assertArrayHasKey('field_mapping', $merge_state, 'field_mapping key from test backend is present.');
    self::assertArrayHasKey('context', $merge_state, 'context key from test backend is present.');

    // These are not that important. They just work because of other tests.
    // We're just testing that token replacement works in general, not the
    // details of it. We have other tests for that.
    self::assertEquals('Hello & how are you doing?', $merge_state['field_mapping']['fields']['TextField1']);
    self::assertEquals("PDF form fields don't accept any HTML.\n", $merge_state['field_mapping']['fields']['TextField2']);
  }

  /**
   * Tests PDF population using local service.
   *
   * @throws \PHPUnit_Framework_SkippedTestError
   * @throws \Behat\Mink\Exception\ResponseTextException
   *   Thrown when test had to be skipped as FillPDF LocalServer is not
   *   available.
   *
   * @see \Drupal\Tests\fillpdf\Traits\TestFillPdfTrait::configureLocalServiceBackend()
   */
  public function testMergeLocalService() {
    // For local container testing, we require the Docker container to be
    // running. If LocalServer's /ping endpoint does not return a 200, we assume
    // that we're not in an environment where we can run this
    // test.
    $this->configureLocalServiceBackend();
    $config = $this->container->get('config.factory')->get('fillpdf.settings');
    if (!FillPdf::checkLocalServiceEndpoint($this->container->get('http_client'), $config)) {
      throw new \PHPUnit_Framework_SkippedTestError('FillPDF LocalServer unavailable, so skipping test.');
    }
    $this->backendTest();
  }

  /**
   * Tests PDF population using a local install of pdftk.
   *
   * @throws \PHPUnit_Framework_SkippedTestError
   * @throws \Behat\Mink\Exception\ResponseTextException
   *   Thrown when test had to be skipped as local pdftk install is not
   *   available.
   */
  public function testMergePdftk() {
    $this->configureFillPdf(['backend' => 'pdftk']);
    if (!FillPdf::checkPdftkPath()) {
      throw new \PHPUnit_Framework_SkippedTestError('pdftk not available, so skipping test.');
    }
    $this->backendTest();
  }

  /**
   * Tests a backend.
   *
   * @throws \Behat\Mink\Exception\ResponseTextException
   */
  protected function backendTest() {
    // If we can upload a PDF, parsing is working.
    // Test with a node.
    $this->uploadTestPdf('fillpdf_Ŧäßð_v3â.pdf');
    $fillpdf_form = FillPdfForm::load($this->getLatestFillPdfForm());

    // Get the field definitions for the form that was created and configure
    // them.
    $fields = $fillpdf_form->getFormFields();
    FillPdfTestBase::mapFillPdfFieldsToEntityFields('node', $fields);

    // Set up a test node.
    $node = $this->createNode([
      'title' => 'Test',
      'type' => 'article',
    ]);

    // Hit the generation route, check the results from the test backend plugin.
    $url = $this->linkManipulator->generateLink([
      'fid' => $fillpdf_form->id(),
      'entity_ids' => ['node' => [$node->id()]],
    ]);
    $this->drupalGet($url);

    // Check if what we're seeing really is a PDF file.
    $maybe_pdf = $this->getSession()->getPage()->getContent();
    static::assertEquals('application/pdf', $this->getMimeType($maybe_pdf));

    $this->drupalGet('<front>');
    $this->assertSession()->pageTextNotContains('Merging the FillPDF Form failed');
  }

}
