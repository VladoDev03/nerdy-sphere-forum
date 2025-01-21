const shareIcons = document.querySelectorAll('.share-icon');

shareIcons.forEach(icon => {
    icon.addEventListener('click', e => {
        e.stopPropagation();
        e.preventDefault();

        const postUrl = icon.getAttribute('data-url');

        navigator.clipboard.writeText(window.location.origin + '/server/' + postUrl).then(() => {
            icon.classList.remove('fa-share-alt');
            icon.classList.add('fa-check');
            icon.title = 'Copied!';

            setTimeout(() => {
                icon.classList.remove('fa-check');
                icon.classList.add('fa-share-alt');
                icon.title = 'Share this post';
            }, 2000);
        }).catch(err => {
            console.error('Failed to copy the URL: ', err);
        });
    });
});
