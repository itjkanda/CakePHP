<div id="lead">
  <p>メールアドレスとパスワードを記入してログインしてください。</p>
  <p>入会手続きがまだの方はこちらからどうぞ。</p>
  <p>&raquo;<?php echo $this->Html->link('入会手続きをする', array('controller' => 'Logins', 'action' => 'join')); ?></p>
  <?php if (isset($error)) : ?>
    <p><?php echo $error; ?></p>
  <?php endif; ?>
</div>
<?php echo $this->Form->create('Login', array('type' => 'post')); ?>
<dl>
  <dt><?php echo $this->Form->label('Login.email', 'メールアドレス'); ?></dt>
  <dd><?php echo $this->Form->text('Login.email', array('default' => '')); ?></dd>
  <dt><?php echo $this->Form->label('Login.password', 'パスワード') ?></dt>
  <dd><?php echo $this->Form->password('Login.password', array('default' => '')); ?></dd>
  <dt>ログイン情報の記録</dt>
  <dd><?php echo $this->Form->checkbox('Login.autoLogin'); ?><?php echo $this->Form->label('Login.autoLogin', '次回からは自動的にログインする'); ?></dd>
</dl>
<div><?php echo $this->Form->submit('ログインする'); ?></div>

<?php echo $this->Form->end(); ?>