<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chunked File Upload</title>
</head>
<body>
<input type="file" id="fileInput" />
<button onclick="uploadFile()">Upload</button>

<script>
    async function uploadFile() {
        const fileInput = document.getElementById('fileInput');
        const file = fileInput.files[0];

        if (!file) {
            console.error('No file selected');
            return;
        }

        const chunkSize = 1 * 1024 * 1024; // 1 MB chunks
        const totalChunks = Math.ceil(file.size / chunkSize);
        let currentChunk = 0;

        while (currentChunk < totalChunks) {
            const start = currentChunk * chunkSize;
            const end = Math.min((currentChunk + 1) * chunkSize, file.size);
            const chunk = file.slice(start, end);

            const formData = new FormData();
            formData.append('file', chunk);
            formData.append('chunkNumber', currentChunk + 1);
            formData.append('totalChunks', totalChunks);

            // Adjust the URL to your server endpoint that handles chunk uploads
            const response = await fetch('{{route('upload-chunk')}}', {
                method: 'POST',
                body: formData,
            });

            if (!response.ok) {
                console.error('Error uploading chunk:', response.statusText);
                return;
            }

            currentChunk++;
        }
    }
</script>
</body>
</html>
