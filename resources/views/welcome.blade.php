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
                <h2>Upload multiple images </h2>
                <h5>Click on choose file and select many images as you want</h5>
                @if(session()->has('success'))
                <div class="alert alert-success">
                    {{ session()->get('success') }}
                </div>
                @endif

                <form method="post" action="{{ route('images.store') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group">
                        <input type="file" name="images[]" multiple class="form-control" accept="image/*">
                        @foreach ($errors->all() as $error)
                        <div class="alert alert-danger">
                            {{ $error }}<br />
                        </div>
                        @endforeach
                    </div>

                    <div class="form-group">
                        <button type="submit" class="btn btn-dark">Upload</button>
                    </div>
                </form>

                <hr />
                <h4>List of images</h4>
                <form method="get" action="{{ url('/')  }}">
                    @csrf
                    <div class="form-group mx-sm-5 mb-3">
                        <input type="text" class="form-control form-control-sm" name="searchKey"
                            placeholder="Search by image name"><br />
                        <button type="submit" class="btn btn-default btn-sm">SEARCH</button>
                        <a href="/" class="btn btn-dark btn-sm">Clear search results</a><br>
                    </div>
                </form>
                <small>TOTAL NUMBERS OF JPGS</small> :<span class="badge badge-dark">{{$totalJpgs}}</span><br>
                <small>TOTAL NUMBERS OF PNGS</small> :<span class="badge badge-dark">{{$totalPngs}}</span>
                <hr>
                <div class="table-responsive-sm">
                    <table class="table">
                        <thead class="thead-dark">
                            <tr>
                                <th>Name</th>
                                <th>Image</th>
                                <th>Uploaded date</th>
                                <th>Total number of link shares</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>

                            @forelse($images as $image)
                            <tr>
                                <td>{{ $image->filename }}</td>
                                <td>
                                    <img src="{{ asset($image->filepath) }}" alt="" title="" width="50" height="50"
                                        style="border-radius: 50px">
                                </td>
                                <td>{{ $image->created_at }}</td>
                                <td>
                                    @if($image->link)
                                    @forelse($image->link->shares as $share)
                                    <span class="badge badge-dark">{{$share->time}}</span>
                                    @empty
                                    <span class="badge badge-dark">0</span>
                                    @endforelse
                                    @endif
                                </td>

                                <td>
                                    <a href="{{ route('images.edit', ['image' => $image]) }}">
                                        Rename image
                                    </a><br>
                                    <a href="{{ route('images.show', ['image' => $image]) }}">
                                        Detail page
                                    </a>
                                    <br>
                                    <div class="dropdown-divider"></div>

                                    @if($image->link)
                                    @if($currentTime > $image->link->expire_at)
                                    <span class="badge badge-danger">Link expired</span>
                                    <form method="post" action="{{ route('images.regenerate.link') }}">
                                        @csrf
                                        <input type="hidden" name="link" value={{asset($image->filepath)}}>
                                        <input type="hidden" name="imageId" value={{$image->id}}>
                                        <input type="submit" name="regenerate" value="Re generate a new link"
                                            id="regenerate" />
                                    </form>
                                    @else
                                    <span class="badge badge-light">Link generated</span>
                                    <div class="dropdown-divider"></div>
                                    <div class="form-group">
                                        <input type="text" class="form-control form-control-sm"
                                            value={{asset($image->filepath)}} id="copiedValue">
                                        <input type="submit" class="form-control form-control-sm"
                                            onclick="copyFunction()" value="Copy link" id="copy" />
                                    </div>
                                    <form method="post" action="{{ route('images.share.link') }}">
                                        @csrf
                                        <input type="hidden" name="linkId" value={{$image->link->id}}>
                                        <input type="submit" name="share" value="Share link" id="share" />
                                    </form>
                                    @endif
                                    @else
                                    <form method="post" action="{{ route('images.generate.link') }}">
                                        @csrf
                                        <input type="hidden" name="link" value={{asset($image->filepath)}}>
                                        <input type="hidden" name="imageId" value={{$image->id}}>
                                        <input type="submit" name="generate" value="Generate a link" id="generate" />
                                    </form>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <p>No Images Uploaded Yet!!</p>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</body>
<script>
    function copyFunction() {
        var copyText = document.getElementById("copiedValue");
        copyText.select();
        document.execCommand("copy");
        alert("Copied the text: " + copyText.value);
    }
</script>

</html>