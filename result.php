<?php 

$host = "mysql:host=localhost;dbname=social";
$user = "root";
$pass = "";

try
{
    $con = new PDO($host,$user,$pass);
    $con->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
}
catch(PDOException $e)
{
    echo "failed to connect " . $e->getMessage();
}
?>

<?php 
session_start();
if($_SERVER["REQUEST_METHOD"] == 'POST')
{
    if($_POST["action"] == "addlike")
    {
        $postid = filter_var($_POST["post_id"], FILTER_SANITIZE_NUMBER_INT);
        $uid = $_SESSION["uid"];
                    
        $stmt = $con->prepare("INSERT INTO likes (user_id , post_id ) VALUES (:u , :p)");
        $stmt->execute(array(
            'u' => $uid,
            'p' => $postid
        ));

        echo "hi";
    }
    if($_POST["action"] == "delelike")
    {
        $uid = $_SESSION["uid"];
        $postid = filter_var($_POST["post_id"], FILTER_SANITIZE_NUMBER_INT);
        $stmt = $con->prepare("DELETE FROM likes WHERE post_id = ? AND user_id = ?");
        $stmt->execute(array($postid,$uid));
        echo "hi";
    }
} 
