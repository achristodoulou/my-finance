<?php namespace App\Http\Controllers;

use App\Helpers\FileHelper;
use App\Models\Category;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Http\Response;

class HomeController extends Controller {

	/*
	|--------------------------------------------------------------------------
	| Home Controller
	|--------------------------------------------------------------------------
	|
	| This controller renders your application's "dashboard" for users that
	| are authenticated. Of course, you are free to change or remove the
	| controller as you wish. It is just here to get your app started!
	|
	*/

    /**
     * Create a new controller instance.
     *
     */
	public function __construct()
	{
		$this->middleware('auth');
	}

    /**
     * Show the application dashboard to the user.
     *
     * @param Authenticatable $user
     * @return Response
     */
	public function index(Authenticatable $user)
	{
        $files_found = sizeof(FileHelper::getTransactionFiles()) > 0 ? true : false;
        $categories_found = false;

        if($files_found)
        {
            $categories_found = Category::all()->count() > 0 ? true : false;
        }

		return view('home', ['user' => $user, 'files_found' => $files_found, 'categories_found' => $categories_found]);
	}

}
