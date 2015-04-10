@extends('app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-10 col-md-offset-1">
                <div class="panel panel-default">
                    <div class="panel-heading">Import Transaction Status</div>

                    <div class="panel-body">
                        @if($result)
                            <div class="alert alert-success" role="alert">
                                <h3>
                                    Success
                                    <small>(Transactions - imported: {{ $total - $skip }} out of {{ $total  }}, skipped {{ $skip }})</small>
                                </h3>
                            </div>
                        @else
                            <div class="alert alert-danger" role="alert">
                                <h3>
                                    Failure
                                </h3>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection