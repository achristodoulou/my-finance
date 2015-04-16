@extends('app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-10 col-md-offset-1">
                <div class="panel panel-default">
                    <div class="panel-heading">My Files</div>

                    <div class="panel-body">

                        @if($files)

                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Filename</th>
                                    <th>Source</th>
                                    <th colspan="4" class="text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php $i = 0; ?>
                            @foreach($files as $file)
                                <tr id="row_{{ $i }}">
                                    <td style="width: 30%">{{ $file['metadata']->getFilename() }} <small>({{ $file['filename']  }})</small></td>
                                    <td style="width: 30%">{{ $file['metadata']->getSource()  }}</td>
                                    <td style="width: 10%"><a class="btn btn-default" href="{{ URL::route('categories_from_file', ['filename' => $file['filename']]) }}">Edit categories</a></td>
                                    <td style="width: 10%"><a class="btn btn-info" href="{{ URL::route('transactions_from_file', ['filename' => $file['filename']]) }}">View report</a></td>
                                    <td style="width: 10%"><a class="btn btn-warning" href="{{ URL::route('import_from_file', ['filename' => $file['filename']]) }}">Import</a></td>
                                    <td style="width: 10%"><a class="btn btn-danger" href="#" onclick="deleteFile('{{ $file['filename'] }}', '{{ $i }}')">Delete</a></td>
                                </tr>
                                <?php $i++; ?>
                            @endforeach
                            </tbody>
                        </table>

                        @else

                            Oops, you have not uploaded any files. <a href="{{ route('fileUpload') }}">Click here</a> if you would like to upload a file.

                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script type="text/javascript">
        function deleteFile(filename, num)
        {
            var url = "{{ URL::route('fileDelete') }}".replace('%7Bfilename%7D', filename);

            $.get( url );

            $('#row_' + num).hide();
        }
    </script>
@endsection