<?php

namespace Drupal\sendy\Entity;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\Entity\EntityChangedInterface;
use Drupal\user\EntityOwnerInterface;

/**
 * Provides an interface for defining Sendy Newsletter List entities.
 *
 * @ingroup sendy
 */
interface NewsletterListInterface extends  ContentEntityInterface, EntityChangedInterface, EntityOwnerInterface {

  // Add get/set methods for your configuration properties here.

  /**
   * Gets the Sendy Newsletter List name.
   *
   * @return string
   *   Name of the Sendy Newsletter List.
   */
  public function getName();

  /**
   * Sets the Sendy Newsletter List name.
   *
   * @param string $name
   *   The Sendy Newsletter List name.
   *
   * @return \Drupal\sendy\Entity\NewsletterListInterface
   *   The called Sendy Newsletter List entity.
   */
  public function setName($name);

  /**
   * Gets the Sendy Newsletter List creation timestamp.
   *
   * @return int
   *   Creation timestamp of the Sendy Newsletter List.
   */
  public function getCreatedTime();

  /**
   * Sets the Sendy Newsletter List creation timestamp.
   *
   * @param int $timestamp
   *   The Sendy Newsletter List creation timestamp.
   *
   * @return \Drupal\sendy\Entity\NewsletterListInterface
   *   The called Sendy Newsletter List entity.
   */
  public function setCreatedTime($timestamp);

  /**
   * Returns the Sendy Newsletter List published status indicator.
   *
   * Unpublished Sendy Newsletter List are only visible to restricted users.
   *
   * @return bool
   *   TRUE if the Sendy Newsletter List is published.
   */
  public function isPublished();

  /**
   * Sets the published status of a Sendy Newsletter List.
   *
   * @param bool $published
   *   TRUE to set this Sendy Newsletter List to published, FALSE to set it to unpublished.
   *
   * @return \Drupal\sendy\Entity\NewsletterListInterface
   *   The called Sendy Newsletter List entity.
   */
  public function setPublished($published);

}
