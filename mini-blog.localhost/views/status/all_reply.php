<div id="statuses">
    <?php foreach ($statuses as $status): ?>
    <?php echo $this->render('status/status', array(
      'status' => $status,
      'user'   =>$user,
      'list'=>$list
    )); ?>
    <?php endforeach; ?>
</div>
