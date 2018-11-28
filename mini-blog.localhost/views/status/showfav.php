

<h3><?php echo 'お気に入り一覧';?></h3>
<div id="">
    <?php foreach ($statuses as $status): ?>
    <?php echo $this->render('status/status', array('status' => $status,'user'=>$user,'list'=>$list)); ?>
    <?php endforeach; ?>
</div>
