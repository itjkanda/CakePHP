<p>&laquo;<?php echo $this->Html->link('一覧に戻る', array('action' => 'index')); ?></p>

<div class="msg">
<?php
  if (!empty($picture)) {
    echo $this->Html->image($picture, array('width' => 48, 'height' => 48));
  }
?>
  <p><?php echo $message; ?><span class="name"><?php echo $name; ?></span></p>
  <p class="day"><?php echo $date; ?></p>
</div>