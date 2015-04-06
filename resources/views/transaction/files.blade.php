@extends('app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-10 col-md-offset-1">
                <div class="panel panel-default">
                    <div class="panel-heading">Uploaded Files</div>

                    <div class="panel-body">

                        @foreach($files as $file)
                            <br />{{ $file  }}
                            <a href="{{ URL::route('categories_from_file', ['filename' => $file]) }}">Fix Categories</a>
                            <a href="{{ URL::route('transactions_from_file', ['filename' => $file]) }}">Report</a>
                        @endforeach

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection