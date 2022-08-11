<?php
session_start();
echo json_encode([
    "batch" => $_SESSION["feedbacks"][$_POST["batchId"]]["batch"],
    "feedback" => $_SESSION["feedbacks"][$_POST["batchId"]]["feedbacks"][$_POST["feedbackId"]],
    "sums" => $_SESSION["feedbacks"][$_POST["batchId"]]["sums"]
]);
?>