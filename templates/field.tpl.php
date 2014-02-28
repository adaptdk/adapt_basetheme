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
<?php if ($field_wrapper): ?>
  <<?php print $field_wrapper; ?> class="<?php print $classes; ?>"<?php print $attributes; ?>>
<?php endif; ?>

  <?php if (!$label_hidden) : ?>
    <?php if ($label_wrapper): ?>
      <<?php print $label_wrapper; ?> class="<?php print $label_class; ?>">
    <?php endif; ?>

    <?php print $label ?>&nbsp;

    <?php if ($label_wrapper): ?>
    </<?php print $label_wrapper; ?>>
    <?php endif; ?>
  <?php endif; ?>

  <?php foreach ($items as $delta => $item) : ?>
    <?php if ($item_wrapper): ?>
      <<?php print $item_wrapper; ?> class="<?php print $delta % 2 ? 'odd' : 'even'; ?>"<?php print $item_attributes[$delta]; ?>>
    <?php endif; ?>

    <?php print render($item); ?>

    <?php if ($item_wrapper): ?>
      </<?php print $item_wrapper; ?>>
    <?php endif; ?>
  <?php endforeach; ?>

<?php if ($field_wrapper): ?>
  </<?php print $field_wrapper; ?>>
<?php endif; ?>
