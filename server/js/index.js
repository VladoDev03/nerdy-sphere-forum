const buttons = [
    ...document.getElementsByClassName('submit-comment'),
    ...document.getElementsByClassName('submit-reply')
];

const setAddEvent = e => {
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
    };

    fetch('../utils/db_add_comment.php', {
        method: 'POST',
        headers: {'Content-Type': 'application/json'},
        body: JSON.stringify(comment)
    })
        .then(res => res.json())
        .then(data => {
            comment.newId = data.id;

            addElement(sectionId, comment, commentExtraData, e);
        })
        .catch(error => {
            console.log(error);
        });
};

Object.values(buttons).forEach(button => button.addEventListener('click', setAddEvent));

const addElement = (parentElementId, ids, content, e) => {
    let section = document.getElementById(parentElementId);

    if (!section) {
        section = createSection(parentElementId);
        e.target.closest('.reply-form').append(section);
    }

    const commentDiv = document.createElement('div');
    const emptyDiv = document.createElement('div');

    const userP = createP('comment-user', content.username + ':');
    const contentP = createP('comment-content', content.content);
    const infoP = createP('comment-info', 'Posted at: ' + content.time);

    emptyDiv.appendChild(userP);
    emptyDiv.appendChild(contentP);
    emptyDiv.appendChild(infoP);

    if (ids.parentId) {
        commentDiv.classList.add('comment-reply');
    } else {
        commentDiv.classList.add('comment');
        const section = document.getElementById(parentElementId);
        section.appendChild(commentDiv);
        emptyDiv.appendChild(addReplyForm(ids));
    }

    emptyDiv.appendChild(addDeleteButton(ids, true, commentDiv));

    commentDiv.appendChild(emptyDiv);
    section.appendChild(commentDiv);
};

const createSection = parentElementId => {
    const div = document.createElement('div');
    const section = document.createElement('details');
    const summary = document.createElement('summary');

    div.id = parentElementId;
    summary.innerText = 'View Replies';
    section.appendChild(summary);

    return div;
};

const addReplyForm = (ids) => {
    const replyForm = document.createElement('form');
    replyForm.classList.add('reply-form');
    replyForm.action = "../utils/db_add_comment.php";
    replyForm.method = "POST";

    const textarea = document.createElement('textarea');
    textarea.classList.add('reply-input');
    textarea.name = "reply-input";
    textarea.id = "reply-input-" + ids.newId;
    textarea.placeholder = "Add a reply...";

    const hiddenPostId = document.createElement('input');
    hiddenPostId.type = "hidden";
    hiddenPostId.id = "post-id-" + ids.newId;
    hiddenPostId.value = ids.postId;

    const submitButton = document.createElement('button');
    submitButton.id = "submit-reply-" + ids.newId;
    submitButton.classList.add('submit-reply');
    submitButton.innerText = 'Send Reply';
    submitButton.addEventListener('click', setAddEvent);

    replyForm.appendChild(textarea);
    replyForm.appendChild(hiddenPostId);
    replyForm.appendChild(submitButton);


    return replyForm;
};

const addDeleteButton = (ids, isComment, elementToRemove) => {
    const deleteButtonDiv = document.createElement('div');
    deleteButtonDiv.classList.add('comment-icons');

    const deleteIcon = document.createElement('i');
    deleteIcon.classList.add('fas', 'fa-trash-alt', 'delete-icon');
    deleteIcon.classList.add(isComment ? 'delete-comment-icon' : 'delete-reply-icon');
    deleteIcon.classList.add('delete');
    deleteIcon.setAttribute('data-id', ids.newId);

    deleteButtonDiv.appendChild(deleteIcon);

    deleteIcon.addEventListener('click', () => {
        if (confirm('Are you sure you want to delete this comment?')) {
            deleteCommentOrReply(ids.newId, isComment, elementToRemove);
        }
    });

    return deleteButtonDiv;
};

const createP = (classToAdd, content) => {
    const element = document.createElement('p');

    element.classList.add(classToAdd);
    element.innerText = content;

    return element;
};

const deleteCommentOrReply = (id, isComment, elementToRemove) => {
    const formData = new FormData();
    formData.append('comment_id', id);

    fetch('../utils/db_delete_comment.php', {
        method: 'POST',
        body: formData
    })
        .then(res => res.text())
        .then(res => {
            if (isComment) {
                elementToRemove.remove();
            } else {
                elementToRemove.remove();
            }
        })
        .catch(err => {
            alert('An error occurred. ' + err);
        });
};
