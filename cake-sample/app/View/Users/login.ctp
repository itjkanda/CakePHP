<div id="lead">
  <p>メールアドレスとパスワードを記入してログインしてください。</p>
  <p>入会手続きがまだの方はこちらからどうぞ。</p>
  <p>&raquo;<?php echo $this->Html->link('入会手続きをする', array('controller' => 'Users', 'action' => 'join')); ?></p>
  <?php if (isset($error)) : ?>
    <p><?php echo $error; ?></p>
  <?php endif; ?>
</div>
<?php echo $this->Form->create('User', array('type' => 'post')); ?>
<dl>
  <dt><?php echo $this->Form->label('User.email', 'メールアドレス'); ?></dt>
  <dd><?php echo $this->Form->text('User.email', array('default' => '')); ?></dd>
  <dt><?php echo $this->Form->label('User.password', 'パスワード') ?></dt>
  <dd><?php echo $this->Form->password('User.password', array('default' => '')); ?></dd>
  <dt>ログイン情報の記録</dt>
  <dd><?php echo $this->Form->checkbox('User.autoUser'); ?><?php echo $this->Form->label('User.autoUser', '次回からは自動的にログインする'); ?></dd>
</dl>
<div><?php echo $this->Form->submit('ログインする'); ?></div>

<?php echo $this->Form->end(); ?>