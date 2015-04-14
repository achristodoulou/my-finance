<?php namespace App\Http\Controllers;

use App\Dto\MetadataDto;
use App\Helpers\FileHelper;
use App\Models\Category;
use App\Helpers\TransactionHelper;
use App\Models\Config;
use App\Repositories\ConfigRepository;
use App\Repositories\TransactionsRepository;
use Illuminate\Support\Facades\Input;
use Request;

class FileTransactionController extends Controller {

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

    /**
     * Upload files page submission
     *
     * @return \Illuminate\View\View
     */
    public function fileUploadPost()
    {
        $file = Request::file('transaction_file');
        $source = Input::get('source');


        $metadata = new MetadataDto(
            Input::get('start_line'),
            $file->getClientOriginalName(),
            Input::get('separator'),
            Input::get('date_format'),
            $source,
            [
                Input::get('col_1'),
                Input::get('col_2'),
                Input::get('col_3'),
                Input::get('col_4'),
                Input::get('col_5'),
                Input::get('col_6')
            ],
            date('Y-m-d H:i:s')
        );

        FileHelper::upload($file, $metadata);

        ConfigRepository::save('source', $source, $source);

        return redirect()->route('files');
    }

    /**
     * Delete a file submission page
     *
     * @param $filename
     */
    public function deleteFile($filename)
    {
        FileHelper::removeFile($filename);
    }

    /**
     * Assign description / labels to categories
     *
     * @param $filename
     * @return \Illuminate\View\View
     */
    public function addToCategoriesLabelsFromFile($filename)
    {
        $transactions = TransactionHelper::getTransactionsFromFile($filename);

        $non_process_transactions = [];

        $categories = Category::all()->toArray();

        for ($i = 0; $i < sizeof($transactions); $i++)
        {
            $found = false;
            $description = strtolower(trim($transactions[$i]->description));

            foreach ($categories as $category)
            {
                $_labels = explode('|', $category['labels']);
                foreach($_labels as $label)
                {
                    $word = $description . $transactions[$i]->amount_debited . $transactions[$i]->amount_credited;
                    if (!empty($description) && preg_match("/($label?)/", $word)) {
                        $found = true;
                        break;
                    }
                }

                if($found)
                    break;
            }

            if(!$found)
            {
                $non_process_transactions[] = $transactions[$i];
            }
        }

        return view('category.add_to_category', ['categories' => Category::lists('category_name', 'category_name'), 'transactions' => $non_process_transactions, 'counter' => 0]);
    }

    /**
     * Assign description / labels to categories submission page
     *
     * @internal param $category
     * @internal param $label
     */
    public function addToCategoriesLabelsFromFilePost()
    {
        $category = strtolower(Input::get('category'));
        $label = strtolower(urldecode(Input::get('label')));

        $categoryItem = Category::where('category_name', '=', $category)->first();

        if(is_null($categoryItem))
        {
            $categoryItem = new Category();
            $categoryItem->category_name = $category;
            $categoryItem->labels = $label;
        }
        else if(!is_null($categoryItem) && strpos($categoryItem->labels, $label) === false)
        {
            $categoryItem->labels = $categoryItem->labels . '|' . $label;
        }
        else{
            $categoryItem->labels = $label;
        }

        $categoryItem->save();
    }

    /**
     * Transactions page
     *
     * @param $filename
     * @return \Illuminate\View\View
     */
    public function transactionsFromFile($filename)
    {
        $transactions = TransactionHelper::getTransactionsFromFile($filename);

        $report = [];

        $report_year_amounts = [];

        $report_year_month_amounts = [];

        foreach($transactions as $transaction)
        {
            $year = $transaction->date->format('Y');
            $month = $transaction->date->format('F');

            if(!isset($report_year_amounts[$year]))
                $report_year_amounts[$year] = 0;

            if(!isset($report_year_month_amounts[$year][$month]))
                $report_year_month_amounts[$year][$month] = 0;

            $word = $transaction->description . $transaction->amount_debited . $transaction->amount_credited;

            if($category = TransactionHelper::getCategoryName($word))
            {
                if(!isset($report[$year]))
                    $report[$year] = [];

                if(!isset($report[$year][$month]))
                    $report[$year][$month] = [];

                if(!isset($report[$year][$month][$category]['total']))
                    $report[$year][$month][$category]['total'] = 0;

                $report[$year][$month][$category]['total'] += $transaction->amount_debited ?: $transaction->amount_credited;
                $report[$year][$month][$category]['transactions'][] = $transaction;
            }
            else
            {
                if(!isset($report[$year][$month]['other']['total']))
                    $report[$year][$month]['other']['total'] = 0;

                $report[$year][$month]['other']['total'] += $transaction->amount_debited ?: $transaction->amount_credited;
                $report[$year][$month]['other']['transactions'][] = $transaction;
            }

            $report_year_month_amounts[$year][$month] = TransactionHelper::calculateTotal($report_year_month_amounts[$year][$month],
                                                                            $transaction->amount_debited,
                                                                            $transaction->amount_credited);

            $report_year_amounts[$year]  = TransactionHelper::calculateTotal($report_year_amounts[$year],
                                                                 $transaction->amount_debited,
                                                                 $transaction->amount_credited);
        }

        return view('transaction.transactions_from_file', ['report' => $report, 'yearly_total' => $report_year_amounts, 'monthly_total' => $report_year_month_amounts]);
    }

    /**
     * Import transactions from file page
     *
     * @param $filename
     * @return \Illuminate\View\View
     */
    public function import($filename)
    {
        $metadata = FileHelper::getMetadata($filename);
        return view('transaction.import', ['metadata' => $metadata, 'filename' => $filename]);
    }

    /**
     * Import transactions from file post
     *
     * @return \Illuminate\View\View
     */
    public function importPost()
    {
        $filename = Input::get('filename');
        $from_date = new \DateTime(Input::get('from_date'));
        $to_date =  new \DateTime(Input::get('to_date'));
        $list_of_transactions_for_saving = [];

        $transactions = TransactionHelper::getTransactionsFromFile($filename);

        if(sizeof($transactions) > 0)
        {
            foreach ($transactions as $transaction)
            {
                if($transaction->date >= $from_date && $transaction->date <= $to_date)
                {
                    $list_of_transactions_for_saving[] = $transaction;
                }
            }

            if(sizeof($list_of_transactions_for_saving) > 0)
            {
                $result = TransactionsRepository::storeMoreThanOne($list_of_transactions_for_saving);

                return view('transaction.import_completed', ['result' => $result['status'], 'skip'  => $result['skip'], 'total' => $result['total']]);
            }
        }

        return redirect()->route('import_from_file', ['filename' => $filename]);
    }
}
