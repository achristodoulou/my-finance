<?php namespace App\Http\Controllers;

use App\Models\Category;
use App\Helpers\TransactionHelper;
use Request;
use Storage;

class TransactionController extends Controller {

    public function files()
    {
        $files = Storage::disk('transactions')->files();

        return view('transaction.files', ['files' => $files]);
    }

	public function fileUpload()
	{
		return view('transaction.file_upload');
	}

    public function fileUploadPost()
    {
        $transactions_path = storage_path() . '/transactions';
        $filename = date('YmdHis');
        Request::file('transaction_file')->move($transactions_path, $filename);

        return $this->files();
    }

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

            if($category = $this->getCategoryForTransaction($transaction->description))
            {
                if(!isset($report[$year]))
                    $report[$year] = [];

                if(!isset($report[$year][$month]))
                    $report[$year][$month] = [];

                if(!isset($report[$year][$month][$category]))
                    $report[$year][$month][$category] = 0;

                $report[$year][$month][$category] += $transaction->amount_debited ?: $transaction->amount_credited;
            }
            else
            {
                if(!isset($report[$year][$month]['other']))
                    $report[$year][$month]['other'] = 0;

                $report[$year][$month]['other'] += $transaction->amount_debited ?: $transaction->amount_credited;
            }

            $report_year_month_amounts[$year][$month] = $this->calculateTotal($report_year_month_amounts[$year][$month],
                                                                            $transaction->amount_debited,
                                                                            $transaction->amount_credited);

            $report_year_amounts[$year]  = $this->calculateTotal($report_year_amounts[$year],
                                                                 $transaction->amount_debited,
                                                                 $transaction->amount_credited);
        }

        return view('transaction.transactions', ['report' => $report, 'yearly_total' => $report_year_amounts, 'monthly_total' => $report_year_month_amounts]);
    }

    function calculateTotal($total, $debitAmount = 0, $creditAmount = 0)
    {
        if($debitAmount)
            $total -= $debitAmount;
        else
            $total += $creditAmount;
        return $total;
    }

    function getCategoryForTransaction($transaction_description)
    {
        $transaction_description = strtolower($transaction_description);

        $categories = Category::all()->toArray();

        foreach ($categories as $category)
        {
            $labels = explode('|', $category['labels']);

            foreach ($labels as $label)
            {
                if(strpos($transaction_description, $label) !== false)
                {
                    return $category['category_name'];
                }
            }
        }

        return  null;
    }
}
