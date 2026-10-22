<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>
<div class="card">
  <div class="card-header">
    <h3 class="card-title"><?php echo isset($box_title) ? html_escape($box_title) : 'Title' ?></h3>

    <div class="card-tools">
      <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
        <i class="fas fa-minus"></i>
      </button>
      <button type="button" class="btn btn-tool" data-card-widget="remove" title="Remove">
        <i class="fas fa-times"></i>
      </button>
    </div>
  </div>
  <div class="card-body">
    <?php echo isset($box_body) ? $box_body : 'Start creating your amazing application!' ?>
  </div>
  <div class="card-footer">
    <?php echo isset($box_footer) ? $box_footer : 'Footer' ?>
  </div>
</div>
