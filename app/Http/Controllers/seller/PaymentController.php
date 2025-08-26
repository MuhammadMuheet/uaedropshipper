<?php

namespace App\Http\Controllers\seller;

use App\Helpers\ActivityLogger;
use App\Http\Controllers\Controller;
use App\Models\PaymentRequest;
use Illuminate\Http\Request;
use App\Models\Transaction;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;

class PaymentController extends Controller
{
    public function index(Request $request)
    {
        if (ActivityLogger::hasSellerPermission('payments', 'view')) {
            if ($request->ajax()) {
                $query = Transaction::query();

                if (!empty($request->current_date)) {
                    $query->whereDate('created_at', '=', $request->current_date);
                }

                if (!empty($request->start_date)) {
                    $query->whereDate('created_at', '>=', $request->start_date);
                }

                if (!empty($request->end_date)) {
                    $query->whereDate('created_at', '<=', $request->end_date);
                }

                $data = $query->where('user_id', Auth::user()->id)->orderBy('id', 'DESC')->get();
                $totalTransactions = 0;
                $totalAmountIn = 0;
                $totalAmountOut = 0;
                $totalSellerTransactions = 0;
                $totalCompanyTransactions = 0;
                $totalWallet = 0;
                foreach ($data as $item) {
                    $totalTransactions += $item->amount;
                    if ($item->amount_type == 'in') {
                        $totalAmountIn += $item->amount;
                    } elseif ($item->amount_type == 'out') {
                        $totalAmountOut += $item->amount;
                    }
                    if ($item->user_type == 'seller') {
                        $totalSellerTransactions += $item->amount;
                    } elseif ($item->user_type == 'logistic_company') {
                        $totalCompanyTransactions += $item->amount;
                    }
                    $totalWallet = @$totalAmountIn - @$totalAmountOut;
                }
                return Datatables::of($data)
                    ->addColumn('AmountType', function ($data) {
                        $AmountTypeLabels = [
                            'in' => ['class' => 'bg-success', 'text' => 'Amount In'],
                            'out' => ['class' => 'bg-danger', 'text' => 'Amount Out'],
                        ];
                        if (isset($AmountTypeLabels[$data->amount_type])) {
                            return "<div class='badge {$AmountTypeLabels[$data->amount_type]['class']}'>{$AmountTypeLabels[$data->amount_type]['text']}</div>";
                        }
                        return "<div class='badge bg-secondary'>Unknown</div>";
                    })
                    ->addColumn('Amount', function ($data) {
                        return $data->amount . ' AED';
                    })
                    ->addColumn('Date', function ($data) {
                        return Carbon::parse($data->created_at)->format('d/m/Y');
                    })
                    ->addColumn('action', function ($data) {
                        $action = '';

                        // Only add buttons if amount_type is 'out'
                        if ($data->amount_type === 'out') {
                            // View Invoice Button
                            //             $action .= '<a href="' . route('admin_invoice', encrypt($data->id)) . '"
                            //    class="btn btn-sm btn-dark me-1" title="View Invoice">
                            //    <i style="font-size:16px; padding:0;" class="fa-solid fa-credit-card"></i>
                            // </a>';

                            // Download PDF Button
                            $action .= '<a href="' . route('admin.payments.invoice.download', encrypt($data->id)) . '"
                   class="btn btn-sm btn-success" title="Download PDF">
                   <i style="font-size:16px;" class="fa-solid fa-print"></i>
                </a>';
                        }

                        return $action;
                    })

                    ->with('totalTransactions', number_format($totalTransactions, 2))
                    ->with('totalWallet', number_format($totalWallet, 2))
                    ->with('totalAmountIn', number_format($totalAmountIn, 2))
                    ->with('totalAmountOut', number_format($totalAmountOut, 2))
                    ->rawColumns(['Date', 'AmountType', 'Amount', 'action'])
                    ->make(true);
            }
            ActivityLogger::UserLog('Open Seller Payments Page');
            return view('seller.pages.payments');
        }
    }


    public function sendPaymentRequest(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:1'
        ]);

        $logistic = auth()->user(); // logged-in seller

        // Check if there's already a pending request
        $pendingRequest = PaymentRequest::where('user_id', $logistic->id)
            ->where('status', 'pending')
            ->first();

        if ($pendingRequest) {
            return response()->json([
                'error' => 'You already have a pending payment request. Please wait until it is approved or rejected.'
            ], 422);
        }

        // Check if requested amount is <= wallet balance
        if ($request->amount > $logistic->wallet) {
            return response()->json([
                'error' => 'Requested amount exceeds your wallet balance.'
            ], 422);
        }

        PaymentRequest::create([
            'user_id' => $logistic->id,
            'amount' => $request->amount,
            'status' => 'pending'
        ]);

        return response()->json(['success' => true]);
    }
}