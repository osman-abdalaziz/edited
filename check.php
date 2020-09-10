<?php
$stmt  = $con->prepare("SELECT * FROM likes WHERE post_id = ? AND user_id = ?");
$stmt->execute(array($post["post_id"], $_SESSION["uid"]));
$likes = $stmt->rowCount();

if ($likes == 0) {
?>
   <form method="POST" id="addlike">
      <input type="hidden" value="addlike" name="action">
      <input type="hidden" name="post_id" value="<?php echo $post["post_id"]; ?>">
      <button type="submit" class="like" name="like">
         <span>
            Like <i class="far fa-thumbs-up"></i>
         </span>
      </button>
   </form>
<?php } else {
?>
   <form method="POST" id="delelike">
      <input type="hidden" value="delelike" name="action">
      <input type="hidden" name="post_id" value="<?php echo $post["post_id"]; ?>">
      <button type="submit" class="like" name="like">
         <span>
            Like <i class="fas fa-thumbs-up"></i>
         </span>
      </button>
   </form>
<?php
}
?>
