<a href="<?php echo $base_url ?>/status/reaction/reply">リプライ</a>
<a href="<?php echo $base_url ?>/status/reaction/fav">お気に入り</a>
<a href="<?php echo $base_url ?>/status/reaction/rt">ＲＴ</a>

<?php if($reaction==='reply'): ?>

  <?php $stt=$statuses['reply']; ?>
<div id="statuses">
    <?php foreach ($stt as $status): ?>
    <?php echo $this->render('status/status', array('status' => $status,'user'=>$user,'list'=>$list)); ?>
    <?php endforeach; ?>
</div>

<?php elseif($reaction==='fav'): ?>

  <?php $stt=$statuses['fav']; ?>
  <?php foreach ($stt as $status): ?>
  <?php echo $this->render('status/status', array('status' => $status,'user'=>$user,'fav'=>true,'list'=>$list)); ?>
  <?php endforeach; ?>

<?php elseif($reaction==='rt'): ?>

  <?php $stt=$statuses['rt']; ?>
  <?php foreach ($stt as $status): ?>
  <?php echo $this->render('status/status', array('status' => $status,'user'=>$user,'list'=>$list)); ?>
  <?php endforeach; ?>

<?php endif; ?>
