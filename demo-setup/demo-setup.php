<?php
/*
"Peace News Ecosystem" is a CMS developed to allow small groups with no tech' expertise to have an internet presence. Its USP is freedom from choice. You can see one installation of Peace News Ecosystem at https://zylum.org/
Copyright (C) 2014 Zylum Ltd.
admin@zylum.org / 5 Caledonian Rd, London, N1 9DY

Version one of Peace News Ecosystem was authored by http://www.wave.coop/ info@wave.coop

This program is free software: you can redistribute it and/or modify it under the terms of the GNU Affero General Public License as published by the Free Software Foundation, either version 3 of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU Affero General Public License for more details.

You should have received a copy of the GNU Affero General Public License along with this program.  If not, see http://www.gnu.org/licenses/agpl-3.0.html
*/

/**
Add demo users to database.
Usage: php demo-setup.php (in terminal) (update function calls if required) 
Uses control/config.php for $salt and $dbpdo 

*/

//load config 
require ("../control/config.php"); 

function clear_users() {
    global $dbpdo;

    $stmt = $dbpdo->prepare(" DELETE FROM `users`; ");
    $stmt->execute();
}

function add_user($name, $email, $password) {
    global $dbpdo;
    global $salt;

    // add user
    $query = "INSERT INTO `users` (`name`, `email`, `password`) VALUES (:name, :email, :password);";
    $stmt = $dbpdo->prepare($query);
    $stmt->execute(array(
        ':name' => $name,
	':email' => $email,
        ':password' => md5($salt.$password)
        ));

    // return user id
    $query = "SELECT id FROM `users` WHERE `name` = :name and `email` = :email;";
    $stmt = $dbpdo->prepare($query);
    $stmt->execute(array(
        ':name' => $name,
        ':email' => $email,
        ));
    $record = $stmt->fetch(PDO::FETCH_ASSOC);
    return $record['id'];
}

function clear_websites() {
    global $dbpdo;

    $stmt = $dbpdo->prepare(" DELETE FROM `website`; ");
    $stmt->execute();
}

function add_website( $name = 'Name', $descr = 'Description', $pages = 1,  $logo = 0,  $title_pic = 0, $color = 'white', 
    $email = 0,  $fb = 'facebook.com', $twitter = 'twitter.com', $active = 'Yes', $on = 1, $why = 'Testing',  $title = 'Title', $url = 'website') {

    global $dbpdo;

    $query = "INSERT INTO `website` ";
    $query .= "(`name`, `descr`, `pages`, `logo`, `title_pic`, `color`, `email`, `fb`, `twitter`, `active`, `on`, `why`, `title`, `url`) ";
    $query .= " VALUES (";
    $query .= ":name, :descr, :pages, :logo, :title_pic, :color, :email, :fb, :twitter, :active, :on, :why, :title, :url";
    $query .= ")";	

    $stmt = $dbpdo->prepare($query);

    $stmt->execute(array(
	':name' => $name,
	':descr' => $descr,
	':pages' => $pages,
	':logo' => $logo,
	':title_pic' => $title_pic, 
	':color' => $color,
	':email' => $email,
	':fb' => $fb,
	':twitter' => $twitter,
	':active' => $active, 
	':on' => $on,
	':why' => $why, 
	':title' => $title,
	':url' => $url
    ));

    return $dbpdo->lastInsertId();

}

function clear_website_users() {
    global $dbpdo;

    $stmt = $dbpdo->prepare(" DELETE FROM `website_user`; ");
    $stmt->execute();
}

function add_website_user($user_id, $website_id, $role_on_website = 'Contributor', $email_preferences = 'email', $valid = '', $registration_date_on_website = '0000-00-00 00:00:00', $last_login_date = '0000-00-00 00:00:00') {
    global $dbpdo;

    $query = "INSERT INTO `website_users` ";
    $query .= " (`user_id`, `website_id`, `role_on_Website`, `email_preferences`, `valid`, `registration_date_on_website`, `last_login_date`) ";
    $query .= " VALUES (";
    $query .= ":user_id, :website_id, :role_on_website, :email_preferences, :valid, :registration_date_on_website, :last_login_date";
    $query .=  " )";

    $stmt = $dbpdo->prepare($query);
    $stmt->execute(array(
	':user_id' => $user_id,
	':website_id' => $website_id,
	':role_on_website' => $role_on_website,
	':email_preferences' => $email_preferences,
	':valid' => $valid,
	':registration_date_on_website' => $registration_date_on_website,
	':last_login_date' => $last_login_date
    ));
}

function show_users() {
    global $dbpdo;

    $stmt = $dbpdo->prepare(" SELECT * FROM `users`; ");
    $stmt->execute();
    while ($record = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo $record['name'].' '.$record['email']."\n";
    }
}

clear_users();
clear_websites();

$user_id = add_user('user','user@ecosystem','pass');
show_users();

$website_id = add_website();
echo "Created website id: ".$website_id."\n";

add_website_user($user_id, $website_id);

echo "Done"."\n";
?>
