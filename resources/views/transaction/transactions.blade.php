@extends('app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-10 col-md-offset-1">
                <div class="panel panel-default">
                    <div class="panel-heading">Transactions by Category</div>

                    <div class="panel-body">

                        <table class="table">
                            @foreach($report as $year => $data)
                                <tr class="info">
                                    <td colspan="3">
                                        <h3>{{ $year }}
                                            {{--<small>&euro;{{ $yearly_total[$year] }}</small>--}}
                                        </h3>
                                    </td>
                                </tr>
                                @foreach($data as $month => $transactions)
                                    <tr class="active">
                                        <td colspan="3">
                                            <h4>{{ $month }}
                                                {{--<small style="font-weight: bold">(&euro;{{ $monthly_total[$year][$month] }})</small>--}}
                                            </h4></td>
                                    </tr>
                                    @foreach($transactions as $category => $amount)
                                        <tr>
                                            <td>{{ strtoupper($category) }}</td>
                                            <td>{{ $amount }}</td>
                                            <td>% on total amount of current month</td>
                                        </tr>
                                        <tr>
                                            <td colspan="3">List of transactions</td>
                                        </tr>
                                    @endforeach
                                @endforeach
                            @endforeach
                        </table>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection