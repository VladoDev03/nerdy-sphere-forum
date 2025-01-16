const buttons = [...document.getElementsByClassName('submit-comment'), ...document.getElementsByClassName('submit-reply')];

Object.values(buttons).forEach(button => button.addEventListener('click', (e) => {
    e.preventDefault();

    let commentType = e.target.getAttribute('id').replace('submit-', '');

    let postId;
    let input;
    let parentId;

    if (commentType.startsWith('comment')) {
        postId = e.target.getAttribute('id').replace('submit-comment-', '');
        input = document.getElementById('comment-input-' + postId).value;

        document.getElementById('comment-input-' + postId).value = '';
        console.log('comment');
    } else if (commentType.startsWith('reply')) {
        parentId = e.target.getAttribute('id').replace('submit-reply-', '');
        input = document.getElementById('reply-input-' + parentId).value;
        postId = document.getElementById('post-id-' + parentId).value;
        console.log(`----------${postId}----------`)

        document.getElementById('reply-input-' + parentId).value = '';
        console.log('reply');
    }

    if (input === '') {
        return;
    }

    const comment = {
        parentId: parentId || null,
        postId: postId,
        userId: currentUserId,
        content: input
    }

    console.log(comment);

    fetch('index.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(comment)
    })
        .then(res => res.text())
        .then(data => {
            document.getElementById('response').innerHTML = data;
        })
        .catch(error => {
            document.getElementById('response').innerHTML = 'An error occurred: ' + error; // Handle any errors
        });
}))
