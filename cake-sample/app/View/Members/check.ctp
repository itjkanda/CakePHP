<p>記入した内容を確認して、「登録する」ボタンをクリックしてください</p>
<?php echo $this->Form->create('Member', array('type' => 'post')); ?>

<dl>
  <dt>ニックネーム</dt>
  <dd><?php echo $data['Member']['name']; ?></dd>
  <dt>メールアドレス</dt>
  <dd><?php echo $data['Member']['email']; ?></dd>
  <dt>パスワード</dt>
  <dd><?php echo $data['Member']['password']; ?></dd>
  <dt>写真など</dt>
  <dd><img src="<?php echo $data['Member']['image']; ?>" /></dd>
</dl>
<div>
  <?php echo $this->Html->link('書き直す', array('controller' => 'members', 'action' => 'join')); ?>
  <?php echo $this->Form->submit('確認して登録する'); ?>
</div>