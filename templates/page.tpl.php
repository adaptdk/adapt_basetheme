<?php if ($tabs) : ?>
  <div class="edit_links"><?php print render($tabs); ?></div>
<?php endif; ?>
<?php print render($page['content']) ?>
<?php if(!empty($snippet_adwords)) : ?>
  <?php print $snippet_adwords; ?>
<?php endif; ?>
