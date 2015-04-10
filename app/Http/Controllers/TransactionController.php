<?php namespace App\Http\Controllers;

use App\Helpers\FileHelper;
use App\Models\Category;
use App\Helpers\TransactionHelper;
use Illuminate\Support\Facades\Input;
use Request;

class TransactionController extends Controller {

    /**
     * Load available files page
     *
     * @return \Illuminate\View\View
     */
    public function files()
    {
        $files = FileHelper::getTransactionFiles();

        return view('transaction.files', ['files' => $files]);
    }

    /**
     * Upload files page
     *
     * @return \Illuminate\View\View
     */
	public function fileUpload()
	{
		return view('transaction.file_upload');
	}


}
