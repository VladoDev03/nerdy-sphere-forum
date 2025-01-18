const editIcons = document.querySelectorAll('.edit-icon');

editIcons.forEach(icon => {
    icon.addEventListener('click', e => {
        e.stopPropagation();
        e.preventDefault();

        window.location = 'http://localhost:63342/server/edit_post.php?id=47';
    });
});
