<div class="post">
    <div class="post-body">
        <p class="post-info">Posted by: <?= $post['username'] ?> | Category: <?= $post['category'] ?> | Posted
            at: <?= $post['created_at'] ?></p>
        <h2 class="title"><?= $post['title'] ?></h2>
        <p class="content"><?= $post['content'] ?></p>

        <?php if (isset($post['images'])): ?>
            <div class="post-images">
                <?php foreach ($post['images'] as $image): ?>
                    <img class="image" src="<?= $image['url'] ?>" alt="Post Image">
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>

    <form class="comment-form" action="../utils/db_add_comment.php" method="POST">
            <textarea class="comment-input" name="comment-input" id="comment-input-<?= $post['id'] ?>"
                      placeholder="Add a comment..."></textarea>
        <button id="submit-comment-<?= $post['id'] ?>" class="submit-comment">Send Comment</button>
    </form>
    <div id="response"></div>

    <details id="comment-section-<?= $post['id'] ?>" class="comment-section">
        <summary>View Comments (<?= $post['comments_count'] ?>)</summary>
        <?php foreach ($post['comments'] as $comment): ?>
            <div class="comment">
                <?php if ($comment['userId'] === $currentUserId): ?>
                <div class="comment-icons">
                    <i class="fas fa-edit edit-icon edit" data-id="<?= $comment['id'] ?>"></i>
                    <i class="fas fa-trash-alt delete-icon delete-comment-icon delete" data-id="<?= $comment['id'] ?>"></i>
                </div>
                <?php endif; ?>
                <div>
                    <p class="comment-user"><?= $comment['username'] ?>:</p>
                    <p class="comment-content"><?= $comment['content'] ?></p>
                    <p class="comment-info">Posted at: <?= $comment['created_at'] ?></p>

                    <form class="reply-form" action="../utils/db_add_comment.php" method="POST">
                            <textarea class="reply-input" name="reply-input" id="reply-input-<?= $comment['id'] ?>"
                                      placeholder="Add a reply..."></textarea>
                        <input type="hidden" id="post-id-<?= $comment['id'] ?>" value="<?= $post['id'] ?>">
                        <button id="submit-reply-<?= $comment['id'] ?>" class="submit-reply">Send Reply</button>
                    </form>
                    <div id="reply-section-<?= $comment['id'] ?>">
                        <?php if (count($comment['replies']) > 0): ?>
                            <details>
                                <summary>View Replies</summary>
                                <?php foreach ($comment['replies'] as $reply): ?>
                                    <div class="comment-reply">
                                        <?php if ($reply['userId'] === $currentUserId): ?>
                                        <div class="reply-icons">
                                            <i class="fas fa-edit edit-icon edit" data-id="<?= $comment['id'] ?>"></i>
                                            <i class="fas fa-trash-alt delete-icon delete-reply-icon delete" data-id="<?= $reply['id'] ?>"></i>
                                        </div>
                                        <?php endif; ?>
                                        <div>
                                            <p class="comment-user"><?= $reply['username'] ?>:</p>
                                            <p class="comment-content"><?= $reply['content'] ?></p>
                                            <p class="comment-info">Posted at: <?= $reply['created_at'] ?></p>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </details>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

        <?php endforeach; ?>
    </details>
</div>