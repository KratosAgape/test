document.getElementById('blogForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    // Get form values
    const title = document.getElementById('title').value;
    const content = document.getElementById('content').value;
    const file = document.getElementById('file').files[0];

    // Set preview values
    document.getElementById('previewTitle').textContent = title;
    document.getElementById('previewContent').textContent = content;
    
    const previewMedia = document.getElementById('previewMedia');
    previewMedia.innerHTML = '';

    if (file) {
        const fileReader = new FileReader();
        
        fileReader.onload = function(e) {
            if (file.type.startsWith('image/')) {
                const img = document.createElement('img');
                img.src = e.target.result;
                previewMedia.appendChild(img);
            } else if (file.type.startsWith('video/')) {
                const video = document.createElement('video');
                video.src = e.target.result;
                video.controls = true;
                previewMedia.appendChild(video);
            }
        };

        fileReader.readAsDataURL(file);
    }
});
