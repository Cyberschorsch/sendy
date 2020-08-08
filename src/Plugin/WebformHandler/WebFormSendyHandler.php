<?php

namespace Drupal\sendy\Plugin\WebformHandler;

use Drupal\Core\Form\FormStateInterface;
use Drupal\webform\Plugin\WebformHandlerBase;
use Drupal\webform\WebformSubmissionInterface;
use Drupal\Core\Logger\LoggerChannelFactoryInterface;
use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\webform\WebformSubmissionConditionsValidatorInterface;
use Drupal\sendy\SendySubscribe;


/**
 * Form submission to Sendy.co handler.
 *
 * @WebformHandler(
 *   id = "sendy",
 *   label = @Translation("Sendy.co"),
 *   category = @Translation("Sendy"),
 *   description = @Translation("Sends a form submission to sendy.co ."),
 *   cardinality = \Drupal\webform\Plugin\WebformHandlerInterface::CARDINALITY_UNLIMITED,
 *   results = \Drupal\webform\Plugin\WebformHandlerInterface::RESULTS_PROCESSED,
 * )
 */
class WebformSendyHandler extends WebformHandlerBase {

  /**
   * {@inheritdoc}
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, LoggerChannelFactoryInterface $logger_factory, ConfigFactoryInterface $config_factory, EntityTypeManagerInterface $entity_type_manager, WebformSubmissionConditionsValidatorInterface $conditions_validator) {
    parent::__construct($configuration, $plugin_id, $plugin_definition, $logger_factory, $config_factory, $entity_type_manager, $conditions_validator);
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('logger.factory'),
      $container->get('config.factory'),
      $container->get('entity_type.manager'),
      $container->get('webform_submission.conditions_validator')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function defaultConfiguration() {
    return [
      'list' => '',
      'email' => '',
      'accept_checkbox' => '',
      'segment' => '',
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
    # Email Options.
    $email_options = array();
    $email_options[''] = $this->t('- Select an option -');
    foreach ($fields as $field_name => $field) {
      if ($field['#type'] == 'email') {
        $email_options[$field_name] = $field['#title'];
      }
    }
    # Checkbox Options.
    $checkbox_options = array();
    $checkbox_options[''] = $this->t('- Select an option -');
    foreach ($fields as $field_name => $field) {
      if ($field['#type'] == 'checkbox') {
        $checkbox_options[$field_name] = $field['#title'];
      }
    }

    # hiddenfield options for segments.
    $hiddenfield = array();
    $hiddenfield[''] = $this->t('- Select an option -');
    foreach ($fields as $field_name => $field) {
      if ($field['#type'] == 'hidden') {
        $hiddenfield[$field_name] = $field['#title'];
      }
    }
    $form['email'] = [
      '#type' => 'select',
      '#title' => $this->t('Email field'),
      '#required' => TRUE,
      '#default_value' => $this->configuration['email'],
      '#options' => $email_options,
    ];
    $form['accept_checkbox'] = [
      '#type' => 'select',
      '#title' => $this->t('Accept Checkbox'),
      '#description' => t('Choose a checkbox field if subscribing should be optional.'),
      '#default_value' => $this->configuration['accept_checkbox'],
      '#options' => $checkbox_options,
    ];
    $form['segment'] = [
      '#type' => 'select',
      '#title' => $this->t('Segment field'),
      '#description' => t('Choose a text field for populating the segment field if configured in sendy'),
      '#default_value' => $this->configuration['segment'],
      '#options' => $hiddenfield,
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
      $accept_checkbox = $fields['data'][$this->configuration['accept_checkbox']];
      if (!$accept_checkbox && !is_null($accept_checkbox)) {
        return;
      }
      $sendFields = array();
      foreach ($fields['data'] as $field_name => $field) {
        $sendFields[strtoupper($field_name)] = $field;
      }
      $email = $fields['data'][$this->configuration['email']];
      $segment = !empty($this->configuration['segment']) ? $fields['data'][$this->configuration['segment']] : '' ;
      $newsletter = \Drupal::entityTypeManager()->getStorage('newsletter_list')->load($this->configuration['list']);
      $config = \Drupal::config('sendy.sendyconfig');
      $newsletter_url = $config->get('newsletter_url');
      $newsletter_id = $newsletter->field_list_id->value;

      $sendySubscribe = new SendySubscribe($newsletter_url, $config->get('api_key'));
      $sendySubscribe->setListId($newsletter_id);
      $status = $sendySubscribe->subscribe('John Doe', $email, ['segment' => $segment]);
      \Drupal::service('messenger')->addMessage(t('Thank you for subscribing to our newsletter.'));
    }
  }
}