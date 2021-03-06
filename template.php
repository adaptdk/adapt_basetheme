<?php

// **********************
// PREPROCESS FUNCTIONS *
// **********************

/**
 * Implements template_preprocess_html().
 */
function adapt_basetheme_preprocess_html(&$variables) {
  // Add theme folder
  $variables['theme_folder'] = base_path() . path_to_theme();

  // Strip all CSS classes, but keep some
  // We use array keys because isset() is faster & cleaner than a foreach loop
  //
  // ### Example of usage in settings.php
  //   $conf['adapt_basetheme_body_classes_to_keep'] = array(
  //   'front' => TRUE,
  //   'not-front' => TRUE,
  //   'page-taxonomy-term' => TRUE,
  //   'adminimal-menu' => TRUE,
  //   'menu-render-collapsed' => TRUE,
  //   'logged-in' => TRUE,
  // );

  $classes_to_keep = variable_get('adapt_basetheme_body_classes_to_keep', array(
    'front' => TRUE,
    'not-front' => TRUE,
    'page-taxonomy-term' => TRUE,
    'adminimal-menu' => TRUE,
    'menu-render-collapsed' => TRUE,
    'logged-in' => TRUE,
    ));
  // Get nodetype
  $nodetype = preg_grep('/^node-type/', $variables['classes_array']);
  if ($nodetype) {
    $type = current($nodetype);
    $classes_to_keep[$type] = TRUE;
  }

  foreach ($variables['classes_array'] as $key => $class) {
    if (!isset($classes_to_keep[trim($class)])) {
      unset($variables['classes_array'][$key]);
    }
  }
}

/**
 * Implements template_preprocess_page().
 */
function adapt_basetheme_preprocess_page(&$variables, $hook) {
  // Remove div wrapper around main content
  if (isset($variables['page']['content']['system_main']['#theme_wrappers']) && is_array($variables['page']['content']['system_main']['#theme_wrappers'])) {
    $variables['page']['content']['system_main']['#theme_wrappers'] = array_diff($variables['page']['content']['system_main']['#theme_wrappers'], array('block'));
  }
}

/**
 * Implements template_preprocess_block().
 */
function adapt_basetheme_preprocess_block(&$variables) {
  $block = $variables['elements']['#block'];

  // Add a theme suggestion to block--menu.tpl so we dont have create a ton of blocks with <nav>
  if (($block->module == "system" && $block->delta == "main-menu") || $block->module == "menu_block") {
    $variables['theme_hook_suggestions'][] = 'block__menu';
  }

  // If we have the main menu, change the title to follow the active item
  // (needed for mobile navigation)
  // For the main menu handled by the system module
  if ($block->delta == 'main-menu' && $block->title != '<none>') {
    // Get only the numeric keys, being the actual links
    $menu_links = element_children($variables['elements']);

    foreach ($menu_links as $link) {
      if (isset($variables['elements'][$link]['#localized_options']['attributes']['class']) && in_array('active-trail', $variables['elements'][$link]['#localized_options']['attributes']['class'])) {
        $variables['elements']['#block']->subject = $variables['elements'][$link]['#original_link']['link_title'];
      }
    }
  }

  // For main menu handled by menu block module
  if ($block->module == 'menu_block' && $variables['elements']['#config']['menu_name'] == 'main-menu' && $block->title != '<none>') {
    // Get only the numeric keys, being the actual links
    $menu_links = element_children($variables['elements']['#content']);

    foreach ($menu_links as $link) {
      if (isset($variables['elements']['#content'][$link]['#localized_options']['attributes']['class']) && in_array('active-trail', $variables['elements']['#content'][$link]['#localized_options']['attributes']['class'])) {
        $variables['elements']['#block']->subject = $variables['elements']['#content'][$link]['#original_link']['link_title'];
      }
    }
  }
}

/**
 * Implements template_preprocess_panels_pane()
 */
function adapt_basetheme_preprocess_panels_pane(&$variables) {
  $classes_to_strip = array(
    'panel-pane' => 'panel-pane',
  );

  // Strip all classes beginning with 'pane-'
  foreach ($variables['classes_array'] as $key => $class) {
    if (substr($class, 0, 5) == 'pane-' || isset($classes_to_strip[$class])) {
      unset($variables['classes_array'][$key]);
    }
  }
}


/**
 * Implements template_preprocess_taxonomy_term().
 */
function adapt_basetheme_preprocess_taxonomy_term(&$variables) {
  // Remove taxonomy term description wrapper
  if (!empty($variables['content']['description'])) {
    $variables['content']['description']['#prefix'] = '';
    $variables['content']['description']['#suffix'] = '';
  }
}

/**
 * Implements template_preprocess_field().
 */
function adapt_basetheme_preprocess_field(&$variables) {
  // Replace all field classes with just one class
  $variables['classes_array'] = array(
    drupal_html_class('field ' . $variables['element']['#field_name']),
  );
  // We'll also add a custom class to be used for the label, to indicate its
  // placement
  if (!$variables['label_hidden']) {
    $variables['label_class'] = $variables['element']['#label_display'];
  }
}

/**
 * Implements template_preprocess_views_view_table().
 */
function adapt_basetheme_preprocess_views_view_table(&$variables) {
  // If there are no classes to be added, do nothing
  if (!$variables['options']['default_row_class'] && !$variables['options']['row_class_special']) {
    return;
  }

  // If standard classes are to be added, remove them
  if ($variables['options']['default_row_class']) {
    foreach ($variables['row_classes'] as $key => $class) {
      $variables['row_classes'][$key] = array();
    }
  }

  // Add first/ last & odd/even
  if ($variables['options']['row_class_special']) {
    $max = count($variables['rows']) - 1;
    foreach ($variables['row_classes'] as $key => $class) {
      $classes = array();

      if ($key == 0) {
        $classes[] = 'first';
      }
      if ($key == $max) {
        $classes[] = 'last';
      }

      $classes[] = $key % 2 ? 'even' : 'odd';

      $variables['row_classes'][$key] = $classes;
    }
  }
}

/**
 * Implements template_preprocess_views_view_list().
 */
function adapt_basetheme_preprocess_views_view_list(&$variables) {
  adapt_basetheme_preprocess_views_view_unformatted($variables);
}

/**
 * Implement template_preprocess_views_view_unformatted().
 */
function adapt_basetheme_preprocess_views_view_unformatted(&$variables) {
  // If there are no classes to be added, do nothing
  if (!$variables['options']['default_row_class'] && !$variables['options']['row_class_special']) {
    return;
  }

  // If standard classes are to be added, remove them
  if ($variables['options']['default_row_class']) {
    foreach ($variables['classes_array'] as $key => $class) {
      $variables['classes_array'][$key] = '';
    }
  }

  // Add first/ last & odd/even
  if ($variables['options']['row_class_special']) {
    $max = count($variables['rows']) - 1;
    foreach ($variables['classes'] as $key => $class) {
      $classes = array();

      if ($key == 0) {
        $classes[] = 'first';
      }
      if ($key == $max) {
        $classes[] = 'last';
      }

      $classes[] = $key % 2 ? 'even' : 'odd';

      $variables['classes_array'][$key] = implode(' ', $classes);
    }
  }
}


// *************
// ALTER HOOKS *
// *************

/**
 * Implements hook_html_head_alter().
 */
function adapt_basetheme_html_head_alter(&$head_elements) {
  unset($head_elements['system_meta_generator']);

  global $theme;
  $path = '/' . drupal_get_path('theme', $theme);

  // Add Apple touch icons
  // Standard
  $head_elements[] = array(
    '#type' => 'html_tag',
    '#tag' => 'link',
    '#attributes' => array(
      'rel' => 'apple-touch-icon',
      'href' => $path . '/images/apple-touch-icon.png',
      ),
    );
  // Bigger sizes
  $sizes = array(
    '144x144',
    '114x114',
    '72x72'
    );
  foreach ($sizes as $size) {
    $head_elements[] = array(
      '#type' => 'html_tag',
      '#tag' => 'link',
      '#attributes' => array(
        'rel' => 'apple-touch-icon',
        'sizes' => $size,
        'href' => $path . '/images/apple-touch-icon-' . $size . '.png',
        ),
      );
  }

  // Add meta viewport
  $head_elements[] = array(
    '#type' => 'html_tag',
    '#tag' => 'meta',
    '#attributes' => array(
      'name' => 'viewport',
      'content' => 'width=device-width, initial-scale=1.0',
      ),
    );

  // Alter rdf_node_title so it validates
  unset($head_elements['rdf_node_title']['#attributes']['about']);
}

/**
 * Implements hook_js_alter().
 */
function adapt_basetheme_js_alter(&$js) {
  // Strip the JS we defined in our settings
  if (!theme_get_setting('js_to_strip')) {
    return;
  }

  global $theme;
  $path = drupal_get_path('theme', $theme);

  $js_to_strip = array_map('trim', explode("\n", theme_get_setting('js_to_strip')));
  $js_to_strip = array_flip($js_to_strip);

  foreach ($js as $key => $file) {
    // Make sure we don't strip JS from our own theme
    $theme_key = substr($key, 0, strlen($path));

    // Get filename
    // (always last part of path)
    $filename_array = explode('/', $key);
    $filename = end($filename_array);

    if (isset($js_to_strip[$filename]) && $theme_key != $path) {
      unset($js[$key]);
    }
  }
}

// *****************
// THEME FUNCTIONS *
// *****************

// NAVIGATION
// -----------

/**
 * Overwrite theme_menu_link().
 */
function adapt_basetheme_menu_link(&$variables) {
  // Classes to keep. We don't keep active & active-trail because they're already added to the anchor element
  $classes_to_keep = array(
    'first' => TRUE,
    'last' => TRUE,
  );

  // Strip classes
  if ($variables['element']['#attributes']['class']) {
    foreach ($variables['element']['#attributes']['class'] as $key => $class) {
      if (!isset($classes_to_keep[$class])) {
        unset($variables['element']['#attributes']['class'][$key]);
      }
    }
  }

  // Check if we even have classes defined
  if (isset($variables['element']['#attributes']['class']) && empty($variables['element']['#attributes']['class'])) {
    unset($variables['element']['#attributes']['class']);
  }

  $element = $variables['element'];
  $sub_menu = '';

  if ($element['#below']) {
    $sub_menu = drupal_render($element['#below']);
  }
  $output = l($element['#title'], $element['#href'], $element['#localized_options']);
  return '<li' . drupal_attributes($element['#attributes']) . '>' . $output . $sub_menu . "</li>\n";
}

/**
 * Overwrite theme_breadcrumb().
 */
function adapt_basetheme_breadcrumb($variables) {
  $breadcrumb = $variables['breadcrumb'];

  return implode(' &raquo; ', $breadcrumb);
}

/**
 * Overwrite theme_links().
 * Specifically for language switcher
 */
function adapt_basetheme_links__locale_block($variables) {
  $links = $variables['links'];

  $output = '';

  if (count($links) > 0) {
    $output .= '<ul class="language">';

    $num_links = count($links);
    $i = 1;

    foreach ($links as $key => $link) {
      $class = array($key);

      // Add first & last classes to the list
      // We've removed the active class because it's on the anchor element already
      if ($i == 1) {
        $class[] = 'first';
      }
      if ($i == $num_links) {
        $class[] = 'last';
      }

      $output .= '<li' . drupal_attributes(array('class' => $class)) . '>';

      if (isset($link['href'])) {
        // Remove the 'language-link' class
        $link['attributes'] = array();

        // Pass in $link as $options, they share the same keys.
        $output .= l(strtoupper($link['language']->language), $link['href'], $link);
      }
      elseif (!empty($link['title'])) {
        $output .= '<span>' . strtoupper($link['language']->language) . '</span>';
      }

      $i++;
      $output .= "</li>\n";
    }

    $output .= '</ul>';
  }

  return $output;
}

/**
 * Overwrite theme_pager().
 */
function adapt_basetheme_pager($variables) {
  $tags = $variables['tags'];
  $element = $variables['element'];
  $parameters = $variables['parameters'];
  $quantity = $variables['quantity'];
  global $pager_page_array, $pager_total;

  // Calculate various markers within this pager piece:
  // Middle is used to "center" pages around the current page.
  $pager_middle = ceil($quantity / 2);
  // current is the page we are currently paged to
  $pager_current = $pager_page_array[$element] + 1;
  // first is the first page listed by this pager piece (re quantity)
  $pager_first = $pager_current - $pager_middle + 1;
  // last is the last page listed by this pager piece (re quantity)
  $pager_last = $pager_current + $quantity - $pager_middle;
  // max is the maximum page number
  $pager_max = $pager_total[$element];
  // End of marker calculations.

  // Prepare for generation loop.
  $i = $pager_first;
  if ($pager_last > $pager_max) {
    // Adjust "center" if at end of query.
    $i = $i + ($pager_max - $pager_last);
    $pager_last = $pager_max;
  }
  if ($i <= 0) {
    // Adjust "center" if at start of query.
    $pager_last = $pager_last + (1 - $i);
    $i = 1;
  }
  // End of generation loop preparation.

  $li_first = theme('pager_first', array('text' => (isset($tags[0]) ? $tags[0] : t('« first')), 'element' => $element, 'parameters' => $parameters));
  $li_previous = theme('pager_previous', array('text' => (isset($tags[1]) ? $tags[1] : t('‹ previous')), 'element' => $element, 'interval' => 1, 'parameters' => $parameters));
  $li_next = theme('pager_next', array('text' => (isset($tags[3]) ? $tags[3] : t('next ›')), 'element' => $element, 'interval' => 1, 'parameters' => $parameters));
  $li_last = theme('pager_last', array('text' => (isset($tags[4]) ? $tags[4] : t('last »')), 'element' => $element, 'parameters' => $parameters));

  if ($pager_total[$element] > 1) {
    if ($li_first) {
      $items[] = array(
        'class' => array('first'),
        'data' => $li_first,
        );
    }
    if ($li_previous) {
      $items[] = array(
        'class' => array('previous'),
        'data' => $li_previous,
        );
    }

    // When there is more than one page, create the pager list.
    if ($i != $pager_max) {
      if ($i > 1) {
        $items[] = array(
          'class' => array('ellipsis'),
          'data' => '…',
          );
      }
      // Now generate the actual pager piece.
      for (; $i <= $pager_last && $i <= $pager_max; $i++) {
        if ($i < $pager_current) {
          $items[] = array(
            //'class' => array('pager-item'),
            'data' => theme('pager_previous', array('text' => $i, 'element' => $element, 'interval' => ($pager_current - $i), 'parameters' => $parameters)),
            );
        }
        if ($i == $pager_current) {
          $items[] = array(
            'class' => array('current'),
            'data' => $i,
            );
        }
        if ($i > $pager_current) {
          $items[] = array(
            //'class' => array('pager-item'),
            'data' => theme('pager_next', array('text' => $i, 'element' => $element, 'interval' => ($i - $pager_current), 'parameters' => $parameters)),
            );
        }
      }
      if ($i < $pager_max) {
        $items[] = array(
          'class' => array('ellipsis'),
          'data' => '…',
          );
      }
    }
    // End generation.
    if ($li_next) {
      $items[] = array(
        'class' => array('next'),
        'data' => $li_next,
        );
    }
    if ($li_last) {
      $items[] = array(
        'class' => array('last'),
        'data' => $li_last,
        );
    }
    return theme('item_list', array(
      'items' => $items,
      'attributes' => array('class' => array('pager')),
      ));
  }
}

// FORMS
// ------

/**
 * Overwrite theme_form().
 */
function adapt_basetheme_form($variables) {
  $element = $variables['element'];
  if (isset($element['#action'])) {
    $element['#attributes']['action'] = drupal_strip_dangerous_protocols($element['#action']);
  }
  element_set_attributes($element, array('method', 'id'));
  if (empty($element['#attributes']['accept-charset'])) {
    $element['#attributes']['accept-charset'] = "UTF-8";
  }

  return '<form' . drupal_attributes($element['#attributes']) . '>' . $element['#children'] . '</form>';
}

// PANELS
// ------

/**
 * Overwrite theme_panels_flexible().
 */
function adapt_basetheme_panels_flexible($variables) {
  $css_id = $variables['css_id'];
  $content = $variables['content'];
  $settings = $variables['settings'];
  $display = $variables['display'];
  $layout = $variables['layout'];
  $handler = $variables['renderer'];

  panels_flexible_convert_settings($settings, $layout);

  $renderer = panels_flexible_create_renderer(FALSE, $css_id, $content, $settings, $display, $layout, $handler);

  // CSS must be generated because it reports back left/middle/right
  // positions.
  $css = panels_flexible_render_css($renderer);

  if (!empty($renderer->css_cache_name) && empty($display->editing_layout)) {
    ctools_include('css');
    // Generate an id based upon rows + columns:
    $filename = ctools_css_retrieve($renderer->css_cache_name);
    if (!$filename) {
      $filename = ctools_css_store($renderer->css_cache_name, $css, FALSE);
    }

    // Give the CSS to the renderer to put where it wants.
    if ($handler) {
      $handler->add_css($filename, 'module', 'all', FALSE);
    }
    else {
      drupal_add_css($filename);
    }
  }
  else {
    // If the id is 'new' we can't reliably cache the CSS in the filesystem
    // because the display does not truly exist, so we'll stick it in the
    // head tag. We also do this if we've been told we're in the layout
    // editor so that it always gets fresh CSS.
    drupal_add_css($css, array('type' => 'inline', 'preprocess' => FALSE));
  }

  // Also store the CSS on the display in case the live preview or something
  // needs it
  $display->add_css = $css;

  $output = panels_flexible_render_items($renderer, $settings['items']['canvas']['children'], $renderer->base['canvas']);

  return $output;
}

/**
 * Overwrite theme_panels_default_style_render_region().
 */
function adapt_basetheme_panels_default_style_render_region($variables) {
  $output = implode('', $variables['panes']);

  return $output;
}


// GENERAL
// --------

/**
 * Overwrite theme_item_list().
 */
function adapt_basetheme_item_list($variables) {
  $items = $variables['items'];
  $title = $variables['title'];
  $type = $variables['type'];
  $attributes = $variables['attributes'];

  // Only output the list container and title, if there are any list items.
  // Check to see whether the block title exists before adding a header.
  // Empty headers are not semantic and present accessibility challenges.
  $output = '';
  if (isset($title) && $title !== '') {
    $output .= '<h3>' . $title . '</h3>';
  }

  if (!empty($items)) {
    $output .= "<$type" . drupal_attributes($attributes) . '>';
    $num_items = count($items);
    $i = 0;
    foreach ($items as $item) {
      $attributes = array();
      $children = array();
      $data = '';
      $i++;
      if (is_array($item)) {
        foreach ($item as $key => $value) {
          if ($key == 'data') {
            $data = $value;
          }
          elseif ($key == 'children') {
            $children = $value;
          }
          else {
            $attributes[$key] = $value;
          }
        }
      }
      else {
        $data = $item;
      }
      if (count($children) > 0) {
        // Render nested list.
        $data .= theme_item_list(array('items' => $children, 'title' => NULL, 'type' => $type, 'attributes' => $attributes));
      }
      if ($i == 1) {
        $attributes['class'][] = 'first';
      }
      if ($i == $num_items) {
        $attributes['class'][] = 'last';
      }
      $output .= '<li' . drupal_attributes($attributes) . '>' . $data . "</li>\n";
    }
    $output .= "</$type>";
  }
  return $output;
}
