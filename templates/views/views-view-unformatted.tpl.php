<?php

/**
 * @file
 * Default simple view template to display a list of rows.
 *
 * @ingroup views_templates
 */
?>

<div class="views-content">

  <?php if (!empty($title)): ?>
    <h2><?php print $title; ?></h2>
  <?php endif; ?>

  <?php foreach ($rows as $id => $row): ?>
    <?php if ($classes_array[$id]): ?>
      <div class="<?php print $classes_array[$id]; ?>">
    <?php else: ?>
      <div>
    <?php endif; ?>
      <?php print $row; ?>
      </div>
  <?php endforeach; ?>

</div>