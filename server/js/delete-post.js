const deleteIcons = document.querySelectorAll('.delete-icon');

deleteIcons.forEach(icon => {
    icon.addEventListener('click', e => {
        e.stopPropagation();
        e.preventDefault();

        const postId = e.target.dataset.id;
        const userConfirmed = confirm('Are you sure you want to delete this post?');

        if (!userConfirmed) {
            return;
        }

        const formData = new FormData();
        formData.append('post_id', postId);

        fetch('../utils/db_delete_post.php', {
           method: 'POST',
           body: formData
        })
            .then(res => res.text())
            .then(res => {
                e.target.closest('.container').remove();
            })
            .catch(err => {
                alert('An error occurred. ' + err);
            });
    });
});
