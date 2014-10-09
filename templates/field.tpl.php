<?php

/**
 * @file field.tpl.php
 *
 * This is a custom field tpl for the Adapt Basetheme. For this to work the
 * Field Wrappers module should be enabled, as it sets the $_wrapper variables.
 *
 * If you disable the Field Wrappers module this will throw errors and you will
 * need to use the standard tpl file located at
 * https://api.drupal.org/api/drupal/modules!field!theme!field.tpl.php/7
 */
?>
<?php if (!empty($field_wrapper)): ?>
  <<?php print $field_wrapper; ?> class="<?php print $classes; ?>"<?php print $attributes; ?>>
<?php endif; ?>

  <?php if (!$label_hidden) : ?>
    <?php if (!empty($label_wrapper)): ?>
      <<?php print $label_wrapper; ?> class="<?php print isset($label_class) ? $label_class : NULL; ?>">
    <?php endif; ?>

    <?php print $label ?>&nbsp;

    <?php if (!empty($label_wrapper)): ?>
    </<?php print $label_wrapper; ?>>
    <?php endif; ?>
  <?php endif; ?>

  <?php foreach ($items as $delta => $item) : ?>
    <?php if (!empty($item_wrapper)): ?>
      <<?php print $item_wrapper; ?> class="<?php print $delta % 2 ? 'odd' : 'even'; ?>"<?php print $item_attributes[$delta]; ?>>
    <?php endif; ?>

    <?php print render($item); ?>

    <?php if (!empty($item_wrapper)): ?>
      </<?php print $item_wrapper; ?>>
    <?php endif; ?>
  <?php endforeach; ?>

<?php if (!empty($field_wrapper)): ?>
  </<?php print $field_wrapper; ?>>
<?php endif; ?>

