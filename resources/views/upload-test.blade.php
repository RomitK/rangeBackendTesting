<!DOCTYPE html>
<html>

<head>
    <title>Upload Test</title>
</head>

<body>
    <form enctype="multipart/form-data" action="{{ url('/upload-test') }}" method="POST">
        @csrf
        Select file: <input type="file" name="uploaded_file" />
        <input type="submit" value="Upload" />
    </form>
</body>

</html>