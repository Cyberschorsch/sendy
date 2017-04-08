<?php

namespace Drupal\sendy\Plugin\Field\FieldFormatter;

use Drupal\Component\Utility\Html;
use Drupal\Core\Field\FieldItemInterface;
use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Field\FormatterBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Plugin implementation of the 'sendy_list_formatter' formatter.
 *
 * @FieldFormatter(
 *   id = "sendy_list_formatter",
 *   label = @Translation("Sendy list formatter"),
 *   field_types = {
 *     "string"
 *   }
 * )
 */
class SendyListFormatter extends FormatterBase {

  /**
   * {@inheritdoc}
   */
  public static function defaultSettings() {
    $defaults = [
      'include_name' => TRUE,
    ] + parent::defaultSettings();
    return $defaults;
  }

  /**
   * {@inheritdoc}
   */
  public function settingsForm(array $form, FormStateInterface $form_state) {
    $form = parent::settingsForm($form, $form_state);
    $form['include_name'] = [
      '#type' => 'checkbox',
      '#title' => t('Include name field?'),
      '#default_value' => $this->getSetting('include_name'),
    ];
    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function settingsSummary() {
    $summary = [];
    // Implement settings summary.
    $summary[] = t('Set available fields.');
    return $summary;
  }

  /**
   * {@inheritdoc}
   */
  public function viewElements(FieldItemListInterface $items, $langcode) {
    $elements = [];
    $include_name = $this->getSetting('include_name');
    foreach ($items as $delta => $item) {
      $elements[$delta] = \Drupal::formBuilder()->getForm('Drupal\sendy\Form\SendySubscribeForm', $item->value, $include_name );
    }

    return $elements;
  }

  /**
   * Generate the output appropriate for one field item.
   *
   * @param \Drupal\Core\Field\FieldItemInterface $item
   *   One field item.
   *
   * @return string
   *   The textual output generated.
   */
  protected function viewValue(FieldItemInterface $item) {
    // The text value has no text format assigned to it, so the user input
    // should equal the output, including newlines.
    return nl2br(Html::escape($item->value));
  }

}
