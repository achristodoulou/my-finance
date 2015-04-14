@extends('app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-10 col-md-offset-1">
                <div class="panel panel-default">
                    <div class="panel-heading">Transactions by Category (source: file)</div>

                    <div class="panel-body">

                        <table class="table">
                            <?php $i = 1; ?>
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
                                    @foreach($transactions as $category => $data)
                                        <tr>
                                            <td><a href="#" onclick="javascript: toggleTransactions('row_{{ $year . $month . $i }}'); return false;">{{ strtoupper($category) }}</a></td>
                                            <td>{{ $data['total'] }}</td>
                                            <td>

                                            </td>
                                        </tr>
                                        <tr id="row_{{ $year . $month . $i }}" style="display: none">
                                            <td colspan="4">
                                                @foreach($data['transactions'] as $t)
                                                    <div>
                                                        {{ $t->getDate() }} |
                                                        {{ $t->description }} |
                                                        {{ $t->amount_credited ?: $t->amount_debited }}
                                                    </div>
                                                @endforeach
                                            </td>
                                        </tr>
                                        <?php $i++; ?>
                                    @endforeach
                                @endforeach
                            @endforeach
                        </table>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <script type="text/javascript">
        function toggleTransactions(id)
        {
            $('#' + id).toggle(100);
        }
    </script>
@endsection