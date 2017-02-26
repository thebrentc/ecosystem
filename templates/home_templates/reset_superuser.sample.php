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
//load config
include ("control/config.php");

function reset_superuser($email = "admin@ecosystem", $password = "ecosystem") {
    global $dbpdo;

    $stmt = $dbpdo->prepare("UPDATE superusers set `password`= :pass WHERE email = :email");
    $stmt->execute(array(
        ':email' => $email,
        ':pass' => md5($salt.$password)
        ));
    
    echo "<p>"."Password for ".$email." should be reset to ".$password."</p>";
}

reset_superuser();
?>
