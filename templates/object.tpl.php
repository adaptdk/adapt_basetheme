<?php if (!empty($pre_object)): ?>
  <?php print render($pre_object) ?>
<?php endif; ?>

<div class='<?php print $classes ?> clearfix' <?php print ($attributes) ?>>
  <?php if (!empty($title_prefix)): ?>
    <?php print render($title_prefix) ?>
  <?php endif; ?>

  <?php if (!empty($title)): ?>
    <h2 <?php if (!empty($title_attributes)) print $title_attributes ?>>
      <?php if (!empty($new)): ?>
        <span class='new'><?php print $new ?></span>
      <?php endif; ?>

      <?php print $title ?>
    </h2>
  <?php endif; ?>

  <?php if (!empty($title_suffix)): ?>
    <?php print render($title_suffix) ?>
  <?php endif; ?>

  <?php if (!empty($submitted)): ?>
    <div class='<?php print $hook ?>-submitted clearfix'><?php print $submitted ?></div>
  <?php endif; ?>

  <?php if (!empty($content)): ?>
    <div <?php print $content_attributes; ?>><?php print render($content) ?></div>
  <?php endif; ?>

  <?php if (!empty($links)): ?>
    <div class='<?php print $hook ?>-links clearfix'><?php print render($links) ?></div>
  <?php endif; ?>
</div>

<?php if (!empty($post_object)): ?>
  <?php print render($post_object) ?>
<?php endif; ?>
