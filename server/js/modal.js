const modal = document.getElementById('image-modal');
const modalImg = document.getElementById('modal-image');
const closeModal = document.getElementById('close-modal');

document.querySelectorAll('.post-images .image').forEach(image => {
    image.addEventListener('click', () => {
        modal.style.display = 'flex';
        modalImg.src = image.src;
    });
});

closeModal.addEventListener('click', () => {
    modal.style.display = 'none';
});

modal.addEventListener('click', event => {
    if (event.target === modal) {
        modal.style.display = 'none';
    }
});