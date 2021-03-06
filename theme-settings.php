<?php

/**
 * Implements hook_form_FORM_ID_alter().
 */
function adapt_basetheme_form_system_theme_settings_alter(&$form, &$form_state) {
  // Create own settings
  $basetheme_settings = array(
    '#type' => 'fieldset',
    '#title' => t('Basetheme settings'),

    'js_to_strip' => array(
      '#type' => 'textarea',
      '#title' => t('JS to strip'),
      '#description' => t('Enter the JS files you wish to strip. Enter one filename per line. JS from your own theme cannot be stripped.'),
      '#default_value' => theme_get_setting('js_to_strip'),
      ),
    );

  // Add settings to beginning of array
  $form = array_reverse($form);
  $form['basetheme_settings'] = $basetheme_settings;
  $form = array_reverse($form);
}
