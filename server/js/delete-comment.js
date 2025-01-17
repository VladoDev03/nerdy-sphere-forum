const deleteIcons = [
    ...document.querySelectorAll('.delete-comment-icon'),
    ...document.querySelectorAll('.delete-reply-icon')
];

deleteIcons.forEach(icon => {
    icon.addEventListener('click', e => {
        e.stopPropagation();
        e.preventDefault();

        const commentId = e.target.dataset.id;
        const userConfirmed = confirm('Are you sure you want to delete this comment?');

        if (!userConfirmed) {
            return;
        }

        const formData = new FormData();
        formData.append('comment_id', commentId);

        let deleteType = e.target.classList[3].replace('delete-', '').replace('-icon', '');

        fetch('../utils/db_delete_comment.php', {
            method: 'POST',
            body: formData
        })
            .then(res => res.text())
            .then(res => {
                if (deleteType === 'comment') {
                    e.target.closest('.comment').remove();
                } else if (deleteType === 'reply') {
                    e.target.closest('.comment-reply').remove();
                }
            })
            .catch(err => {
                alert('An error occurred. ' + err);
            });
    });
});
