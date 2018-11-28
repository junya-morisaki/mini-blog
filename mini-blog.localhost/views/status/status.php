
<div class="status">
    <div class="status_content">
      <?php if(isset($fav)){
        echo $status['fav_name'].'さんが以下の投稿をお気に入りにしました';
      } ?>
      <br>

        <a href="<?php echo $base_url; ?>/user/<?php echo $this->escape($status['user_name']); ?>">
            @<?php echo $this->escape($status['user_name']); ?>
        </a>

        <?php echo $this->escape($status['name']); ?>

        <?php if(isset($status['reply_to'])) :?>
        <a href="<?php echo $base_url; ?>/user/<?php echo $this->escape($status['r_user_name']); ?>/status/<?php echo $this->escape($status['reply_to']); ?>/none">
          <?php echo '('.$this->escape($status['r_name']); ?>さんの投稿
        </a>
         <?php echo 'へのリプライ)';  ?>
         <?php endif; ?>

        <?php if(isset($status['rt_at'])){//RTされた投稿ならば
          echo '('.$this->escape($status['rt_name']).'さんが'.$this->escape($status['rt_at']).'にリツイートしました)';
        } ?>

        <br><br>
        <?php echo $this->escape($status['body']); ?>

    </div>
    <div>
      <br>

        <a href="<?php echo $base_url; ?>/user/<?php echo $this->escape($status['user_name']);
        ?>/status/<?php echo $this->escape($status['id']); ?>/none">
            <?php echo $this->escape($status['created_at']); ?>
          </a>
          <?php if(in_array($status['id'],$list['rt'])): ?>
            <form class="" action="<?php echo $base_url; ?>/status/rt/false" method="post">
                <input type="hidden" name="postid" value="<?php echo $this->escape($status['id']); ?>">
              <input type="submit" name="" value="RT解除">
            </form>
        <?php else: ?>
          <form class="" action="<?php echo $base_url; ?>/status/rt/true" method="post">
              <input type="hidden" name="postid" value="<?php echo $this->escape($status['id']); ?>">
            <input type="submit" name="" value="RT">
          </form>
        <?php endif; ?>



        <?php if(in_array($status['id'],$list['fav'])): ?>
            <form class="" action="<?php echo $base_url; ?>/status/fav/false" method="post">
              <input type="submit" name="" value="fav解除">
                <input type="hidden" name="postid" value="<?php echo $this->escape($status['id']); ?>">
            </form>
          <?php else: ?>
            <form class="" action="<?php echo $base_url; ?>/status/fav/true" method="post">
              <input type="submit" name="" value="fav">
                <input type="hidden" name="postid" value="<?php echo $this->escape($status['id']); ?>">
            </form>
          <?php endif; ?>

          <form class="" action="<?php echo $base_url; ?>/status/reply" method="post">
              <input type="hidden" name="postid" value="<?php echo $this->escape($status['id']); ?>">
              <input type="hidden" name="before" value="true" />
            <input type="submit" name="" value="リプライ">
          </form>

          <?php if(isset($rtinfo)){
            $rt_url =<<<EOD
            {$base_url}/user/{$status['user_name']}/status/{$status['id']}/rt
EOD;

            echo 'RT数:<a href ="'.$rt_url.'">'.$this->escape($rtinfo['sum']).'</a>';} ?>
          <?php if(isset($favinfo)){
            $fav_url =<<<EOD
            {$base_url}/user/{$status['user_name']}/status/{$status['id']}/fav
EOD;
            echo 'fav数:<a href ="'.$fav_url.'">'.$this->escape($favinfo['sum']).'</a>';} ?>
    </div>
</div>
