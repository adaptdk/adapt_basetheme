<?php

/**
 * @file
 * Default simple view template to display a list of rows.
 *
 * @ingroup views_templates
 */
?>
<section>
  <?php if (!empty($title)): ?>
    <h2><?php print $title; ?></h2>
  <?php endif; ?>
  <?php foreach ($rows as $id => $row): ?>
    <?php if ($classes_array[$id]): ?>
      <article class="<?php print $classes_array[$id]; ?>">
    <?php else: ?>
      <article>
    <?php endif; ?>
      <?php print $row; ?>
    </article>
  <?php endforeach; ?>
</section>
