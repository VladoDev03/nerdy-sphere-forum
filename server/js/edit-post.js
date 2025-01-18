const textAreaValue = document.getElementById('hidden-value');
const textarea = document.getElementById('content');

textarea.value = textAreaValue.value;

const imageElements = document.querySelectorAll('.image-container');

const imagesToDelete = [];

imageElements.forEach(i => {
    i.addEventListener('click', e => {
        const imageId = i.getAttribute('data-image-id');
        const postId = i.getAttribute('data-post-id');

        const userConfirmed = confirm('Are you sure you want to delete this image?');

        if (!userConfirmed) {
            return;
        }

        imagesToDelete.push(imageId);
        e.target.closest('.image-container').remove();
    });
});

const submitEdit = document.getElementById('edit-form');

submitEdit.addEventListener('submit', e => {
    const formData = new FormData();

    imagesToDelete.forEach((item, index) => {
        formData.append(`array[${index}]`, item);
    });

    fetch('../utils/db_delete_image.php', {
        method: 'POST',
        body: formData
    })
        .then(res => res.text())
        .then(res => {
            console.log(res);
        })
        .catch(err => {
            alert('An error occurred. ' + err);
        });
})
