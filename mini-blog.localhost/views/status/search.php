<form class="" action="" method="get">
<label for="">キーワード:</label>
<input type="text" name="key" value="">
<input type="submit" name="" value="送信">
</form>
<br>
<?php if(isset($statuses)) :?>
  <h3><?php echo $this->escape($key).'の検索結果'; ?></h3>
  <br>
  <div id="statuses">
      <?php foreach ($statuses as $status): ?>
      <?php echo $this->render('status/status', array('status' => $status,'user'=>$user,'list'=>$list)); ?>
      <?php endforeach; ?>
  </div>
<?php endif; ?>
