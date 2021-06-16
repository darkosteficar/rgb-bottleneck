<?php
include_once("includes/db.php");

$commentdi = $_POST['str'];
$commentQuery = "SELECT * FROM comments WHERE comment_post_id = ? ORDER BY comment_id DESC";
$sql = $conn->prepare($commentQuery);
$sql->bind_param('i', $commentdi);
$sql->execute();
$results = $sql->get_result();



function time_elapsed_string($datetime, $full = false)
{
    $now = new DateTime;
    $ago = new DateTime($datetime);
    $diff = $now->diff($ago);
    $diff->w = floor($diff->d / 7);
    $diff->d -= $diff->w * 7;
    $string = array('y' => 'year', 'm' => 'month', 'w' => 'week', 'd' => 'day', 'h' => 'hour', 'i' => 'minute', 's' => 'second');
    foreach ($string as $k => &$v) {
        if ($diff->$k) {
            $v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? 's' : '');
        } else {
            unset($string[$k]);
        }
    }
    if (!$full) $string = array_slice($string, 0, 1);
    return $string ? implode(', ', $string) . ' ago' : 'just now';
}




$commentHTML = '';
while ($comment = mysqli_fetch_assoc($results)) {

    $sql1 = $conn->prepare('SELECT * FROM users WHERE user_id = ?');
    $sql1->bind_param('i', $comment['comment_author']);
    $sql1->execute();
    $results1 = $sql1->get_result();
    $user = mysqli_fetch_assoc($results1);
    $userImage = $user['user_image'];
    $timeAgo = $comment['comment_date'];



    $sql2 = $conn->prepare("SELECT * FROM users WHERE user_id = ?");
    $sql2->bind_param("i", $comment['comment_author']);
    $sql2->execute();
    $results2 = $sql2->get_result();
    $userAuthor = mysqli_fetch_assoc($results2);
    $userAuthorName = $userAuthor['username'];


    $commentHTML .= '<div class="media d-block d-md-flex mt-3 border-top border-black rounded p-3">
    <img class="d-flex mb-3 mx-auto " src="userImages/' . $userImage . '" alt="Generic placeholder image">
    <div class="media-body text-center text-md-left ml-md-3 ml-0">
        <h5 class="mt-0 font-weight-bold">' . $userAuthorName . '
            <a href="" class="pull-right text-dark"  style="font-size: 15px;">
                ' . $timeAgo . '
            </a>
        </h5>
        <div class="mw-80">
        ' . $comment['comment_content'] . '
        </div>
    </div>
</div>';;
    //$commentHTML .= getCommentReply($conn, $comment["id"]);
}


echo $commentHTML;
