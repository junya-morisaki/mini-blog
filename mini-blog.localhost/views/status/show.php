<?php $this->setLayoutVar('title', $status['user_name']) ?>

<?php if($type==='rt') : ?>
  <h2>リツイートしたユーザー一覧</h2>
<?php foreach($rtinfo as $info): ?>
<a href="<?=$base_url; ?>/user/<?=$info['user_name']; ?>"><?php echo $info['name']; ?></a><br>
<?php endforeach; ?>
<?php elseif($type==='fav') : ?>
  <h2>お気に入りしたユーザー一覧</h2>
  <?php foreach($favinfo as $info): ?>
  <a href="<?=$base_url; ?>/user/<?=$info['user_name']; ?>"><?php echo $info['name']; ?></a><br>
<?php endforeach; ?>

<?php else : ?>
<?php echo $this->render('status/status', array(
  'status' => $status,
  'user'  =>$user,
  'rtinfo'=>$rtinfo,
  'favinfo'=>$favinfo,
  'list'  =>$list
)); ?>

<a href="<?php echo $base_url; ?>/status/all_reply/<?php echo $status['id'] ?>">全リプライを見る</a>
<?php endif; ?>
