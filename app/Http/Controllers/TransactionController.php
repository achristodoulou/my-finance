<?php namespace App\Http\Controllers;

use App\Helpers\FileHelper;
use App\Models\Category;
use App\Helpers\TransactionHelper;
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

    /**
     * Upload files page submission
     *
     * @return \Illuminate\View\View
     */
    public function fileUploadPost()
    {
        FileHelper::upload(Request::file('transaction_file'));

        return $this->files();
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
        $transactions_path = storage_path() . '/transactions';

        $transactions = TransactionHelper::getTransactionsFromFile($transactions_path . '/' . $filename);

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
                    if (!empty($description) && strpos($description, $label) !== false) {
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
     * @param $category
     * @param $label
     */
    public function addToCategoriesLabelsFromFilePost($category, $label)
    {
        $category = strtolower($category);
        $label = strtolower($label);

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
        $transactions_path = storage_path() . '/transactions';

        $transactions = TransactionHelper::getTransactionsFromFile($transactions_path . '/' . $filename);

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

            if($category = TransactionHelper::getCategoryName($transaction->description))
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
}
