const currentPort = window.location.port;
const editIcons = document.querySelectorAll('.edit-icon');

editIcons.forEach(icon => {
    icon.addEventListener('click', e => {
        e.stopPropagation();
        e.preventDefault();

        const postId = e.target.dataset.id;

        window.location = `http://localhost:${currentPort}/server/edit_post.php?id=${postId}`;
    });
});
