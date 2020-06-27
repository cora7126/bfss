<?php

namespace Drupal\iframe\Plugin\Field\FieldWidget;

use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Field\WidgetBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Component\Utility\UrlHelper;

/**
 * Plugin implementation base functions.
 */
class IframeWidgetBase extends WidgetBase {

  /**
   * Allowed editable attributes of iframe field on node-edit.
   *
   * @var array
   */
  public $allowedAttributes = [
    'title' => 1,
    'width' => 1,
    'height' => 1,
    'url' => 1,
  ];

  /**
   * {@inheritdoc}
   */
  public static function defaultSettings() {
    return [
      // Here if *own* default value not the one from edit-type-field.
    ] + parent::defaultSettings();
  }

  /**
   * Translate the description for iframe width/height only once.
   */
  protected static function getSizedescription() {
    return t('The iframe\'s width and height can be set in pixels as a number only ("500" for 500 pixels) or in a percentage value followed by the percent symbol (%) ("50%" for 50 percent).');
  }

  /**
   * It is {@inheritdoc}.
   *
   * Used : at "Manage form display" after work-symbol.
   */
  public function settingsForm(array $form, FormStateInterface $form_state) {
    /* Settings form after "manage form display" page, valid for one content type */
    $field_settings = $this->getFieldSettings();
    $settings = $this->getSettings();
    $values = [];
    $values = $settings + $field_settings + self::defaultSettings();
    // \iframe_debug(0, 'manage settingsForm field_settings', $field_settings);
    // \iframe_debug(0, 'manage settingsForm settings', $settings);
    // \iframe_debug(0, 'manage settingsForm values', $values);
    $element['width'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Iframe Width'),
      // ''
      '#default_value' => isset($values['width']) ? $values['width'] : '',
      '#description' => self::getSizedescription(),
      '#maxlength' => 4,
      '#size' => 4,
    ];
    $element['height'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Iframe Height'),
      // ''
      '#default_value' => isset($values['height']) ? $values['height'] : '',
      '#description' => self::getSizedescription(),
      '#maxlength' => 4,
      '#size' => 4,
    ];
    $element['class'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Additional CSS Class'),
      // ''
      '#default_value' => isset($values['class']) ? $values['class'] : '',
      '#description' => $this->t('When output, this iframe will have this class attribute. Multiple classes should be separated by spaces.'),
    ];
    $element['expose_class'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Expose Additional CSS Class'),
      // 0
      '#default_value' => isset($values['expose_class']) ? $values['expose_class'] : '',
      '#description' => $this->t('Allow author to specify an additional class attribute for this iframe.'),
    ];
    $element['frameborder'] = [
      '#type' => 'select',
      '#title' => $this->t('Frameborder'),
      '#options' => ['0' => $this->t('No frameborder'), '1' => $this->t('Show frameborder')],
      // 0
      '#default_value' => isset($values['frameborder']) ? $values['frameborder'] : '0',
      '#description' => $this->t('Frameborder is the border around the iframe. Most people want it removed, so the default value for frameborder is zero (0), or no border.'),
    ];
    $element['scrolling'] = [
      '#type' => 'select',
      '#title' => $this->t('Scrolling'),
      '#options' => [
        'auto' => $this->t('Automatic'),
        'no' => $this->t('Disabled'),
        'yes' => $this->t('Enabled'),
      ],
      // 'auto'
      '#default_value' => isset($values['scrolling']) ? $values['scrolling'] : 'auto',
      '#description' => $this->t('Scrollbars help the user to reach all iframe content despite the real height of the iframe content. Please disable it only if you know what you are doing.'),
    ];
    $element['transparency'] = [
      '#type' => 'select',
      '#title' => $this->t('Transparency'),
      '#options' => [
        '0' => $this->t('No transparency'),
        '1' => $this->t('Allow transparency'),
      ],
      // 0
      '#default_value' => isset($values['transparency']) ? $values['transparency'] : '0',
      '#description' => $this->t('Allow transparency per CSS in the outer iframe tag. You have to set background-color:transparent in your iframe body tag too!'),
    ];
    $element['tokensupport'] = [
      '#type' => 'select',
      '#title' => $this->t('Token Support'),
      '#options' => [
        '0' => $this->t('No tokens allowed'),
        '1' => $this->t('Tokens only in title field'),
        '2' => $this->t('Tokens for title and URL field'),
      ],
      // 0
      '#default_value' => isset($values['tokensupport']) ? $values['tokensupport'] : '0',
      '#description' => $this->t('Are tokens allowed for users to use in title or URL field?'),
    ];
    $element['allowfullscreen'] = [
      '#type' => 'select',
      '#title' => $this->t('Allow fullscreen'),
      '#options' => [
        '0' => $this->t('false'),
        '1' => $this->t('true'),
      ],
      // 0
      '#default_value' => isset($values['allowfullscreen']) ? $values['allowfullscreen'] : '0',
      '#description' => $this->t('Allow fullscreen for iframe. The iframe can activate fullscreen mode by calling the requestFullscreen() method.'),
    ];

    if (!\Drupal::moduleHandler()->moduleExists('token')) {
      $element['tokensupport']['#description'] .= ' ' . $this->t('Attention: Token module is not currently enabled!');
    }
    return $element;
  }

  /**
   * {@inheritdoc}
   */
  public function settingsSummary() {
    $field_settings = $this->getFieldSettings();
    $settings = $this->getSettings() + $field_settings;
    /* summary on the "manage display" page, valid for one content type */
    $summary = [];

    $summary[] = $this->t('Iframe default width: @width', ['@width' => $settings['width']]);
    $summary[] = $this->t('Iframe default height: @height', ['@height' => $settings['height']]);
    $summary[] = $this->t('Iframe default frameborder: @frameborder', ['@frameborder' => $settings['frameborder']]);
    $summary[] = $this->t('Iframe default scrolling: @scrolling', ['@scrolling' => $settings['scrolling']]);

    return $summary;
  }

  /**
   * It is {@inheritdoc}.
   *
   * Used: (1) at admin edit fields.
   *
   * Used: (2) at add-story for creation content.
   */
  public function formElement(FieldItemListInterface $items, $delta, array $element, array &$form, FormStateInterface $form_state) {
    // 1) Shows the "default fields" in the edit-type-field page
    // -- (on_admin_page = true).
    // 2) Edit-fields on the article-edit-page (on_admin_page = false).
    // Global settings.
    $field_settings = $this->getFieldSettings();
    $settings = $this->getSettings() + $field_settings + self::defaultSettings();
    /** @var \Drupal\iframe\Plugin\Field\FieldType\IframeItem $item */
    $item =& $items[$delta];
    $field_definition = $item->getFieldDefinition();
    $on_admin_page = isset($element['#field_parents'][0]) && ('default_value_input' == $element['#field_parents'][0]);
    $is_new = $item->getEntity()->isNew();
    // \iframe_debug(0, 'add-story formElement field_setting', $field_settings);
    // \iframe_debug(0, 'add-story formElement setting', $settings);
    $values = $item->toArray();

    if ($is_new || $on_admin_page) {
      foreach ($values as $vkey => $vval) {
        if ($vval !== NULL && $vval !== '') {
          $settings[$vkey] = $vval;
        }
      }
    }
    else {
      if (isset($settings['expose_class']) && $settings['expose_class']) {
        $this->allowedAttributes['class'] = 1;
      }
      foreach ($this->allowedAttributes as $attribute => $attrAllowed) {
        if ($attrAllowed) {
          $settings[$attribute] = $values[$attribute];
        }
      }
    }
    // \iframe_debug(0, 'add-story formElement final setting', $settings);
    foreach ($settings as $attribute => $attrValue) {
      $item->setValue($attribute, $attrValue);
    }

    $element += [
      '#type' => 'fieldset',
      '#collapsible' => TRUE,
      '#open' => TRUE,
    ];
    if (!$on_admin_page) {
      $element['#title'] = $field_definition->getLabel();
      $element['description'] = [
        '#type' => 'item',
        '#description' => $field_definition->getDescription(),
        '#weight' => 0,
      ];
    }

    $element['title'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Iframe Title'),
      '#placeholder' => '',
      '#default_value' => $settings['title'],
      '#size' => 80,
      '#maxlength' => 1024,
      '#weight' => 2,
      // '#element_validate' => array('text'),
    ];

    $element['url'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Iframe URL'),
      '#placeholder' => 'https://',
      '#default_value' => isset($settings['url']) ? $settings['url'] : '',
      '#size' => 80,
      '#maxlength' => 1024,
      '#weight' => 1,
      '#element_validate' => [[get_class($this), 'validateUrl']],
    ];

    $element['width'] = [
      '#title' => $this->t('Iframe Width'),
      '#type' => 'textfield',
      '#default_value' => isset($settings['width']) ? $settings['width'] : '',
      '#description' => self::getSizedescription(),
      '#maxlength' => 4,
      '#size' => 4,
      '#weight' => 3,
      '#element_validate' => [[get_class($this), 'validateWidth']],
    ];
    $element['height'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Iframe Height'),
      '#default_value' => isset($settings['height']) ? $settings['height'] : '',
      '#description' => self::getSizedescription(),
      '#maxlength' => 4,
      '#size' => 4,
      '#weight' => 4,
      '#element_validate' => [[get_class($this), 'validateHeight']],
    ];
    if (isset($settings['expose_class']) && $settings['expose_class']) {
      $element['class'] = [
        '#type' => 'textfield',
        '#title' => $this->t('Additional CSS Class'),
        // ''
        '#default_value' => $settings['class'],
        '#description' => $this->t('When output, this iframe will have this class attribute. Multiple classes should be separated by spaces.'),
        '#weight' => 5,
      ];
    }
    // \iframe_debug(0, 'formElement', $element);
    return $element;
  }

  /**
   * Validate width(if minimum url is defined)
   *
   * @see \Drupal\Core\Form\FormValidator
   */
  public static function validateWidth(&$form, FormStateInterface &$form_state) {
    $parents = $form['#parents'];
    $itemfield = $parents[0];
    $iteminst = $parents[1];
    /*
     * $value = $form['#value'];
     * $itemname = $parents[2];
     * $itemid = $form['#id'];
     */
    $node = $form_state->getUserInput();
    $me = $node[$itemfield][$iteminst];
    // \iframe_debug(0, 'validateWidth', $me);
    if (!empty($me['url']) && isset($me['width'])) {
      if (empty($me['width']) || !preg_match('#^(\d+\%?|auto)$#', $me['width'])) {
        $form_state->setError($form, self::getSizedescription());
      }
    }
  }

  /**
   * Validate height (if minimum url is defined)
   *
   * @see \Drupal\Core\Form\FormValidator
   */
  public static function validateHeight(&$form, FormStateInterface &$form_state) {
    $parents = $form['#parents'];
    $itemfield = $parents[0];
    $iteminst = $parents[1];
    /*
     * $value = $form['#value'];
     * $itemname = $parents[2];
     * $itemid = $form['#id'];
     */
    $node = $form_state->getUserInput();
    $me = $node[$itemfield][$iteminst];
    // \iframe_debug(0, 'validateHeight', $me);
    if (!empty($me['url']) && isset($me['height'])) {
      if (empty($me['height']) || !preg_match('#^(\d+\%?|auto)$#', $me['height'])) {
        $form_state->setError($form, self::getSizedescription());
      }
    }
  }

  /**
   * Validate url.
   *
   * @see \Drupal\Core\Form\FormValidator
   */
  public static function validateUrl(&$form, FormStateInterface &$form_state) {
    $parents = $form['#parents'];
    $itemfield = $parents[0];
    $iteminst = $parents[1];
    /*
     * $value = $form['#value'];
     * $itemname = $parents[2];
     * $itemid = $form['#id'];
     */
    $node = $form_state->getUserInput();
    $me = $node[$itemfield][$iteminst];
    // \iframe_debug(0, 'validateUrl', $me);
    if (!empty($me['url'])) {
      if (!UrlHelper::isValid($me['url'])) {
        $form_state->setError($form, t('Invalid syntax for "Iframe URL".'));
      }
      elseif (strpos($me['url'], '//') === 0) {
        $form_state->setError($form, t('Drupal does not accept scheme-less URLs. Please add "https:" to your URL, this works on http-parent-pages too.'));
      }
    }
  }

  /**
   * {@inheritdoc}
   */
  public function massageFormValues(array $values, array $form, FormStateInterface $form_state) {
    // Global values.
    $field_settings = $this->getFieldSettings();
    $settings = $this->getSettings() + $field_settings;

    if (isset($settings['expose_class']) && $settings['expose_class']) {
      $this->allowedAttributes['class'] = 1;
    }

    // \iframe_debug(0, __METHOD__ . ' settings', $settings);
    // \iframe_debug(0, __METHOD__ . ' old-values', $values);
    foreach ($values as $delta => $value) {
      /*
       * Validate that all keys are available,
       * in the user-has-only-some-values case too.
       */
      $testvalue = $value + $settings;
      $newvalue = [];

      foreach ($testvalue as $key => $val) {
        if (
          isset($this->allowedAttributes[$key])
          && $this->allowedAttributes[$key]
        ) {
          $newvalue[$key] = $val;
        }
        elseif (isset($settings[$key])) {
          $newvalue[$key] = $settings[$key];
        }
        else {
          $newvalue[$key] = $val;
        }
      }
      if (!empty($settings['class']) && !strstr($newvalue['class'])) {
        $newvalue['class'] = trim(implode(" ", [$settings['class'], $newvalue['class']]));
      }
      $new_values[$delta] = $newvalue;
    }
    // \iframe_debug(0, __METHOD__ . ' new-values', $new_values);
    return $new_values;
  }

}
