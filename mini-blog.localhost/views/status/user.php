<?php $this->setLayoutVar('title', $user['user_name']) ?>

<h3><?php echo 'ユーザーID:@'.$this->escape($user['user_name']); ?></h3>
<h3><?php echo 'ユーザー名:'.$this->escape($user['name']); ?></h3>
<h3><?php echo '自己紹介<br><br><br>'.$this->escape($user['intro']); ?></h3>


<?php if (!is_null($following)): ?>

<?php if ($following): ?>
<p>フォローしています</p>
<form action="<?php echo $base_url; ?>/follow/off" method="post">
  <input type="hidden" name="following_name" value="<?php  ?>" />
  <input type="hidden" name="_token" value="<?php echo $this->escape($_token); ?>" />
  <input type="submit" value="解除する" />

<?php else: ?>
<form action="<?php echo $base_url; ?>/follow/on" method="post">
    <input type="hidden" name="_token" value="<?php echo $this->escape($_token); ?>" />
    <input type="hidden" name="following_name" value="<?php echo $this->escape($user['user_name']); ?>" />

    <input type="submit" value="フォローする" />
</form>
<?php endif; ?>

<?php endif; ?>

フォロー：<a href="<?php echo $base_url; ?>/user/<?php echo $this->escape($user['id']); ?>/follow">
  <?php echo $this->escape($followsum[0]['count']); ?>
</a>

フォロワー：<a href="<?php echo $base_url; ?>/user/<?php echo $this->escape($user['id']);  ?>/follower">
<?php echo $this->escape($followersum[0]['count']); ?>
</a>

<br>
<a href="<?php echo $base_url; ?>/status/showfav/<?php echo $user['id']; ?>">お気に入りを見る</a><!--追加-->
<br>
<br><h3>投稿一覧</h3>
<div id="">
    <?php foreach ($statuses as $status): ?>
    <?php echo $this->render('status/status', array('status' => $status,'user'=>$user,'list'=>$list)); ?>
    <?php endforeach; ?>
</div>
