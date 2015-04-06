@include('common.menu')

<h1>Categories</h1>

@foreach($transactions as $t)
    <br />{{ $t->description  }}
@endforeach