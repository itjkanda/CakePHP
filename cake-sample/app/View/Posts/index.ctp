<?php echo $this->Form->create('Post' , array('type' => 'post')); ?>
<dl>
  <dt>メッセージをどうぞ</dt>
  <dd><?php echo $this->Form->textarea('Post.message'); ?></dd>
</dl>
<div>
<?php echo $this->Form->end('投稿する'); ?>
</div>
<?php echo $this->Html->link('ログアウト', '/users/logout'); ?>