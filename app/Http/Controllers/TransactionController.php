<?php namespace App\Http\Controllers;

use App\Dto\TransactionDto;
use App\Helpers\TransactionHelper;
use App\Repositories\TransactionsRepository;
use Illuminate\Support\Facades\Input;
use Request;

class TransactionController extends Controller {

    /**
     * Load available files page
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $transactions = TransactionsRepository::all();

        return view('transaction.transactions', ['transactions' => $transactions]);
    }

    /**
     *
     *
     * @return \Illuminate\View\View
     */
	public function update($id)
	{
        $transaction = new TransactionDto();
        $transaction->setId($id);
        $transaction->setValueDate( Input::get('value_date') );
        $transaction->setDate( Input::get('date') );
        $transaction->filename = Input::get('filename');
        $transaction->source = Input::get('source');
        $transaction->amount_remaining_balance = Input::get('remaining_balance');
        $transaction->amount_debited = Input::get('debited');
        $transaction->amount_credited = Input::get('credited');
        $transaction->description = Input::get('description');

        $result = TransactionsRepository::update($transaction);
	}

    public function delete($id = 0)
    {
        TransactionsRepository::delete($id);
    }

    public function report()
    {
        $transactions = TransactionsRepository::all();

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

        return view('transaction.transactions_from_storage', ['report' => $report, 'yearly_total' => $report_year_amounts, 'monthly_total' => $report_year_month_amounts]);
    }
}
