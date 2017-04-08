<?php

/**
 * @file
 * Contains newsletter_list.page.inc.
 *
 * Page callback for Sendy Newsletter List entities.
 */

use Drupal\Core\Render\Element;

/**
 * Prepares variables for Sendy Newsletter List templates.
 *
 * Default template: newsletter_list.html.twig.
 *
 * @param array $variables
 *   An associative array containing:
 *   - elements: An associative array containing the user information and any
 *   - attributes: HTML attributes for the containing element.
 */
function template_preprocess_newsletter_list(array &$variables) {
  // Fetch NewsletterList Entity Object.
  $newsletter_list = $variables['elements']['#newsletter_list'];

  // Helpful $content variable for templates.
  foreach (Element::children($variables['elements']) as $key) {
    $variables['content'][$key] = $variables['elements'][$key];
  }
}
