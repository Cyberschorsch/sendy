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
    $form['newsletter_callback_url'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Newsletter Callback URL'),
      '#description' => $this->t('Enter the URL where the user should be redirected after submission.'),
      '#default_value' => $config->get('newsletter_callback_url'),
    ];
    $form['api_key'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Sendy API Key'),
      '#description' => $this->t('Enter the api key for your sendy installation.'),
      '#default_value' => $config->get('api_key'),
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
      ->set('newsletter_callback_url', $form_state->getValue('newsletter_callback_url'))
      ->set('api_key', $form_state->getValue('api_key'))
      ->save();
  }

}
