<?php $this->setLayoutVar('title', 'プロフィール編集') ?>

<form class="" action="" method="post">
  ユーザー名<br>
  <input type="text" name="name" value="<?php echo $this->escape($user['name']) ?>">
<br><br>自己紹介文<br>
<textarea name="intro" rows="15" cols="100" ><?php echo $this->escape($user['intro']) ?></textarea>
<input type="hidden" name="_token" value="<?php echo $this->escape($_token); ?>" />
<br>
<input type="submit" name="" value="更新">
</form>
