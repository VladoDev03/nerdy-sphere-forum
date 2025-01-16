const buttons = document.getElementsByClassName('submit-comment')

Object.values(buttons).forEach(button => button.addEventListener('click', (e) => {
    e.preventDefault();

    let postId = e.target.getAttribute('id').replace('submit-comment-', '');
    let input = document.getElementById('comment-input-' + postId).value;

    document.getElementById('comment-input-' + postId).value = '';

    if (input === '') {
        return;
    }

    const comment = {
        postId: postId,
        userId: currentUserId,
        content: input
    }

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
