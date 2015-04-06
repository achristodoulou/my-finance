@extends('app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-10 col-md-offset-1">
                <div class="panel panel-default">
                    <div class="panel-heading">Upload Transaction Files</div>

                    <div class="panel-body">

                        {!! Form::open(array('url' => URL::route('fileUpload'), 'enctype' => 'multipart/form-data')) !!}

                            <div class="form-group">
                                <label for="transaction_file">File</label>
                                <input type="file" class="form-control" name="transaction_file" id="transaction_file" placeholder="Transactions File">
                            </div>
                            <div class="form-group">
                                <button type="submit" class="btn btn-default">Submit</button>
                            </div>

                        {!! Form::close() !!}

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection