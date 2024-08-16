CREATE DATABASE task_management;
CREATE TABLE user(
    id int PRIMARY_KEY auto_increment;
    email varchar(100) UNIQUE;
    password varchar(50);
    role varchar(50);
);
CREATE TABLE user_profile (
    user_id int,
 id int PRIMARY KEY AUTO_INCREMENT,
  first_name varchar(50) not null,
   last_name varchar(50) not null, 
   gender varchar(7) not null,
address text not null, 
    cnic varchar(13), 
    date_of_birth date not null,
    image longblob,
    FOREIGN KEY (user_id) REFERENCES user(id));
CREATE TABLE task(
    id int PRIMARY KEY AUTO_INCREMENT,
    title varchar(255) UNIQUE,
    description varchar(255),
    status varchar(40),
    tag varchar(50),
    FOREIGN KEY (assignee_id) REFERENCES user(id)
);
CREATE TABLE task_assignment(
    task_id int,
    assignee_id int
    FOREIGN KEY (assignee_id) REFERENCES user(id),
    FOREIGN KEY (task_id) REFERENCES task(id),
    PRIMARY KEY (task_id, assignee_id)
);
