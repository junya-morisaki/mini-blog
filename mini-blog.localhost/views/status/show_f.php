
<?php if($value==='follow'): ?>
<h2>フォロー一覧</h2>
<?php foreach($infos as $info ): ?>
  <a href="<?php echo $base_url; ?>/user/<?php echo $this->escape($info['user_name']) ?>">
<?php echo $info['name'] ?>
</a><br>
<?php endforeach; ?>

<?php elseif($value==='follower'): ?>
<h2>フォロワー一覧</h2>
<?php foreach($infos as $info ): ?>
  <a href="<?php echo $base_url; ?>/user/<?php echo $this->escape($info['user_name']) ?>">
<?php echo $info['name'] ?>
</a><br>
<?php endforeach; ?>
<?php endif; ?>
