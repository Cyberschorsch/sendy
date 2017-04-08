<?php

namespace Drupal\sendy\Plugin\WebformHandler;

use Drupal\Core\Form\FormStateInterface;
use Drupal\webform\WebformHandlerBase;
use Drupal\webform\WebformSubmissionInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\sendy\SendySubscribe;

/**
 * Form submission to Sendy.co handler.
 *
 * @WebformHandler(
 *   id = "sendy",
 *   label = @Translation("Sendy.co"),
 *   category = @Translation("Sendy"),
 *   description = @Translation("Sends a form submission to sendy.co ."),
 *   cardinality = \Drupal\webform\WebformHandlerInterface::CARDINALITY_UNLIMITED,
 *   results = \Drupal\webform\WebformHandlerInterface::RESULTS_PROCESSED,
 * )
 */
class WebformSendyHandler extends WebformHandlerBase {

  /**
   * {@inheritdoc}
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, LoggerInterface $logger) {
    parent::__construct($configuration, $plugin_id, $plugin_definition, $logger);
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('logger.factory')->get('webform.sendy')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function defaultConfiguration() {
    return [
      'list' => '',
      'email' => '',
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function buildConfigurationForm(array $form, FormStateInterface $form_state) {
    $lists = _sendy_get_newsletter_lists();
    $options = [];
    foreach ($lists as $id => $list) {
      $options[$id] = $list->getName() . ': ' . $list->field_list_id->value;
    }
    $form['list'] = [
      '#type' => 'select',
      '#title' => $this->t('List'),
      '#required' => TRUE,
      '#default_value' => $this->configuration['list'],
      '#options' => $options,
    ];

    $fields = $this->getWebform()->getElementsDecoded();
    $options = array();
    $options[''] = $this->t('- Select an option -');
    foreach ($fields as $field_name => $field) {
      if ($field['#type'] == 'email') {
        $options[$field_name] = $field['#title'];
      }
    }

    $form['email'] = [
      '#type' => 'select',
      '#title' => $this->t('Email field'),
      '#required' => TRUE,
      '#default_value' => $this->configuration['email'],
      '#options' => $options,
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function submitConfigurationForm(array &$form, FormStateInterface $form_state) {
    parent::submitConfigurationForm($form, $form_state);
    $values = $form_state->getValues();
    foreach ($this->configuration as $name => $value) {
      if (isset($values[$name])) {
        $this->configuration[$name] = $values[$name];
      }
    }
  }

  /**
   * {@inheritdoc}
   */
  public function postSave(WebformSubmissionInterface $webform_submission, $update = TRUE) {
    if (!$update) {
      $fields = $webform_submission->toArray(TRUE);
      $sendFields = array();
      foreach ($fields['data'] as $field_name => $field) {
        $sendFields[strtoupper($field_name)] = $field;
      }
      $email = $fields['data'][$this->configuration['email']];
      $newsletter = \Drupal::entityTypeManager()->getStorage('newsletter_list')->load($this->configuration['list']);
      $config = \Drupal::config('sendy.sendyconfig');
      $newsletter_url = $config->get('newsletter_url');
      $newsletter_id = $newsletter->field_list_id->value;

      $sendySubscribe = new SendySubscribe($newsletter_url);
      $sendySubscribe->setListId($newsletter_id);
      $status = $sendySubscribe->subscribe('John Doe', $email);
      drupal_set_message(t('Thank you for subscribing to our newsletter.'));
    }
  }
}