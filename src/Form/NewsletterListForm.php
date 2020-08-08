<?php

namespace Drupal\sendy\Form;

use Drupal\Core\Entity\ContentEntityForm;
use Drupal\Core\Form\FormStateInterface;

/**
 * Form controller for Sendy Newsletter List edit forms.
 *
 * @ingroup sendy
 */
class NewsletterListForm extends ContentEntityForm {

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    /* @var $entity \Drupal\sendy\Entity\NewsletterList */
    $form = parent::buildForm($form, $form_state);

    $entity = $this->entity;

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function save(array $form, FormStateInterface $form_state) {
    $entity = &$this->entity;

    $status = parent::save($form, $form_state);

    switch ($status) {
      case SAVED_NEW:
        \Drupal::service('messenger')->addMessage($this->t('Created the %label Sendy Newsletter List.', [
          '%label' => $entity->label(),
        ]));
        break;

      default:
        \Drupal::service('messenger')->addMessage($this->t('Saved the %label Sendy Newsletter List.', [
          '%label' => $entity->label(),
        ]));
    }
    $form_state->setRedirect('entity.newsletter_list.canonical', ['newsletter_list' => $entity->id()]);
  }

}
