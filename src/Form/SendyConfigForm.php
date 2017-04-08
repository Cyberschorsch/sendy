<?php

namespace Drupal\sendy\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Class SendyConfigForm.
 *
 * @package Drupal\sendy\Form
 */
class SendyConfigForm extends ConfigFormBase {

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return [
      'sendy.sendyconfig',
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'sendy_config_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = $this->config('sendy.sendyconfig');
    $form['newsletter_url'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Newsletter URL'),
      '#description' => $this->t('Enter the URL of the sendy newsletter service'),
      '#maxlength' => 255,
      '#size' => 64,
      '#default_value' => $config->get('newsletter_url'),
    ];
    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    parent::validateForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    parent::submitForm($form, $form_state);

    $this->config('sendy.sendyconfig')
      ->set('newsletter_url', $form_state->getValue('newsletter_url'))
      ->save();
  }

}
