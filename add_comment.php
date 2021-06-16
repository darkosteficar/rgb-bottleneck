<?php

include("includes/db.php");

$post_date = date('Y-m-d H:i:s');
$post_id = $_POST['post_id'];
if (!empty($_POST["comment_author"]) && !empty($_POST["comment_content"])) {
    $updatePostCommentCount = $conn->prepare("UPDATE posts SET post_comment_count = post_comment_count + 1 WHERE post_id = ?");
    $updatePostCommentCount->bind_param("i", $post_id);
    $updatePostCommentCount->execute();
    $stmt = $conn->prepare("INSERT INTO comments(comment_author, comment_content, comment_date,comment_post_id) VALUES (?,?,?,?)");
    $stmt->bind_param("issi", $_POST["comment_author"], $_POST["comment_content"], $post_date, $post_id);
    if ($stmt->execute()) {
        $message = '<div class="alert alert-success" role="alert">Komentar postavljen. </div>';
        $status = array(
            'error'  => 0,
            'message' => $message
        );
    }
} else {
    $message = '<label class="text-danger">Greška: komentar nije postavljen.</label>';
    $status = array(
        'error'  => 1,
        'message' => $message
    );
}
echo json_encode($status);
