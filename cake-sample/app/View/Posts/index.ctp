<?php echo $this->Form->create('Post' , array('type' => 'post')); ?>
<dl>
  <dt>メッセージをどうぞ</dt>
  <dd><?php echo $this->Form->textarea('Post.message'); ?></dd>
</dl>
<div>
<?php echo $this->Form->end('投稿する'); ?>
<?php foreach ($postData as $data): ?>
<div class="msg">
  <img src="member_picture/<?php echo $data['User']['picture']; ?>" width="48" height="48" alt="">
  <p><?php echo $data['Post']['message']; ?><span class="name">(<?php echo $data['User']['name']; ?>)</span></p>
  <p class="day"><?php echo $data['Post']['created']; ?></p>
</div>
<?php endforeach; ?>
</div>
<?php echo $this->Html->link('ログアウト', '/users/logout'); ?>