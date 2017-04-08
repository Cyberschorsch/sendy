<?php

namespace Drupal\sendy\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use SendyPHP\Sendy;

/**
 * Class SendySubscribeForm.
 *
 * @package Drupal\sendy\Form
 */
class SendySubscribeForm extends FormBase {


  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'sendy_subscribe_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state, $newsletter_list = NULL, $include_name = NULL) {
    $form['name'] = [
      '#type' => empty($include_name) ? 'hidden' : 'textfield',
      '#title' => $this->t('Name'),
      '#maxlength' => 255,

    ];
    $form['email_adress'] = [
      '#type' => 'email',
      '#title' => $this->t('Email Adress'),
      '#required' => TRUE,
      '#attributes' => [
        'placeholder' => t('Enter your email adress'),
      ],
    ];
    $form['newsletter_id'] = [
      '#type' => 'hidden',
      '#value' => $newsletter_list,
    ];
    $form['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Subscribe to newsletter'),
      '#attributes' => [
        'class' => ['newsletter-submit'],
      ],
    ];

    return $form;
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
    $config = \Drupal::config('sendy.sendyconfig');
    $newsletter_url = $config->get('newsletter_url');
    $name = $form_state->getValue('name');
    $email = $form_state->getValue('email_adress');
    $newsletter_id = $form_state->getValue('newsletter_id');
    $sendySubscribe = new SendySubscribe($newsletter_url);
    $sendySubscribe->setListId($newsletter_id);
    $status = $sendySubscribe->subscribe($name, $email);
    drupal_set_message(t('Thank you for subscribing to our newsletter.'));
  }

}
