@extends('app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-10 col-md-offset-1">
                <div class="panel panel-default">
                    <div class="panel-heading">Categories</div>

                    <div class="panel-body">

                        <table class="table">
                            <tr>
                                <td>Name</td>
                                <td>Labels</td>
                                <td></td>
                            </tr>
                            @foreach($categories as $category)
                                <tr class="info" id="row_{{ $category->id }}">
                                    <td>
                                        <input class="form-control" type="text" name="category_name" id="category_name_{{ $category->id }}" value="{{ strtoupper($category->category_name) }}" style="width: 100%; height: 100px;">
                                    </td>
                                    <td>
                                        <textarea class="form-control" style="width: 100%; height: 100px" id="labels_{{ $category->id }}" >{{ $category->labels }}</textarea>
                                    </td>
                                    <td>
                                        <button class="btn btn-primary" onclick="save('{{ $category->id }}')">Save</button>
                                        <button class="btn btn-danger" onclick="deleteCategory('{{ $category->id }}')">Delete</button>
                                    </td>
                                </tr>
                            @endforeach
                            <tr class="info">
                                <td>
                                    <input  class="form-control" type="text" name="category_name" id="category_name_0" style="width: 100%; height: 50px;">
                                </td>
                                <td>
                                    <textarea class="form-control" style="width: 100%; height: 50px" id="labels_0" ></textarea>
                                </td>
                                <td>
                                    <button class="btn btn-primary" onclick="save('0')">Save</button>
                                </td>
                            </tr>
                        </table>

                        <script type="text/javascript">
                            function save(num)
                            {
                                var category_name = $('#category_name_' + num).val();
                                var labels = $('#labels_' + num).val();

                                var url = "{{ URL::route('category_labels_update') }}".replace('%7Bid%7D', num);

                                $.post( url, { category_name: category_name, labels: labels} );

                                location.reload();
                            }

                            function deleteCategory(num)
                            {
                                var url = "{{ URL::route('category_delete') }}".replace('%7Bid%7D', num);

                                $.get( url );

                                $('#row_' + num).hide();
                            }
                        </script>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection