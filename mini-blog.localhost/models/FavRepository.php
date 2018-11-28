<?php
class FavRepository extends DbRepository{

  public function fav_update($post_id,$fav_user,$bool){
    if($bool===true){
     $now = new DateTime();
    $sql="
    insert into fav (fav_user,post_id,fav_at) values(:fav_user,:post_id,:fav_at)
    ";

      $this->execute($sql, array(
        ':post_id'  => $post_id,
        ':fav_user' => $fav_user,
        ':fav_at' => $now->format('Y-m-d H:i:s')
      ));

    }elseif($bool===false){
      $sql="
      delete from fav where fav_user=:fav_user and post_id=:post_id
      ";

        $this->execute($sql, array(
          ':post_id'  => $post_id,
          ':fav_user' => $fav_user,
        ));

      }

  }

  public function getfav($user_id){
    $sql="
    SELECT a.*, u.user_name,u.name
    FROM status a
      LEFT JOIN user u ON a.user_id = u.id
        LEFT JOIN fav ON a.id=fav.post_id
        WHERE fav.fav_user=:user_id
        ORDER BY fav.fav_at DESC
    ";

    $statuses= $this->fetchAll($sql, array(':user_id' => $user_id));

    return $statuses;
  }


  public function fetchFavUserByPostId($post_id){
$sql="
select fav.*,u.user_name,u.name
from fav
left join user u on fav.fav_user=u.id
where fav.post_id=:post_id
";

$info=$this->fetchAll($sql, array(
  ':post_id'        => $post_id,
));

$sum=count($info);
$info['sum']=$sum;

return $info;
  }

  public function fetchFavPostByUserid($user_id){
$sql="
select post_id from fav where fav_user=:user_id
";

$post_list=$this->fetchAll($sql, array(
  ':user_id'=> $user_id,
));

$list=array();
foreach($post_list as $id){
  $list[]=$id['post_id'];
}

return $list;

  }


}
 ?>
