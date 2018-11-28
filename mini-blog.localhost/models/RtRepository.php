<?php
class RtRepository extends DbRepository{

  public function rt_update($post_id,$rt_user,$bool){
    if($bool===true){
      $now = new DateTime();
    $sql="
    insert into rt (rt_user,post_id,rt_at) values(:rt_user,:post_id,:rt_at)
    ";

      $this->execute($sql, array(
        ':post_id'        => $post_id,
        ':rt_user' => $rt_user,
        ':rt_at' => $now->format('Y-m-d H:i:s')
      ));

    }elseif($bool===false){
      $sql="
      delete from rt where rt_user=:rt_user and post_id=:post_id
      ";

        $stmt = $this->execute($sql, array(
          ':post_id'        => $post_id,
          ':rt_user' => $rt_user,
        ));

      }

  }

  public function fetchRtUserByPostId($post_id){
$sql="
select rt.*,u.user_name,u.name
from rt
left join user u on rt.rt_user=u.id
where rt.post_id=:post_id
";

$info=$this->fetchAll($sql, array(
  ':post_id'        => $post_id,
));

$sum=count($info);
$info['sum']=$sum;

return $info;
  }


  public function fetchRtPostByUserid($user_id){
  $sql="
  select post_id from rt where rt_user=:user_id
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
