<?php

namespace Drupal\sendy;

use SendyPHP\Sendy;

/**
 * Class SendySubscribe.
 *
 * @package Drupal\sendy
 */
class SendySubscribe {

  private $sendy_url;
  private $list_id;
  private $sendy;
  private $api_key;
  /**
   * Constructor.
   */
  public function __construct($sendy_url, $api_key) {
    $this->setSendyUrl($sendy_url);
    $this->sendy = new Sendy($sendy_url, $api_key);
  }

  public function subscribe($name, $email, $additional_parameters = '') {
    $status =$this->sendy->subscribe($this->getListId(), $email , empty($name) ? 'John Doe' : $name, $additional_parameters);
    return $status;
  }

  public function checkSubStatus($email, $list) {
    return $this->sendy->getSubscriptionStatus($list, $email);
  }

  /**
   * @return mixed
   */
  public function getSendyUrl() {
    return $this->sendy_url;
  }

  /**
   * @param mixed $sendy_url
   */
  public function setSendyUrl($sendy_url) {
    $this->sendy_url = $sendy_url;
  }

  /**
   * @return mixed
   */
  public function getListId() {
    return $this->list_id;
  }

  /**
   * @param mixed $list_id
   */
  public function setListId($list_id) {
    $this->list_id = $list_id;
  }

  /**
   * @return mixed
   */
  public function getApiKey() {
    return $this->api_key;
  }

  /**
   * @param mixed $api_key
   */
  public function setApiKey($api_key) {
    $this->api_key = $api_key;
  }






}
