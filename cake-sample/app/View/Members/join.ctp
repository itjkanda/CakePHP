<p>次のフォームに必要事項をご記入ください。</p>
<?php echo $this->Form->create('Member', array('type' => 'post','enctype' => 'multipart/form-data')); ?>

<dl>
  <dt><?php echo $this->Form->label('Member.name', 'ニックネーム'); ?></dt>
  <dd><?php echo $this->Form->text('Member.name', array('default' => $data['Member']['name'])); ?>
<?php echo $this->Form->error('Member.name'); ?></dd>
  <dt><?php echo $this->Form->label('Member.email', 'メールアドレス'); ?></dt>
  <dd><?php echo $this->Form->text('Member.email', array('default' => $data['Member']['email'])); ?>
<?php echo $this->Form->error('Member.email'); ?></dd>
  <dt><?php echo $this->Form->label('Member.password', 'パスワード'); ?></dt>
  <dd>
<?php echo $this->Form->password('Member.password', array('default' => $data['Member']['password'])); ?>
<?php echo $this->Form->error('Member.password'); ?></dd>
  <dt><?php echo $this->Form->label('Member.picture', '写真など'); ?></dt>
  <dd><?php echo $this->Form->file('Member.picture_tmp'); ?>
<?php echo $this->Form->error('Member.picture_tmp'); ?></dd>
</dl>
<div><?php echo $this->Form->submit('入力内容を確認する'); ?></div>
<?php echo $this->Form->end(); ?>