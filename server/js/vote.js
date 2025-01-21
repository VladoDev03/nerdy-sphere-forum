document.querySelectorAll('.vote-button').forEach(button => {
    button.addEventListener('click', e => {
        const userId = button.getAttribute('data-user-id');
        const postId = button.getAttribute('data-post-id');
        const voteType = button.classList.contains('upvote') ? 'Like' : 'Dislike';

        const upvoteButton = button.closest('.post-votes').querySelector('.upvote');
        const downvoteButton = button.closest('.post-votes').querySelector('.downvote');

        let isVoteRemoved = false;

        if (voteType === 'Like' && upvoteButton.classList.contains('upvoted')) {
            upvoteButton.classList.remove('upvoted');
            isVoteRemoved = true;
        } else if (voteType === 'Dislike' && downvoteButton.classList.contains('downvoted')) {
            downvoteButton.classList.remove('downvoted');
            isVoteRemoved = true;
        } else {
            // Clear previous vote styles
            upvoteButton.classList.remove('upvoted');
            downvoteButton.classList.remove('downvoted');

            // Apply new vote styles
            if (voteType === 'Like') {
                upvoteButton.classList.add('upvoted');
            } else {
                downvoteButton.classList.add('downvoted');
            }
        }

        const vote = {
            user_id: userId,
            post_id: postId,
            vote_type: isVoteRemoved ? 'delete' : voteType
        };

        fetch('../utils/db_vote.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(vote)
        })
            .then(response => response.json())
            .then(data => {
                const newCount = data.votesData.voteDifference;

                console.log(data.votesData);

                document.getElementById(`vote-count-${postId}`).innerText = newCount;
                console.log("Total votes", data.votesData.totalVotes);
            });
    });
});
