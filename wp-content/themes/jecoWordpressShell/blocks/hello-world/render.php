<link rel="stylesheet" href="<?= BLOCKS_STYLES_PATH . "/hello-world/style.css" ?>">
<?php
$data = get_field('data');
$blockID = $block['id'];
?>
<div class="hello-world" id="<?= $blockID ?>">
  <h1>Render block</h1>
  <p>This is the render block</p>
  <p><?= $data['text'] ?></p>
</div>