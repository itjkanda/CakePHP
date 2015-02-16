<p>次のフォームに必要事項をご記入ください。</p>
<?php echo $this->Form->create('User', array('type' => 'post','enctype' => 'multipart/form-data')); ?>
<dl>
  <dt>
    <?php echo $this->Form->label('User.name', 'ニックネーム'); ?>
  </dt>
  <dd>
    <?php
      /**
       * 実はformヘルパー使ってフォームを生成していると
       * $this->request->dataに値が入っているかどうか判定して
       * 勝手にvalueを入れてくれるのです(ない場合はvalueは空白になる)
       * フォーム1個出すのにviewでif文書きまくるとHTMlのコーダーに殴られるので
       * 極力避けましょう
       * 
       * $this->request->dataに値を設置してFormのdefaultを削除してみてください
       * 
       */
      if (!empty($data['User']['name'])) {
        echo $this->Form->text('User.name', array('default' => $data['User']['name']));
      } else {
        echo $this->Form->text('User.name');
      }
    ?>
    <?php echo $this->Form->error('User.name'); ?>
  </dd>
  <dt>
    <?php echo $this->Form->label('User.email', 'メールアドレス'); ?>
  </dt>
  <dd>
    <?php
      if (!empty($data['User']['email'])) {
        echo $this->Form->text('User.email', array('default' => $data['User']['email']));
      } else {
        echo $this->Form->text('User.email');
      }
    ?>
    <?php echo $this->Form->error('User.email'); ?>
  </dd>
  <dt>
    <?php echo $this->Form->label('User.password', 'パスワード'); ?>
  </dt>
  <dd>
    <?php
      if (!empty($data['User']['password'])) {
        echo $this->Form->text('User.password', array('default' => $data['User']['password']));
      } else {
        echo $this->Form->text('User.password');
      }
    ?>
    <?php echo $this->Form->error('User.password'); ?>
  </dd>
  <dt>
    <?php echo $this->Form->label('User.picture', '写真など'); ?>
  </dt>
  <dd>
    <?php echo $this->Form->file('User.picture_tmp'); ?>
    <?php echo $this->Form->error('User.picture_tmp'); ?>
  </dd>
</dl>
<div>
  <?php echo $this->Form->submit('入力内容を確認する'); ?>
</div>
<?php echo $this->Form->end(); ?>