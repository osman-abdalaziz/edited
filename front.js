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
        checkLike();
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
        checkLike();
    });

    function checkLike(){

        $.ajax({
            method: "POST",
            url: "check.php",
            success: function (data) {
                $("#like-button").html(data);
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

checkLike();