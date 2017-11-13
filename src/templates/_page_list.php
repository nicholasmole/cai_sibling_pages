<?php

use JB\SPW\Helpers;

?>

<div id="spw-page-list">
  <ul>
    <?php foreach ($pages as $page): ?>
      <li><?php echo Helpers::build_sidebar_link($page) ?></li>
    <?php endforeach; ?>
  </ul>
</div>

