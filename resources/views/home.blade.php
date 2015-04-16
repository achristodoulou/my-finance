@extends('app')

@section('content')
<div class="container">
	<div class="row">
		<div class="col-md-10 col-md-offset-1">
			<div class="panel panel-default">
				<div class="panel-heading">Welcome</div>

				<div class="panel-body">

                    <p>This is a small web app that can help you have a more clear understanding on how much you are spending each month.</p>

                    <hr />

                    <h4>Long story short...</h4>

                    <p>Mr. Smart, shop groceries from different stores and it pays everywhere using its debit card. At the end of the month he needs
                    to check how much he spend in total for groceries and in order to do that he need to go through his monthly bank statement and
                    do the maths. But, one day he says why every month I need to spend all these time to calculate how much I spend for different items, there
                    must be a better way.</p>

                    <p>While searching in the internet he find out mr finance, what is that??? An online application that can help me to focus on the main things.
                    The only thing that I need to do is to export a csv file from my online banking for the last month, upload the file to mr finance, create
                    my own categories and that's it. Now at any given time, I can see a report that show me how much I have spend for different items each month,
                    and I can even compare against previous month.</p>

                    @if($files_found == 0)
                        <hr />
                        <h4><span class="glyphicon glyphicon-chevron-up"></span> Whats next?</h4>

                        <p>First you need to download from your online banking, a csv file with your transactions
                            and then <a href="{{ route('fileUpload') }}">click here</a> to upload your first file!</p>
                    @else
                        @if(!$categories_found)
                            <hr />
                            <h4><span class="glyphicon glyphicon-thumbs-up"></span> I have uploaded a file, whats next?</h4>

                            <p>Proceed by creating your categories, think of categories as groups
                                for your different payments.

                                <ul>
                                    <li>The first way is to create your categories from file, <a href="{{ route('files') }}">click here</a> and next to the file of your choice click on categories.</li>
                                    <li>The second way is to go directly to the categories page, <a href="{{ route('categories') }}">click here</a> and start adding your categories.</li>
                                </ul>
                            </p>
                        @endif
                    @endif

				</div>
			</div>
		</div>
	</div>
</div>
@endsection
