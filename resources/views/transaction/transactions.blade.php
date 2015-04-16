@extends('app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-10 col-md-offset-1">
                <div class="panel panel-default">
                    <div class="panel-heading">My Transactions</div>

                    <div class="panel-body">

                        @if($transactions)

                            @foreach($transactions as $transaction)

                                <div class="row" id="row_{{ $transaction->getId() }}">

                                    <div class="col-md-3">
                                        <label for="date">Date</label>
                                        <input type="text" name="date" id="date_{{ $transaction->getId() }}" value="{{ $transaction->getDate() }}" />
                                    </div>

                                    <div class="col-md-3">
                                        <label for="value_date">Value Date</label>
                                        <input type="text" name="value_date" id="value_date_{{ $transaction->getId() }}" value="{{ $transaction->getValueDate() }}" />
                                    </div>

                                    <div class="col-md-3">
                                        <label for="filename">Description</label>
                                        <input type="text" name="description" id="description_{{ $transaction->getId() }}" value="{{ $transaction->description }}" />
                                    </div>

                                    <div class="col-md-3">
                                        <label for="filename">filename</label>
                                        <input type="text" name="filename" id="filename_{{ $transaction->getId() }}" value="{{ $transaction->filename }}" />
                                    </div>

                                    <div class="col-md-3">
                                        <label for="filename">Source</label>
                                        <input type="text" name="source" id="source_{{ $transaction->getId() }}" value="{{ $transaction->source }}" />
                                    </div>

                                    <div class="col-md-3">
                                        <label for="filename">Credited</label>
                                        <input type="text" name="credited" id="credited_{{ $transaction->getId() }}" value="{{ $transaction->amount_credited }}" />
                                    </div>

                                    <div class="col-md-3">
                                        <label for="debited">Debited</label>
                                        <input type="text" name="debited" id="debited_{{ $transaction->getId() }}" value="{{ $transaction->amount_debited }}" />
                                    </div>

                                    <div class="col-md-3">
                                        <label for="filename">Remaining Balance</label>
                                        <input type="text" name="remaining_balance" id="remaining_balance_{{ $transaction->getId() }}" value="{{ $transaction->amount_remaining_balance }}" />
                                    </div>

                                    <div class="col-md-12 text-right" style="padding-right: 47px">
                                        <br />
                                        <button class="btn btn-primary" onclick="update('{{ $transaction->getId() }}')">Update</button>
                                        <button class="btn btn-danger" onclick="deleteTransaction('{{ $transaction->getId() }}')">Delete</button>
                                    </div>


                                    <div class="col-md-12">
                                        <hr />
                                    </div>

                                </div>
                            @endforeach
                        @else

                            Oops, currently there are no any transactions saved in database. <a href="{{ route('files') }}">Click here</a> if you would like to import from a file.

                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script type="text/javascript">

        function deleteTransaction(id)
        {
            var url = "{{ URL::route('transactions_delete') }}".replace('%7Bid%7D', id);

            $.get( url );

            $('#row_' + id).hide();
        }

        function update(id)
        {
            var date = $('#date_' + id).val();
            var value_date = $('#value_date_' + id).val();
            var description = $('#description_' + id).val();
            var filename = $('#filename_' + id).val();
            var source = $('#source_' + id).val();
            var credited = $('#credited_' + id).val();
            var debited = $('#debited_' + id).val();
            var remaining_balance = $('#remaining_balance_' + id).val();

            var url = "{{ URL::route('transaction_update') }}".replace('%7Bid%7D', id);

            $.post( url, { date: date, value_date: value_date, description: description, filename: filename, source: source, credited: credited, debited: debited, remaining_balance: remaining_balance } );
        }
    </script>
@endsection