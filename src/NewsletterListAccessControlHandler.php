<?php

namespace Drupal\sendy;

use Drupal\Core\Entity\EntityAccessControlHandler;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Access\AccessResult;

/**
 * Access controller for the Sendy Newsletter List entity.
 *
 * @see \Drupal\sendy\Entity\NewsletterList.
 */
class NewsletterListAccessControlHandler extends EntityAccessControlHandler {

  /**
   * {@inheritdoc}
   */
  protected function checkAccess(EntityInterface $entity, $operation, AccountInterface $account) {
    /** @var \Drupal\sendy\Entity\NewsletterListInterface $entity */
    switch ($operation) {
      case 'view':
        if (!$entity->isPublished()) {
          return AccessResult::allowedIfHasPermission($account, 'view unpublished sendy newsletter list entities');
        }
        return AccessResult::allowedIfHasPermission($account, 'view published sendy newsletter list entities');

      case 'update':
        return AccessResult::allowedIfHasPermission($account, 'edit sendy newsletter list entities');

      case 'delete':
        return AccessResult::allowedIfHasPermission($account, 'delete sendy newsletter list entities');
    }

    // Unknown operation, no opinion.
    return AccessResult::neutral();
  }

  /**
   * {@inheritdoc}
   */
  protected function checkCreateAccess(AccountInterface $account, array $context, $entity_bundle = NULL) {
    return AccessResult::allowedIfHasPermission($account, 'add sendy newsletter list entities');
  }

}
