<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>File Upload with 5MB Chunks</title>
    <!-- Include jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

    <!-- Include jQuery File Upload plugin -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/blueimp-file-upload/10.12.0/js/vendor/jquery.ui.widget.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/blueimp-file-upload/10.12.0/js/jquery.iframe-transport.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/blueimp-file-upload/10.12.0/js/jquery.fileupload.js"></script>
</head>
<body>

<input type="file" id="fileInput" name="file" multiple>

<div id="progress">
    <div class="bar" style="width: 0%;"></div>
</div>

<script>
    $(function () {
        var fileInput = $('#fileInput');
        var progressBar = $('#progress .bar');

        fileInput.fileupload({
            url: '{{route('upload-chunk')}}', // Replace with your actual upload endpoint
            dataType: 'json',
            sequentialUploads: true,
            singleFileUploads: false,
            maxChunkSize: 1000000, // Set max chunk size to 5 MB

            add: function (e, data) {
                // You can perform additional actions when a file is added
                // For example, display file information or validate file type/size
                console.log('File added:', data.files[0].name);

                // Add custom data to the request
                data.formData = {
                    totalChunks: Math.ceil(data.files[0].size / data.originalFiles[0].maxChunkSize),
                    currentChunk: 1, // Initialize current chunk to 1
                };

                // Start the upload when a file is added
                data.submit();
            },

            progress: function (e, data) {
                var progress = parseInt(data.loaded / data.total * 100, 10);
                progressBar.css('width', progress + '%');
            },

            send: function (e, data) {
                // Update the current chunk information before sending
                data.formData.currentChunk = Math.ceil(data.loaded / data.originalFiles[0].maxChunkSize);
            },

            done: function (e, data) {
                console.log('Upload finished:', data.result);
                // You can handle the response from the server here
            }
        });
    });
</script>

</body>
</html>
