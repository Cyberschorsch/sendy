# About this module

This modules integrates the sendy.co newsletter service with Drupal 8. 

Current features: 

- Provides a newsletter list entity type for creating multiple newsletter lists
- Provides a block type for creating custom newsletter blocks for your newsletter lists
- Integrates Honeypot module
- Provides a webform handler
- Populate a segment field in webform for assigning users to segments in sendy

Currently not supported (but maybe later):

- Other custom fields from sendy.co

# Requirements

A working sendy.co installation.

# Installation

Install with composer to download the library.

# Assign users to segments.

In your webform, create a hidden field with the key 'segment' and configure this field to be used 
in the sendy handler configuration. You may use the prepopulate feature of webform to populate 
the hidden field with a value or use a fixed value in the element configuration.
