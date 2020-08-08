<?php

namespace Drupal\sendy;

use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Entity\EntityListBuilder;
use Drupal\Core\Url;
use Drupal\Core\Link;

/**
 * Defines a class to build a listing of Sendy Newsletter List entities.
 *
 * @ingroup sendy
 */
class NewsletterListListBuilder extends EntityListBuilder {

  /**
   * {@inheritdoc}
   */
  public function buildHeader() {
    $header['id'] = $this->t('Sendy Newsletter List ID');
    $header['name'] = $this->t('Name');
    return $header + parent::buildHeader();
  }

  /**
   * {@inheritdoc}
   */
  public function buildRow(EntityInterface $entity) {
    /* @var $entity \Drupal\sendy\Entity\NewsletterList */
    $row['id'] = $entity->id();
    $row['name'] = $this->l(
      $entity->label(),
      new Url(
        'entity.newsletter_list.edit_form', array(
          'newsletter_list' => $entity->id(),
        )
      )
    );
    return $row + parent::buildRow($entity);
  }

}
