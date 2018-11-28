use

CREATE TABLE user(
    id INTEGER AUTO_INCREMENT,
    user_name VARCHAR(20) NOT NULL,
    password VARCHAR(40) NOT NULL,
    created_at DATETIME,
    PRIMARY KEY(id),
    -- 追加
    name varchar(30) default '名無し',
    intro text,
    -- ここまで
    UNIQUE KEY user_name_index(user_name)
) ENGINE = INNODB;

CREATE TABLE following(
    user_id INTEGER,
    following_id INTEGER,
    PRIMARY KEY(user_id, following_id)
) ENGINE = INNODB;

CREATE TABLE status(
    id INTEGER AUTO_INCREMENT,
    user_id INTEGER NOT NULL,
    body VARCHAR(255),
    created_at DATETIME,
    PRIMARY KEY(id),
    -- 追加
    reply_to int,
    -- ここまで
    INDEX user_id_index(user_id)
) ENGINE = INNODB;

create table fav_rt(
  rt_user int,
  post_id int,
  rt_at DATETIME,
  fav_at DATETIME,
  PRIMARY KEY(rt_user, post_id)
)



ALTER TABLE following ADD FOREIGN KEY (user_id) REFERENCES user(id);
ALTER TABLE following ADD FOREIGN KEY (following_id) REFERENCES user(id);
ALTER TABLE status ADD FOREIGN KEY (user_id) REFERENCES user(id);
