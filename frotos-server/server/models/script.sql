create table users ( user_id bigint(20) primary key, firstname varchar(50) not null, lastname varchar(50) not null, email varchar(256) not null, fb_id bigint(20) not null, age bigint(5), sex tinyint(1), location varchar(256));

alter table users add (created_at datetime not null);

alter table users add (profile_picture varchar(1024)  not null);

create table friendships (user_id1 bigint(20) not null, user_id2 bigint(20) not null);

create table friends_info (user_id bigint(20) primary key, firstname varchar(20) not null, lastname varchar(20) not null, profile_picture varchar(512) not null, email varchar(256));

create table nonusers ( user_id bigint(20) primary key, firstname varchar(50) not null, lastname varchar(50) not null, fb_id bigint(20) not null, profile_picture varchar(1024) );


create table interventions ( intervention_id bigint(20) primary key, user_id bigint(20) not null, author_id bigint(20) not null, created_at datetime not null, title varchar(1024) not null, description text(11), photo_src varchar(1024));

alter table interventions add (privacy tinyint(4));

create table interventions_users (intervention_id bigint(20) not null, user_id bigint(2) not null, accepted tinyint(4), joined_at datetime);

alter table interventions modify column intervention_id bigint(20) auto_increment;