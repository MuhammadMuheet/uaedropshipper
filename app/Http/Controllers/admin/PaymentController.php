<?php

namespace App\Http\Controllers\admin;

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
        if (ActivityLogger::hasPermission('payments', 'view')) {
            if ($request->ajax()) {
                $query = Transaction::query();

                if (!empty($request->usertype)) {
                    $query->where('user_type', $request->usertype);
                }
                if (!empty($request->logistic_company)) {
                    $query->where('user_id', $request->logistic_company);
                }

                if (!empty($request->seller)) {
                    $query->where('user_id', $request->seller);
                }
                if (!empty($request->current_date)) {
                    $query->whereDate('created_at', '=', $request->current_date);
                }

                if (!empty($request->start_date)) {
                    $query->whereDate('created_at', '>=', $request->start_date);
                }

                if (!empty($request->end_date)) {
                    $query->whereDate('created_at', '<=', $request->end_date);
                }

                $data = $query->orderBy('id', 'DESC')->get();
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
                    ->addColumn('UserName', function ($data) {
                        $user = User::where('id', $data->user_id)->first();
                        return $user ? ucfirst($user->name) : 'N/A';
                    })
                    ->addColumn('UserType', function ($data) {
                        $userTypeLabels = [
                            'seller' => ['class' => 'bg-primary', 'text' => 'Seller'],
                            'logistic_company' => ['class' => 'bg-dark', 'text' => 'Logistic Company'],
                        ];
                        if (isset($userTypeLabels[$data->user_type])) {
                            return "<div class='badge {$userTypeLabels[$data->user_type]['class']}'>{$userTypeLabels[$data->user_type]['text']}</div>";
                        }
                        return "<div class='badge bg-secondary'>Unknown</div>";
                    })
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
                        $action .=
                            '<a href="' . route('admin_invoice', encrypt($data->id)) . '" class=" btn btn-sm btn-dark" >
                        <i style="font-size: 16px; padding: 0;" class="fa-solid fa-credit-card"></i>
                    </a>';
                        return $action;
                    })
                    ->with('totalTransactions', $totalTransactions)
                    ->with('totalWallet', $totalWallet)
                    ->with('totalAmountIn', $totalAmountIn)
                    ->with('totalAmountOut', $totalAmountOut)
                    ->with('totalSellerTransactions', $totalSellerTransactions)
                    ->with('totalCompanyTransactions', $totalCompanyTransactions)
                    ->rawColumns(['UserName', 'Date', 'UserType', 'AmountType', 'Amount', 'action'])
                    ->make(true);
            }
            ActivityLogger::UserLog('Open Payments Page');
            $sellerData = User::where('role', 'seller')->where('status', 'active')->orderBy('id', 'DESC')->get();
            $LogisticCompanyData = User::where('role', 'logistic_company')->where('status', 'active')->orderBy('id', 'DESC')->get();
            return view('admin.pages.payments', compact('sellerData', 'LogisticCompanyData'));
        }
    }

    public function invoice(Request $request, $id)
    {
        if (ActivityLogger::hasPermission('payments', 'view')) {
            $payment_id = decrypt($id);
            ActivityLogger::UserLog('Open Payments invoice');
            $paymentData = Transaction::where('id', $payment_id)->first();
            return view('admin.pages.invoice', compact('paymentData', 'id'));
        }
    }

    public function get_transaction_user_type(Request $request)
    {
        if (ActivityLogger::hasPermission('payments', 'view')) {

            if ($request->user_type == 'logistic_company') {

                $users = User::where('role', '=', 'logistic_company')->where('status', '=', 'active')->get();
                $options = '<option value="" disabled selected>Choose Logistic Company</option>';

                foreach ($users as $user) {
                    $options .= '<option value="' . $user->id . '">' . $user->name . ' [Wallet Amount: ' . ($user->wallet ?? '0') . ']</option>';
                }
            } elseif ($request->user_type == 'seller') {
                $users = User::where('role', '=', 'seller')->where('status', '=', 'active')->get();
                $options = '<option value="" disabled selected>Choose Seller</option>';
                foreach ($users as $user) {
                    $options .= '<option value="' . $user->id . '">' . $user->name . ' [Wallet Amount: ' . ($user->wallet ?? '0') . ']</option>';
                }
            }
            return response()->json(['options' => $options]);
        }
    }
    public function give_payment(Request $request)
    {
        if (ActivityLogger::hasPermission('payments', 'view')) {
            if (empty($request->user_id)) {
                return response()->json(3);
            }
            if (empty($request->amount)) {
                return response()->json(2);
            }
            try {
                $data = User::find($request->user_id);
                if ($data) {
                    $wallet_amount = $data->wallet;
                    if ($request->amount > $wallet_amount) {
                        return response()->json(4);
                    }
                    $data->wallet -= $request->amount;
                    $data->save();
                    $transaction = Transaction::create([
                        'user_id' => $request->user_id,
                        'user_type' => $request->user_type,
                        'amount_type' => 'out',
                        'amount' => $request->amount,
                    ]);
                    ActivityLogger::UserLog('Give Payment ' . $request->amount . ' AED to ' . $data->name);
                    return response()->json(1);
                } else {
                    return response()->json(3);
                }
            } catch (\Exception $e) {
                return response()->json(['error' => 'Something went wrong'], 500);
            }
        }
    }



    //     public function listPaymentRequests()
    //     {
    //         $requests = PaymentRequest::with('seller')->latest()->get();

    //         $data = [];

    //         foreach ($requests as $index => $req) {
    //             $data[] = [
    //                 'id' => $index + 1,
    //                 'seller_name' => $req->seller ? $req->seller->name : 'N/A',
    //                 'amount' => $req->amount,
    //                 'status' => ucfirst($req->status),
    //                 'created_at' => $req->created_at->format('Y-m-d H:i'),
    //                 'action' => '
    //     <button class="btn btn-sm btn-success" onclick="handlePaymentAction(' . $req->id . ', \'approve\', ' . $req->amount . ', ' . $req->seller_id . ')">Approve</button>
    //     <button class="btn btn-sm btn-danger" onclick="handlePaymentAction(' . $req->id . ', \'reject\')">Reject</button>
    // ',

    //             ];
    //         }

    //         return response()->json(['data' => $data]);
    //     }



    public function listPaymentRequests()
    {
        // Sirf pending status wali requests fetch karo
        $requests = PaymentRequest::with('seller')
            ->where('status', 'pending') // Add this line
            ->latest()
            ->get();

        $data = [];

        foreach ($requests as $index => $req) {
            $data[] = [
                'id' => $index + 1,
                'seller_name' => $req->seller ? $req->seller->name : 'N/A',
                'amount' => $req->amount,
                'status' => ucfirst($req->status),
                'created_at' => $req->created_at->format('Y-m-d H:i'),
                'action' => '
                <button class="btn btn-sm btn-success" onclick="handlePaymentAction(' . $req->id . ', \'approve\', ' . $req->amount . ', ' . $req->seller_id . ')">Approve</button>
                <button class="btn btn-sm btn-danger" onclick="handlePaymentAction(' . $req->id . ', \'reject\')">Reject</button>
            ',
            ];
        }

        return response()->json(['data' => $data]);
    }




    public function handlePaymentAction(Request $request)
    {
        $request->validate([
            'request_id' => 'required|exists:payment_requests,id',
            'action' => 'required|in:approve,reject',
        ]);

        $paymentRequest = PaymentRequest::find($request->request_id);

        if ($paymentRequest->status !== 'pending') {
            return response()->json(['message' => 'Already processed.'], 400);
        }

        if ($request->action === 'approve') {
            if (empty($request->user_id) || empty($request->amount)) {
                return response()->json(['message' => 'Missing user or amount'], 400);
            }

            $user = User::find($request->user_id);

            if (!$user || $user->wallet < $request->amount) {
                return response()->json(['message' => 'Insufficient balance or user not found'], 400);
            }

            // Deduct from wallet
            $user->wallet -= $request->amount;
            $user->save();

            // Create transaction
            Transaction::create([
                'user_id' => $request->user_id,
                'user_type' => 'seller',
                'amount_type' => 'out',
                'amount' => $request->amount,
            ]);

            // Update payment request status
            $paymentRequest->status = 'approved';
            $paymentRequest->save();

            // Log activity (optional)
            ActivityLogger::UserLog('Approved payment of ' . $request->amount . ' to ' . $user->name);

            return response()->json(['message' => 'Payment approved successfully.']);
        }

        if ($request->action === 'reject') {
            $paymentRequest->status = 'rejected';
            $paymentRequest->save();
            return response()->json(['message' => 'Payment request rejected.']);
        }
    }
}
