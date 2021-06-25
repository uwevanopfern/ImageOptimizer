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
                <h2>Image details</h2>

                Image : <img src="{{ asset($image->filepath) }}" alt="" title="" width="50" height="50"
                    style="border-radius: 50px">

                <br />

                Number of link shares
                @if($image->link)
                @forelse($image->link->shares as $share)
                <span class="badge badge-dark">{{$share->time}}</span>
                @empty
                <span class="badge badge-dark">0</span>
                @endforelse
                @endif

                <br />
                <ul class="list-group">
                    <li class="list-group-item">File Name: {{$image->filename}}</li>
                    <li class="list-group-item">File Size: {{$image->filesize}}</li>
                    <li class="list-group-item">Mime Type: {{$image->mimetype}}</li>
                    <li class="list-group-item">Uploaded on: {{$image->created_at}}</li>
                </ul>
                <a href="{{ url('/') }}">
                    Back home
                </a>
            </div>
        </div>
    </div>
</body>

</html>