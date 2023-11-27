<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Bootstrap demo</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" >
  </head>
  <body>
    
    <div class="container">
        <h3>Form Upload</h3>
        <div class="row">
            <div class="col-md-2">
                <ul class="list-group">
                <li class="list-group-item active" aria-current="true">Single Upload Form</li>
                <form action="{{ route('single-upload') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="file" class="form-control" name="file" id="image-upload-input" onchange="picturePreview(event)">
                    <img id="picture-preview" src="#" style="max-height: 150px; max-height:150px; display:none"> 
                    <button type="submit" class="btn btn-primary">Save</button>
                </form>
                </ul>
            </div>
            <div class="col-md-2">
                <ul class="list-group">
                <li class="list-group-item active" aria-current="true">Multi Upload Form</li>
                <form action="{{ route('multi-upload') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="file" class="form-control" name="images[]" multiple> 
                    <button type="submit" class="btn btn-primary">Save</button>
                </form>
                </ul>
            </div>
            <div class="col-md-2">
                <ul class="list-group">
                <li class="list-group-item active" aria-current="true">Crop Upload Form</li>
                <form action="{{ route('crop-upload') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="file" class="form-control" name="file"> 
                    <input type="text" class="form-control" name="width">
                    <input type="text" class="form-control" name="height">
                    <button type="submit" class="btn btn-primary">Save</button>
                </form>
                </ul>
            </div>
            <div class="col-md-2">
                <ul class="list-group">
                <li class="list-group-item active" aria-current="true">Document Upload Form</li>
                <form action="{{ route('document-upload') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="file" class="form-control" name="file" accept=".pdf,.doc,.docx">
                    <button type="submit" class="btn btn-primary">Save</button>
                </form>
                </ul>
            </div>
        </div>
        <h3>Image List</h3>
        <table class="table">
        <thead>
          <tr>
            <th scope="col">#</th>
            <th scope="col">Image</th>
            <th scope="col">Action</th>
          </tr>
        </thead>
        <tbody>
          @foreach ($images as $image)
          <tr>
            <th scope="row">{{ $image->id }}</th>
            <td>
              <img src="{{ $image->image_path.$image->image_name }}" data-bs-toggle="modal" data-bs-target="#exampleImage-{{ $image->id }}" style="width: 100px; height:100px;cursor:pointer">
              <div class="modal fade" id="exampleImage-{{ $image->id }}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                  <div class="modal-content">
                    <div class="modal-header">
                      <h1 class="modal-title fs-5" id="exampleModalLabel">Modal Image {{ $image->id }}</h1>
                      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                    <img src="{{ $image->image_path.$image->image_name }}" style="width:100%">
                    </div>
                    <div class="modal-footer">
                      <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    </div>
                  </div>
                </div>
              </div>
            </td> 
            <td><a href="{{ route('delete-image', $image->id) }}" onclick="return confirm('Bu resmi silmek istediğinize emin misiniz?')" class="btn btn-danger btn-sm">Sil</a></td>
          </tr>
          @endforeach
        </tbody>
      </table>
    </div>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
      function picturePreview(event) {
        var image = document.getElementById('picture-preview');
        image.style.display = 'block';

        var file = event.target.files[0]; 
        var imageUrl = URL.createObjectURL(file);
        image.src = imageUrl;
      }
    </script>
  </body>
</html>