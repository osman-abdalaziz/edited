<?php
$stmt  = $con->prepare("SELECT * FROM likes WHERE post_id = ? AND user_id = ?");
$stmt->execute(array($post["post_id"], $_SESSION["uid"]));
$likes = $stmt->rowCount();

echo $likes;