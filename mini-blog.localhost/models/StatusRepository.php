<?php

/**
 * StatusRepository.
 *
 * @author Katsuhiro Ogawa <fivestar@nequal.jp>
 */
class StatusRepository extends DbRepository
{
    public function insert($user_id, $body,$reply=false,$reply_to=0)
    {
        $now = new DateTime();

        if($reply===false){

        $sql = "
            INSERT INTO status(user_id, body, created_at)
                VALUES(:user_id, :body, :created_at)
        ";

        $stmt = $this->execute($sql, array(
            ':user_id'    => $user_id,
            ':body'       => $body,
            ':created_at' => $now->format('Y-m-d H:i:s'),
        ));

      }elseif($reply===true){
        $sql = "
            INSERT INTO status(user_id, body,reply_to, created_at)
                VALUES(:user_id, :body,:reply_to, :created_at)
        ";

        $stmt = $this->execute($sql, array(
            ':user_id'    => $user_id,
            ':body'       => $body,
            ':reply_to'    => $reply_to,
            ':created_at' => $now->format('Y-m-d H:i:s'),
        ));
      }
    }



    public function fetchAllPersonalArchivesByUserId($user_id)//変更
    {
      $statuses=array();
      $user=array();
      $rt=array();

        $sql = "
            SELECT a.*, u.user_name,u.name,fav.*,
            r_user.user_name as r_user_name,r_user.name as r_name
            FROM status a
                LEFT JOIN user u ON a.user_id = u.id
                LEFT JOIN following f ON f.following_id = a.user_id
                    AND f.user_id = :user_id
                LEFT JOIN fav ON fav.post_id=a.id
                LEFT JOIN status r_status ON a.reply_to=r_status.id
                LEFT JOIN user r_user ON r_user.id=r_status.user_id
                WHERE f.user_id = :user_id OR u.id = :user_id
                ORDER BY a.created_at DESC
        ";//変更

        $user= $this->fetchAll($sql, array(':user_id' => $user_id));

        $sql="
        SELECT a.*, u.user_name,u.name,rt.*,ub.name as rt_name,fav.*,
        r_user.user_name as r_user_name,r_user.name as r_name
            FROM status a
                LEFT JOIN user u ON a.user_id = u.id
                LEFT JOIN rt ON a.id=rt.post_id
                LEFT JOIN user ub ON rt.rt_user=ub.id
                LEFT JOIN fav ON fav.post_id=a.id
                LEFT JOIN status r_status ON a.reply_to=r_status.id
                LEFT JOIN user r_user ON r_user.id=r_status.user_id
            WHERE rt.rt_user IN (
              SELECT following_id from following where user_id=:user_id)
              OR rt.rt_user =:user_id
            ORDER BY rt.rt_at DESC
        ";
        $rt= $this->fetchAll($sql, array(':user_id' => $user_id));

        $sql="
        select fav.post_id from fav where fav.fav_user=:user_id
        ";

        $fav= $this->fetchAll($sql, array(':user_id' => $user_id));

        $i=0;
        $j=0;
        $k=0;

        while(isset($user[$i]) || isset($rt[$j])){
        if(!isset($user[$i])){
          $statuses[$k]=$rt[$j];
          $j++;
        }elseif(!isset($rt[$j])){
          $statuses[$k]=$user[$i];
          $i++;
        }elseif($user[$i]['created_at']>=$rt[$j]['rt_at']){
            $statuses[$k]=$user[$i];
            $i++;
          }elseif($user[$i]['created_at']<$rt[$j]['rt_at']){
            $statuses[$k]=$rt[$j];
            $j++;
          }//ＤＢ後でなおす
          $k++;
        }

        return $statuses;
    }

    public function fetchAllByUserId($user_id)//変更
    {
      $statuses=array();
      $user=array();
      $rt=array();

        $sql = "
            SELECT a.*, u.user_name,u.name,fav.*,
            r_user.user_name as r_user_name,r_user.name as r_name
            FROM status a
            LEFT JOIN user u ON a.user_id = u.id
            LEFT JOIN fav ON fav.post_id=a.id
            LEFT JOIN status r_status ON a.reply_to=r_status.id
            LEFT JOIN user r_user ON r_user.id=r_status.user_id
            WHERE u.id = :user_id
            ORDER BY a.created_at DESC
        ";

         $user=$this->fetchAll($sql, array(':user_id' => $user_id));

         $sql="
         SELECT a.*, u.user_name,u.name,rt.*,ub.name as rt_name,fav.*,
         r_user.user_name as r_user_name,r_user.name as r_name
             FROM status a
                 LEFT JOIN user u ON a.user_id = u.id
                 LEFT JOIN rt ON a.id=rt.post_id
                 LEFT JOIN user ub ON rt.rt_user=ub.id
                 LEFT JOIN fav ON fav.post_id=a.id
                 LEFT JOIN status r_status ON a.reply_to=r_status.id
                 LEFT JOIN user r_user ON r_user.id=r_status.user_id
             WHERE rt.rt_user=:user_id
             ORDER BY rt.rt_at DESC
             ";
             $rt=$this->fetchAll($sql, array(':user_id' => $user_id));

             $i=0;
             $j=0;
             while(isset($user[$i]) || isset($rt[$j])){
             if(!isset($user[$i])){
               $statuses[]=$rt[$j];
               $j++;
             }elseif(!isset($rt[$j])){
               $statuses[]=$user[$i];
               $i++;
             }elseif($user[$i]['created_at']>=$rt[$j]['rt_at']){
                 $statuses[]=$user[$i];
                 $i++;
               }elseif($user[$i]['created_at']<$rt[$j]['rt_at']){
                 $statuses[]=$rt[$j];
                 $j++;
               }
             }

             return $statuses;
    }

    public function fetchByIdAndUserName($id, $user_name)
    {
        $sql = "
            SELECT a.* , u.user_name,u.name,
             r_user.user_name as r_user_name,r_user.name as r_name
                FROM status a
                    LEFT JOIN user u ON u.id = a.user_id
                    LEFT JOIN status r_status ON a.reply_to=r_status.id
                    LEFT JOIN user r_user ON r_user.id=r_status.user_id
                WHERE a.id = :id
                    AND u.user_name = :user_name
        ";//変更

        return $this->fetch($sql, array(
            ':id'        => $id,
            ':user_name' => $user_name,
        ));
    }

    public function fetchAllStatus(){//追加 全投稿取得
      $sql="
      SELECT a.* , u.user_name,u.name,
      r_user.user_name as r_user_name,r_user.name as r_name
          FROM status a
              LEFT JOIN user u ON u.id = a.user_id
              LEFT JOIN status r_status ON a.reply_to=r_status.id
              LEFT JOIN user r_user ON r_user.id=r_status.user_id
              order by created_at desc";

      return $this->fetchAll($sql);
    }

   public function fetchSearchByKey($key){
  $sql=<<<EOD
  SELECT a.* , u.user_name,u.name,
    r_user.user_name as r_user_name,r_user.name as r_name
  FROM status a
  LEFT JOIN user u ON u.id = a.user_id
  LEFT JOIN status r_status ON a.reply_to=r_status.id
  LEFT JOIN user r_user ON r_user.id=r_status.user_id
  WHERE a.body LIKE '%{$key}%'
  order by created_at desc
EOD;

    return $this->fetchAll($sql);
  }

public function fetchReactionByUserId($user_id){
  $sql="
  SELECT a.* , u.user_name,u.name,
  r_user.user_name as r_user_name,r_user.name as r_name
  FROM status a
  LEFT JOIN user u ON u.id = a.user_id
  LEFT JOIN status r_status ON a.reply_to=r_status.id
  LEFT JOIN user r_user ON r_user.id=r_status.user_id
  WHERE r_status.user_id=:user_id
  order by created_at desc
  ";

  $reply=$this->fetchAll($sql,array(
  ':user_id'=>$user_id
));

$sql="
SELECT a.* , u.user_name,u.name,rt.*,ub.name as rt_name,
  r_user.user_name as r_user_name,r_user.name as r_name
FROM rt
    LEFT JOIN status a ON a.id=rt.post_id
    LEFT JOIN user u ON a.user_id = u.id
    LEFT JOIN user ub ON rt.rt_user=ub.id
    LEFT JOIN status r_status ON a.reply_to=r_status.id
    LEFT JOIN user r_user ON r_user.id=r_status.user_id
    WHERE a.user_id=:user_id
    ORDER BY rt.rt_at DESC
    ";

    $rt=$this->fetchAll($sql,array(
    ':user_id'=>$user_id
  ));

  $sql="
  SELECT a.* , u.user_name,u.name,fav.*,ub.name as fav_name,
  r_user.user_name as r_user_name,r_user.name as r_name
  FROM fav
      LEFT JOIN status a ON a.id=fav.post_id
      LEFT JOIN user u ON a.user_id = u.id
      LEFT JOIN user ub ON fav.fav_user=ub.id
      LEFT JOIN status r_status ON a.reply_to=r_status.id
      LEFT JOIN user r_user ON r_user.id=r_status.user_id
      WHERE a.user_id=:user_id
      ORDER BY fav.fav_at DESC
  ";

  $fav=$this->fetchAll($sql,array(
  ':user_id'=>$user_id
));

$statuses=array();
$statuses['reply']=$reply;
$statuses['rt']=$rt;
$statuses['fav']=$fav;

return $statuses;
}

public function fetchReplyByPostId($post_id){
  $a=array();
$a[]=$this->getReplyRoot($post_id);
$root_id=$a[0]['id'];

$b=$this->getChild($root_id);
foreach($b as $c){
  $a[]=$c;
}
// $b=$this->getChild($root_id);
// var_dump($b);
// exit();
return $a;
}


private function getReplyRoot($post_id){
  $sql="
  SELECT a.*,u.user_name,u.name,
  r_user.user_name as r_user_name,r_user.name as r_name
  FROM status a
  LEFT JOIN user u ON u.id = a.user_id
  LEFT JOIN status r_status ON a.reply_to=r_status.id
  LEFT JOIN user r_user ON r_user.id=r_status.user_id
  WHERE a.id=:post_id
  ";

 $parent=$this->fetch($sql,array(
 ':post_id'=>$post_id
));
if(isset($parent['reply_to'])){
  $root=$this->getReplyRoot($parent['reply_to']);
}else{
  $root=$parent;
}
return $root;
}

private function getChild($root_id){
$sql="
SELECT a.*,u.user_name,u.name,
r_user.user_name as r_user_name,r_user.name as r_name
FROM status a
LEFT JOIN user u ON u.id = a.user_id
LEFT JOIN status r_status ON a.reply_to=r_status.id
LEFT JOIN user r_user ON r_user.id=r_status.user_id
WHERE a.reply_to =:root_id
order by created_at asc
";
$childs=$this->fetchAll($sql,array(
':root_id'=>$root_id
));
$statuses=array();

if(isset($childs)){
foreach ($childs as $child) {
  $statuses[]=$child;
}

$i=0;
$childs_id=array();

while(isset($childs[$i])){
$childs_id[]=$childs[$i]['id'];
$i++;
}

$j=0;
while(isset($childs_id[$j])){
  $gets=$this->getChild($childs_id[$j]);
  foreach($gets as $get){
    $statuses[]=$get;
  }
  $j++;
}
return $statuses;

}



}



}
// $i=0; $j=0; $k=0;
// while(isset($reply[$i]) || isset($rt[$j]) || isset($fav[$k])){
//   if(isset($reply[$i]) && isset($rt[$j]) && isset($fav[$k])){
// $a=max($reply[$i]['created_at'],$rt[$j]['rt_at'],$fav[$k]['fav_at']);
//        if($a===$reply[$i]['created_at']){
//        $statuses[]=$reply[$i]['created_at'];
//        $i++;
//
//      }elseif($a===$rt[$j]['rt_at']){
//        $statuses[]=$rt[$j]['rt_at'];
//        $j++;
//
//      }elseif($a===$fav[$k]['fav_at']){
//        $statuses[]=$fav[$k]['fav_at'];
//        $k++;
//
//     }
//
//   }elseif(isset($reply[$i]) && isset($rt[$j])){
//     $a=max($reply[$i]['created_at'],$rt[$j]['rt_at']);
//     if($a===$reply[$i]['created_at']){
//     $statuses[]=$reply[$i];
//     $i++;
//     }elseif($a===$rt[$j]['rt_at']){
//     $statuses[]=$rt[$j];
//     $j++;
//   }
//
//   }elseif(isset($reply[$i]) && isset($fav[$k])){
//     $a=max($reply[$i]['created_at'],$fav[$k]['fav_at']);
//     if($a===$reply[$i]['created_at']){
//     $statuses[]=$reply[$i];
//     $i++;
//     }elseif($a===$fav[$k]['fav_at']){
//       $statuses[]=$fav[$k];
//       $k++;
//     }
//
//   }elseif((isset($rt[$j]) && isset($fav[$k]))){
//     $a=max($rt[$j]['rt_at'],$fav[$k]['fav_at']);
//     if($a===$rt[$j]['rt_at']){
//     $statuses[]=$rt[$j];
//     $j++;
//     }
//     elseif($a===$fav[$k]['fav_at']){
//       $statuses[]=$fav[$k];
//       $k++;
//     }
//
//   }elseif(isset($reply[$i])){
//     foreach($reply as $status){
//       $statuses[]=$status;
//     }
//
//   }elseif(isset($rt[$j])){
//     foreach($rt as $status){
//       $statuses[]=$status;
//     }
//
//   }elseif(isset($fav[$k])){
//     foreach($fav as $status){
//       $statuses[]=$status;
//     }
//
//   }
// }
//
// return $statuses;
//
// }


//まちがえた
    // public function rt_update($id,$rt_user_id,$bool){
    //   if($bool===true){
    //   $sql="
    //   update status set rt_user_id=:rt_user_id where id=:id
    //   ";
    //
    //     $this->execute($sql, array(
    //       ':id'        => $id,
    //       ':rt_user_id' => $rt_user_id,
    //     ));
    //
    //   }elseif($bool===false){
    //     $sql="
    //     update status rt_user_id=null where id=:id
    //     ";
    //
    //       $stmt = $this->execute($sql, array(
    //         ':id'        => $id,
    //         ':rt_user_id' => $rt_user_id,
    //       ));
    //
    //     }
    //
    // }
    //
    //
    // public function fav_update($id,$fav_user_id,$bool){
    //   if($bool===true){
    //   $sql="
    //   update status fav_user_id=:fav_user_id where id=:id
    //   ";
    //
    //     $stmt = $this->execute($sql, array(
    //       ':id'        => $id,
    //       ':fav_user_id' => $fav_user_id,
    //     ));
    //
    //   }elseif($bool===false){
    //     $sql="
    //     update status rt_user_id=null where id=:id
    //     ";
    //
    //       $stmt = $this->execute($sql, array(
    //         ':id'        => $id,
    //         ':fav_user_id' => $fav_user_id,
    //       ));
    //
    //     }
    //
    // }
