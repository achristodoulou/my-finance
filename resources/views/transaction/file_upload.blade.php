@extends('app')

@section('content')

    <div class="container">
        <div class="row">
            <div class="col-md-10 col-md-offset-1">
                <div class="panel panel-default">
                    <div class="panel-heading">New File</div>

                    <div class="panel-body">

                        {!! Form::open(array('url' => URL::route('fileUpload'), 'enctype' => 'multipart/form-data')) !!}

                        @if(isset($messages) && sizeof($messages->all()) > 0)
                            <div class="alert alert-danger alert-dismissible" role="alert">
                                <span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                <strong>Oops! Something went wrong</strong>
                                <ul>
                                    @foreach ($messages->all() as $message)
                                        <li>{{ $message }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif


                        <div class="row">
                            <div class="col-md-10">
                                <input type="file" class="form-control" name="transaction_file" id="transaction_file" placeholder="Transactions File">
                            </div>

                            <div class="col-md-2 text-right">
                                <button type="submit" class="btn btn-primary">Submit</button>
                            </div>
                        </div>

                        <br />
                        <fieldset>
                            <legend>Advanced Properties</legend>

                            <p><b>Specify the order of the columns inside the csv file.</b></p>

                            <div class="row">
                                <div class="col-md-2">
                                    <select name="col_1" class="form-control">
                                        <option value="date" selected>Date</option>
                                        <option value="value_date">Value Date</option>
                                        <option value="description">Description</option>
                                        <option value="debit_amount">Debit Amount</option>
                                        <option value="credit_amount">Credit Amount</option>
                                        <option value="remaining_balance">Remaining Balance</option>
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <select name="col_2" class="form-control">
                                        <option value="date">Date</option>
                                        <option value="value_date" selected>Value Date</option>
                                        <option value="description">Description</option>
                                        <option value="debit_amount">Debit Amount</option>
                                        <option value="credit_amount">Credit Amount</option>
                                        <option value="remaining_balance">Remaining Balance</option>
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <select name="col_3" class="form-control">
                                        <option value="date">Date</option>
                                        <option value="value_date">Value Date</option>
                                        <option value="description" selected>Description</option>
                                        <option value="debit_amount">Debit Amount</option>
                                        <option value="credit_amount">Credit Amount</option>
                                        <option value="remaining_balance">Remaining Balance</option>
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <select name="col_4" class="form-control">
                                        <option value="date">Date</option>
                                        <option value="value_date">Value Date</option>
                                        <option value="description">Description</option>
                                        <option value="debit_amount" selected>Debit Amount</option>
                                        <option value="credit_amount">Credit Amount</option>
                                        <option value="remaining_balance">Remaining Balance</option>
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <select name="col_5" class="form-control">
                                        <option value="date">Date</option>
                                        <option value="value_date">Value Date</option>
                                        <option value="description">Description</option>
                                        <option value="debit_amount">Debit Amount</option>
                                        <option value="credit_amount" selected>Credit Amount</option>
                                        <option value="remaining_balance">Remaining Balance</option>
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <select name="col_6" class="form-control">
                                        <option value="date">Date</option>
                                        <option value="value_date">Value Date</option>
                                        <option value="description">Description</option>
                                        <option value="debit_amount">Debit Amount</option>
                                        <option value="credit_amount">Credit Amount</option>
                                        <option value="remaining_balance" selected>Remaining Balance</option>
                                    </select>
                                </div>
                            </div>
                            <br />
                            <div class="row">

                                <div class="col-md-4">
                                    <label for="source">Source</label>
                                    <input type="text" class="form-control" name="source" id="source" placeholder="Source of file" value="Bank name">
                                </div>
                                <div class="col-md-2">
                                    <label for="separator">Column Separator</label>
                                    <input type="text" class="form-control" name="separator" id="separator" placeholder="Separator" value=";">
                                </div>
                                <div class="col-md-4"><label for="date_format">Date format</label>
                                    <input type="text" class="form-control" name="date_format" id="date_format" placeholder="Example: 2015-03-26 then (y-m-d)" value="d/m/Y">
                                    <small>example: 26/03/2015 then date format will be <b>d/m/Y</b></small>
                                </div>
                                <div class="col-md-2">
                                    <label for="start_line">Start line</label>
                                    <input type="text" class="form-control" name="start_line" id="start_line" placeholder="Start line" value="1">
                                    <small>The line of the first transaction</small>
                                </div>
                            </div>
                        </fieldset>

                        {!! Form::close() !!}

                    </div>
                </div>
            </div>
        </div>
    </div>

    <script type="text/javascript">

        var results = [];

        function save(num)
        {
            var new_category = $('#new_category_' + num).val();
            var existing_category = $('#existing_category_' + num).val();
            var label = $('#label_' + num).val();
            var category = new_category.length > 0 ? new_category : existing_category;

            var url = "{{ URL::route('category_labels_save') }}";

            $.post( url, { category: category, label: encodeURIComponent(label) } );

            $('#row_' + num).hide();

        }

        $(function() {

            $( "#source" ).autocomplete({
                async: false,
                source: '{{ URL::route('api_sources_by_keyword') }}',
                minLength: 2
            });

        });
    </script>

@endsection