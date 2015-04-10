@extends('app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-10 col-md-offset-1">
                <div class="panel panel-default">
                    <div class="panel-heading">Import Transactions</div>

                    <div class="panel-body">

                        {!! Form::open() !!}

                        <input type="hidden" name="filename" value="{{ $filename }}">

                        <div class="row">

                            <div class="col-md-4">
                                <label for="filename">Filename</label>
                                {{ $metadata->getFilename() }}
                            </div>

                            <div class="col-md-4">
                                <label for="filename">Source</label>
                                {{ $metadata->getSource() }}
                            </div>

                            <div class="col-md-4">
                                <label for="filename">Source</label>
                                {{ $metadata->getCreatedAt() }}
                            </div>

                            <br /><br />

                            <div class="col-md-4">
                                <label for="filename">From Date</label>
                                <input type="text" class="form-control" name="from_date" id="from_date" value="{{ date('Y-m-d') }}">
                            </div>

                            <div class="col-md-4">
                                <label for="filename">End Date</label>
                                <input type="text" class="form-control" name="to_date" id="to_date" value="{{ date('Y-m-d') }}">
                            </div>

                            <div class="col-md-4">
                                <br />
                                <button type="submit" class="btn btn-primary">Proceed</button>
                            </div>
                        </div>

                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script type="text/javascript">
        $(document).ready(function() {
            $('#from_date').datepicker( { dateFormat: "yy-mm-dd" } );
            $('#to_date').datepicker( { dateFormat: "yy-mm-dd" } );
        });
    </script>

@endsection