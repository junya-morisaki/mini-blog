<?php

/**
 * StatusRepository.
 *
 * @author Katsuhiro Ogawa <fivestar@nequal.jp>
 */
class FollowingRepository extends DbRepository
{
    public function insert($user_id, $following_id) {
        $sql = "INSERT INTO following VALUES(:user_id, :following_id)";

        $stmt = $this->execute($sql, array(
            ':user_id'      => $user_id,
            ':following_id' => $following_id,
        ));
    }


    public function delete($user_id, $following_id) {
        $sql = "delete
        from following
        where user_id=:user_id and following_id=:following_id";

        $stmt = $this->execute($sql, array(
            ':user_id'      => $user_id,
            ':following_id' => $following_id,
        ));
    }

    public function isFollowing($user_id, $following_id)
    {
        $sql = "
            SELECT COUNT(user_id) as count
                FROM following
                WHERE user_id = :user_id
                    AND following_id = :following_id
        ";

        $row = $this->fetch($sql, array(
            ':user_id'      => $user_id,
            ':following_id' => $following_id,
        ));

        if ($row['count'] !== '0') {
            return true;
        }

        return false;
    }

    public function fetchFollowByUserName($user_id){
      $sql="
      select u.user_name,u.name
      from following f
      left join user u on f.following_id=u.id
      where f.user_id=:user_id
      ";

      $follow=$this->fetchAll($sql, array(
        ':user_id' => $user_id
      ));

      return $follow;
    }


    public function fetchFollowerByUserName($user_id){
      $sql="
      select u.user_name,u.name
      from following f
      left join user u on f.user_id=u.id
      where f.following_id=:user_id
      ";

      $follower=$this->fetchAll($sql, array(
        ':user_id' => $user_id
      ));

      return $follower;
    }

    public function fetchFollowSumByUserId($user_id){

      $sql="
      select COUNT(f.user_id) as count
      from following f
      left join user u on f.following_id=u.id
      where f.user_id= :user_id
      ";

      $sum=$this->fetchAll($sql, array(
        ':user_id' => $user_id
      ));


      return $sum;
    }


    public function fetchFollowerSumByUserId($user_id){
      $sql="
      select count(u.id) as count
      from following f
      left join user u on f.user_id=u.id
      where f.following_id= :user_id
      ";

      $sum=$this->fetchAll($sql, array(
        ':user_id' => $user_id
      ));

      return $sum;
    }
}
