<?php $this->setLayoutVar('title', 'リプライ') ?>
<?php $postid=$_POST['postid'] ?>


<?php if (isset($errors) && count($errors) > 0 && !isset($_POST['before'])): ?>
<?php echo $this->render('errors', array('errors' => $errors)) ?>
<?php endif; ?>

<form class="" action="" method="post">
<textarea name="body" rows="10" cols="80"></textarea>
<input type="hidden" name="postid" value="<?php echo $postid; ?>">
<br>
<input type="submit" name="" value="送信">
</form>
