<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Upload Multiple Files in Laravel 7 with Coding Driver</title>
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.3/css/bootstrap.min.css" />


    <style>
        .invalid-feedback {
            display: block;
        }
    </style>
</head>

<body>

    <div class="container">
        <div class="row">
            <div class="col-md-8 offset-md-1">
                <h2>Rename image</h2>
                <form action="{{ route('images.update', ['image' => $image]) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <input class="form-control" type="text" name="filename" value="{{$image->filename}}">
                    <input class="form-control" type="text" value="{{$image->filesize}}" readonly>
                    <input class="form-control" type="text" value="{{$image->mimetype}}" readonly>
                    <div class="form-group">
                        <button type="submit" class="btn btn-dark">Rename</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>

</html>