const buttons = [
    ...document.getElementsByClassName('submit-comment'),
    ...document.getElementsByClassName('submit-reply')
];

Object.values(buttons).forEach(button => button.addEventListener('click', (e) => {
    e.preventDefault();

    let commentType = e.target.getAttribute('id').replace('submit-', '');

    let postId;
    let input;
    let parentId;

    let sectionId;

    let commentExtraData;

    if (commentType.startsWith('comment')) {
        postId = e.target.getAttribute('id').replace('submit-comment-', '');
        input = document.getElementById('comment-input-' + postId).value;

        sectionId = `comment-section-${postId}`;

        document.getElementById('comment-input-' + postId).value = '';
    } else if (commentType.startsWith('reply')) {
        parentId = e.target.getAttribute('id').replace('submit-reply-', '');
        input = document.getElementById('reply-input-' + parentId).value;
        postId = document.getElementById('post-id-' + parentId).value;

        sectionId = `reply-section-${parentId}`;

        document.getElementById('reply-input-' + parentId).value = '';
    }

    commentExtraData = {
        username: currentUsername,
        time: getTime(),
        content: input
    };

    if (input === '') {
        return;
    }

    const comment = {
        parentId: parentId || null,
        postId: postId,
        userId: currentUserId,
        content: input
    }

    fetch('../utils/db_add_comment.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(comment)
    })
        .then(res => res.text())
        .then(data => {
            document.getElementById('response').innerHTML = data;
            addElement(sectionId, comment, commentExtraData);
        })
        .catch(error => {
            document.getElementById('response').innerHTML = 'An error occurred: ' + error; // Handle any errors
        });
}))

const addElement = (parentElementId, ids, content) => {
    const section = document.getElementById(parentElementId);
    const commentDiv = document.createElement('div');

    const userP = createP('comment-user', content.username + ':');
    const contentP = createP('comment-content', content.content);
    const infoP = createP('comment-info', 'Posted at: ' + content.time);

    commentDiv.appendChild(userP);
    commentDiv.appendChild(contentP);
    commentDiv.appendChild(infoP);

    if (ids.parentId) {
        commentDiv.classList.add('comment-reply');
    } else {
        commentDiv.classList.add('comment');
    }

    section.appendChild(commentDiv)
};

const createP = (classToAdd, content) => {
    const element = document.createElement('p');

    element.classList.add(classToAdd);
    element.innerText = content;

    return element;
};

const getTime = () => {
    let now = new Date();

    let year = formatNumber(now.getFullYear());
    let month = formatNumber(now.getMonth());
    let day = formatNumber(now.getDate());
    let hours = formatNumber(now.getHours());
    let minutes = formatNumber(now.getMinutes());
    let seconds = formatNumber(now.getSeconds());

    return `${year}-${month}-${day} ${hours}:${minutes}:${seconds}`;
};

const formatNumber = (number) => {
    return number < 10 ? '0' + number : number;
};
