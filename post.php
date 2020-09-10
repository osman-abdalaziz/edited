<?php
ob_flush();
$title = "post";
session_start();
if (isset($_SESSION["email"])) {
    include "init.php";
    if ($_SERVER["REQUEST_METHOD"] == 'POST') {

        if (isset($_POST["postadd"])) {
            $formerr = array();

            $content = $_POST["post"];

            if (empty($content)) {
                $formerr[] = "post can't be <B>Empty</B>";
            }
            if (empty($formerr)) {
                $date = date("Y M,d");
                $time = date("h:i");
                $uid  = $_SESSION["uid"];
                $dayofyears = date("ymd");

                $stmt = $con->prepare("INSERT INTO post (user_id , content , img_id ,`date`,`time`,day_years) VALUES (:u , :c , :i , :d, :t , :y) ");
                $stmt->execute(array(
                    'u' => $uid,
                    'c' => $content,
                    'i' => 0,
                    'd' => $date,
                    't' => $time,
                    'y' => $dayofyears
                ));
                if ($stmt == true) {
                    $suc = "<div class='alert alert-success'>the post upload successfully</div>";
                }
            }
        }
        if (isset($_POST["addcomment"])) {
            $formerr = array();

            $newcomment = filter_var($_POST["comment"], FILTER_SANITIZE_STRING);
            $post_id    = $_POST["post_id"];

            if (empty($newcomment)) {
                $formerr[] = "must be wrote any thing";
            }

            if (empty($formerr)) {
                $date = date("Y M,d");
                $time = date("h:i");
                $dayofyears = date("ymd");
                $uid = $_SESSION["uid"];
                $stmt = $con->prepare("INSERT INTO comment (comm_con , user_id , post_id  , `date` , `time` , day_year) VALUES (:c , :u ,:p ,:d , :t , :y)");
                $stmt->execute(array(
                    'c' => $newcomment,
                    'u' => $uid,
                    'p' => $post_id,
                    'd' => $date,
                    't' => $time,
                    'y' => $dayofyears
                ));
                if ($stmt) {
                } else {
                    echo "no upload comment";
                }
            }
        }
        if (isset($_POST["like"])) {
            $postid = filter_var($_POST["post_id"], FILTER_SANITIZE_NUMBER_INT);
            $uid = $_SESSION["uid"];

            $stmt = $con->prepare("INSERT INTO likes (user_id , post_id ) VALUES (:u , :p)");
            $stmt->execute(array(
                'u' => $uid,
                'p' => $postid
            ));
        }
    }

    $stmt = $con->prepare("SELECT post.* , users.* FROM users INNER JOIN post ON post.user_id = users.ID WHERE users.ID = ? order by `time` DESC ");
    $stmt->execute(array($_SESSION["uid"]));
    $allpost = $stmt->fetchAll();
    $path = "../../../../php_mah/social/upload/avatar//";

?>
    <div class="homepage">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-2"></div>
                <div class="col-lg-8">
                    <form action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="POST">
                        <textarea name="post"></textarea>
                        <input type="submit" value="add" class="btn btn-info sub btn-block" name="postadd">
                        <?php
                        if (!empty($formerr)) {
                            foreach ($formerr as $err) {
                        ?>
                                <div class="alert alert-danger"><?php echo $err; ?></div>
                        <?php
                            }
                        }
                        if (isset($suc)) {
                            echo $suc;
                        }
                        ?>
                    </form>
                </div>
                <div class="col-lg-2"></div>
            </div>
            <div class="homepage">
                <div class="row">
                    <div class="col-lg-2"></div>
                    <div class="col-lg-8">
                        <div class="heading">
                            All POSTS
                        </div>
                        <div class="bodyheading">
                            <?php
                            foreach ($allpost as $post) {
                                $day = date("ymd");
                                $dayago = $day - $post["day_years"];
                                if ($dayago == 0) {
                                    $diftime = "Today";
                                } elseif ($dayago == 1) {
                                    $diftime = "Yesterday";
                                } elseif ($dayago >= 2) {
                                    if ($dayago == 7) {
                                        $diftime = "Week Ago";
                                    } elseif ($dayago == 30) {
                                        $diftime = "Month Ago";
                                    } elseif ($dayago == 60) {
                                        $diftime = "2 Month Ago";
                                    } else {
                                        $diftime = $post["date"];
                                    }
                                }

                                /* select all comments**/

                                $stmt = $con->prepare("SELECT users.* , post.*, comment.*  FROM comment 
                            INNER JOIN users ON users.ID = comment.user_id
                            INNER JOIN post  ON post.post_id = comment.post_id
                            WHERE post.post_id = ?");
                                $stmt->execute(array($post["post_id"]));
                                $allcomment = $stmt->fetchAll();
                                $count = $stmt->rowCount();
                            ?>
                                <div class="conpost">
                                    <div class="imagepost image_card">
                                        <?php
                                        if ($post["img"] == 0) {
                                        ?>
                                            <img src="../../../../php_mah/social/include/template/layout/img/imagedefalut.png" alt="">
                                        <?php
                                        } else {
                                        ?>
                                            <img src="<?php echo $path . $post["img"]; ?>" alt="">
                                        <?php
                                        }
                                        ?>
                                        <span class="name"><?php echo $post["fname"] . " " . $post["lname"]; ?></span> <span class="datepost"><?php echo $diftime; ?> <?php echo $post["time"] ?> </span>
                                    </div>
                                    <div class="textpost">
                                        <?php echo $post["content"]; ?>
                                    </div>
                                    <div class="mediaoptin text-center" id="like">
                                        <div class="row">
                                            <span class="col-lg-4">
                                                Share <i class="fas fa-share"></i>
                                            </span>
                                            <span class="col-lg-4 comments">
                                                Comment <i class="fas fa-comment-alt"></i>
                                            </span>
                                            <!-- [ Start ] Like Button Check in check.php -->
                                            <span id="like-button"></span>
                                            <!-- [ End ] Like Button Check in check.php -->
                                        </div>
                                    </div>
                                    <hr>
                                    <?php
                                    if ($count == 0) {
                                    ?>
                                        <div class="allcomments" style="color: #fff;">
                                            No Comment To Show
                                        </div>
                                        <?php
                                    } else {
                                        foreach ($allcomment as $com) {
                                            $day = date("ymd");
                                            $dayago = $day - $com["day_year"];
                                            if ($dayago == 0) {
                                                $diftime = "Today";
                                            } elseif ($dayago == 1) {
                                                $diftime = "Yesterday";
                                            } elseif ($dayago >= 2) {
                                                if ($dayago == 7) {
                                                    $diftime = "Week Ago";
                                                } elseif ($dayago == 30) {
                                                    $diftime = "Month Ago";
                                                } elseif ($dayago == 60) {
                                                    $diftime = "2 Month Ago";
                                                } else {
                                                    $diftime = $com["date"];
                                                }
                                            }

                                        ?>
                                            <div class="allcomments">
                                                <div class="imagecomment image_card">
                                                    <?php
                                                    if ($com["img"] == 0) {
                                                    ?>
                                                        <img src="../../../../php_mah/social/include/template/layout/img/imagedefalut.png" alt="">
                                                    <?php
                                                    } else {
                                                    ?>
                                                        <img src="<?php echo $path . $com["img"]; ?>" alt="">
                                                    <?php
                                                    }
                                                    ?>
                                                    <span class="name"><?php echo $com["fname"] . " " . $com["lname"]; ?></span>
                                                    <span class="datepost"> <?php echo  $diftime; ?> <?php echo $com["time"]; ?> </span>
                                                </div>
                                                <p class="con_comment"><?php echo $com["comm_con"]; ?></p>
                                            </div>
                                    <?php
                                        }
                                    }
                                    ?>
                                    <div class="sendcomment">
                                        <form action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="POST">
                                            <input type="hidden" name="post_id" value="<?php echo $post["post_id"]; ?>">
                                            <input type="text" name="comment" class="form-control">
                                            <input type="submit" value="send" class="btn btn-primary" name="addcomment">
                                        </form>
                                    </div>
                                </div>
                            <?php
                            }
                            ?>
                        </div>
                    </div>
                    <div class="col-lg-2"></div>
                </div>
            </div>
        </div>
    </div>
<?php
    include $foot;
} else {
    header("location:login.php");
}
