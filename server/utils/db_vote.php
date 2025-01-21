<?php
require '../configuration/database.php';
require './db_post_data.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $data = json_decode(file_get_contents('php://input'), true);

    if (!isset($data['user_id']) || !isset($data['post_id']) || !isset($data['vote_type'])) {
        echo json_encode(['success' => false, 'message' => 'Missing required parameters.']);
        exit;
    }

    $userId = $data['user_id'];
    $postId = $data['post_id'];
    $voteType = $data['vote_type'];

    if ($voteType === 'delete') {
        $deleteStmt = $conn->prepare("DELETE FROM user_reaction WHERE user_id = ? AND post_id = ?");
        $deleteStmt->bind_param("ii", $userId, $postId);

        if ($deleteStmt->execute()) {
            $votesData = fetchVotesCount($conn, $postId, $userId);

            echo json_encode([
                'success' => true,
                'message' => 'Vote processed successfully.',
                'votesData' => $votesData
            ]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to delete vote.']);
        }

        $deleteStmt->close();

        exit();
    }

    $checkStmt = $conn->prepare("SELECT user_id, post_id, reaction FROM user_reaction WHERE user_id = ? AND post_id = ?");
    $checkStmt->bind_param("ii", $userId, $postId);

    if ($checkStmt->execute()) {
        $checkStmt->bind_result($checkedUserId, $checkedPostId, $checkedVoteType);
        $voteExists = $checkStmt->fetch();
        $checkStmt->close();

        if (!$voteExists) {
            $insertStmt = $conn->prepare("INSERT INTO user_reaction (user_id, post_id, reaction) VALUES (?, ?, ?);");
            $insertStmt->bind_param("iis", $userId, $postId, $voteType);
            $insertStmt->execute();
            $insertStmt->close();
        } else {
            if ($checkedVoteType !== $voteType) {
                $updateStmt  = $conn->prepare("
                    UPDATE user_reaction
                    SET reaction = ?
                    WHERE user_id = ? AND post_id = ?;
                ");

                $updateStmt->bind_param("sii", $voteType, $userId, $postId);
                $updateStmt->execute();
                $updateStmt->close();
            }
        }

        $votesData = fetchVotesCount($conn, $postId, $checkedUserId);

        echo json_encode([
            'success' => true,
            'message' => 'Vote processed successfully.',
            'votesData' => $votesData
        ]);
    } else {
        error_log('Query failed: ' . $checkStmt->error);
    }
}
