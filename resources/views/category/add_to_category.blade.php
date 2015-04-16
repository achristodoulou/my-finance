@extends('app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-10 col-md-offset-1">
                <div class="panel panel-default">
                    <div class="panel-heading">Categories from File</div>

                    <div class="panel-body">

                        <h1>Labels ({{ sizeof($transactions) }})</h1>

                        <form class="form-inline" id="form">

                        @for($i = 0; $i < sizeof($transactions); $i++)
                            <div id="row_{!! $i !!}" class="form-group" style="padding-bottom: 4px">
                                <input class="form-control" type="text" name="label_{!! $i !!}" id="label_{!! $i !!}" value="{{ $transactions[$i]->getDate() . ' / ' .$transactions[$i]->description }} ({{ $transactions[$i]->amount_credited ? '+' : '-' }}{{ $transactions[$i]->amount_credited ?: $transactions[$i]->amount_debited }})" style="width: 300px; height: 30px;" />
                                <input class="form-control" type="text" name="new_category_{!! $i !!}" id="new_category_{!! $i !!}" placeholder="New Category" style="width: 300px; height: 30px;" />
                                @if($categories)
                                {!! Form::select('existing_category', $categories, null, ['id' => 'existing_category_' . $i, 'class' => 'form-control']) !!}
                                @endif
                                <button onclick="save('{!! $i !!}')" class="btn btn-default">Save</button>
                            </div>
                        @endfor

                        </form>

                        <script type="text/javascript">
                            $(document).ready(function() {
                                $("#form").submit(function(){
                                    return false;
                                });
                            });

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

                        </script>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection