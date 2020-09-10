$(function () {


    $("input").focus(function () {

        $(this).attr("dataval", $(this).attr("placeholder"));
        $(this).attr("placeholder", "");
    });

    $("input").blur(function () {

        $(this).attr("placeholder", $(this).attr("dataval"));
    });


    $("#addlike").on("submit", function (e) {
        e.preventDefault();
        $.ajax({
            method: "POST",
            url: "result.php",
            contentType: false,
            processData: false,
            data: new FormData(this),
            success: function (data) {
                $("#addlike")[0].reset();
                console.log(data);
            }
        });
    });

    $("#delelike").on("submit", function (e) {
        e.preventDefault();
        $.ajax({
            method: "POST",
            url: "result.php",
            contentType: false,
            processData: false,
            data: new FormData(this),
            success: function (data) {
                console.log(data);
            }
        });
    });

    function checkLike(){

        $.ajax({
            method: "POST",
            url: "check.php",
            success: function (data) {
                if(data == 0){
                    $("#like-button").html('<form method="POST" id="addlike"><input type="hidden" value="addlike" name="action"><input type="hidden" name="post_id" value="<?php echo $post["post_id"]; ?>"><button type="submit" class="like" name="like"><span>Like <i class="far fa-thumbs-up"></i></span></button></form>');
                } else {
                    $("#like-button").html('<form method="POST" id="delelike"><input type="hidden" value="delelike" name="action"><input type="hidden" name="post_id" value="<?php echo $post["post_id"]; ?>"><button type="submit" class="like" name="like"><span>Like <i class="fas fa-thumbs-up"></i></span></button></form>');
                }
            }
        });

    }

    $("#sendchat").on("submit", function (e) {
        e.preventDefault();
        $.ajax({
            method: "POST",
            url: "result.php",
            contentType: false,
            processData: false,
            data: new FormData(this),
            success: function (data) {
                $("#sendchat")[0].reset();
                console.log(data);
            }
        });
    });
});
