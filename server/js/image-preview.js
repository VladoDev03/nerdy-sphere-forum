const imageInput = document.getElementById('images');
const previewContainer = document.getElementById('image-preview');

imageInput.addEventListener('change', (event) => {
    const files = event.target.files;

    previewContainer.innerHTML = '';

    Array.from(files).forEach((file, index) => {
        if (file.type.startsWith('image/')) {
            const reader = new FileReader();

            reader.onload = function (e) {
                const previewItem = document.createElement('div');
                previewItem.classList.add('preview-item');

                const img = document.createElement('img');
                img.src = e.target.result;
                img.alt = file.name;

                const removeBtn = document.createElement('button');
                removeBtn.classList.add('remove-btn');
                removeBtn.textContent = '×';
                removeBtn.addEventListener('click', () => {
                    const fileArray = Array.from(imageInput.files);
                    fileArray.splice(index, 1);

                    // Create a new FileList object
                    const newFileList = new DataTransfer();
                    fileArray.forEach((f) => newFileList.items.add(f));

                    imageInput.files = newFileList.files;
                    previewItem.remove();
                });

                previewItem.appendChild(img);
                previewItem.appendChild(removeBtn);
                previewContainer.appendChild(previewItem);
            };

            reader.readAsDataURL(file);
        }
    });
});