<?php echo $this->Form->create('Post' , array('type' => 'post')); ?>
<dl>
  <dt>メッセージをどうぞ</dt>
  <!-- こういう時どう実装するのがベストなんだろ -->
  <dd>
  <?php
    if (isset($repMessage)) {
      echo $this->Form->textarea('Post.message', array('value' => $repMessage));
      echo $this->Form->hidden('Post.reply_post_id', array('value' => $repId));
    } else {
      echo $this->Form->textarea('Post.message', array());
    }
  ?>
  </dd>
  <!-- ここまで -->
</dl>
<div>
<?php echo $this->Form->end('投稿する'); ?>
<?php foreach ($postData as $data): ?>
<div class="msg">
  <?php if (!empty($data['User']['picture'])): ?>
  <?php echo $this->html->image($data['User']['picture'], array('width' => 48, 'height' => 48)); ?>
  <?php endif; ?>
  <p><?php echo $data['Post']['message']; ?><span class="name">（<?php echo $data['User']['name']; ?>）</span>【<?php echo $this->Html->link('Re', array('controller' => 'posts', 'action' => 'index', '?' => array('res' => $data['Post']['post_id']))); ?>】</p>
  <p class="day"><?php echo $data['Post']['created']; ?></p>
</div>
<?php endforeach; ?>
<?php echo $this->Paginator->counter(); ?><br />
<?php echo $this->Paginator->prev('前へ'); ?>
<?php echo $this->Paginator->numbers(); ?>
<?php echo $this->Paginator->next('次へ'); ?>
</div>
<?php echo $this->Html->link('ログアウト', '/users/logout'); ?>