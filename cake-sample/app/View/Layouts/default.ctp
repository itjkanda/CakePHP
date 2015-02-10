<!DOCTYPE html>
<html lang="ja">
<head>
    <?php echo $this->Html->charset("utf-8");?>
    <?php echo $this->Html->css('./style.css'); ?>
    <link rel="stylesheet" href="./style.css">
    <title><?php echo $title_for_layout; ?></title>
</head>
<body>

  <div id="wrap">
      <div id="head">
          <h1>ひとこと掲示板</h1>
      </div>
      <div id="content">
          <?php echo $this->fetch('content'); ?>
      </div>
      <div id="foot">
          <p><img src="images/txt_copyright.png" width="136" height="15" alt=""></p>
      </div>
  </div>

  <?php echo $this->element('sql_dump'); ?>

</body>

</html>
