<p>記入した内容を確認して、「登録する」ボタンをクリックしてください</p>
<?php echo $this->Form->create('Users', array('type' => 'post')); ?>
<dl>
  <dt>ニックネームほげ</dt>
  <dd><?php var_dump($data); ?></dd>
  <dt>メールアドレス</dt>
  <dd><?php echo $data['Users']['email']; ?></dd>
  <dt>パスワード</dt>
  <dd><?php echo $data['Users']['password']; ?></dd>
  <dt>写真など</dt>
  <dd><?php echo $this->Html->image($img_name); ?></dd>
</dl>
<div>
  <?php echo $this->Html->link('書き直す', array('controller' => 'users', 'action' => 'join')); ?>
  <?php echo $this->Form->submit('確認して登録する'); ?>
</div>