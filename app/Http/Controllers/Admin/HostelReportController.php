<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Hostel;
use App\Models\HostelRoom;
use App\Models\HostelAllotment;
use App\Models\StudentLedger;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class HostelReportController extends Controller
{
    public function index()
    {
        $hostels = Hostel::all();
        $rooms = HostelRoom::all();
        return view('pages.hostel.report', compact('hostels', 'rooms'));
    }

    public function generate(Request $request)
    {
        $type = $request->type; // 'room' or 'collection'
        $from = $request->from_date;
        $to = $request->to_date;
        $block = $request->block; // Hostel type (Boys/Girls/Common)
        $roomId = $request->room_id;

        if ($type === 'room') {
            $query = HostelAllotment::with(['student.user', 'student.classInfo', 'student.sectionInfo', 'room.hostel'])
                ->where('status', 'alloted');

            if ($block) {
                $query->whereHas('room.hostel', function($q) use ($block) {
                    $q->where('type', $block);
                });
            }

            if ($roomId) {
                $query->where('room_id', $roomId);
            }

            $data = $query->get();

            return response()->json([
                'html' => view('pages.hostel.hostel-report-rows', [
                    'allotments' => $data,
                    'reportType' => 'room'
                ])->render()
            ]);
        } else {
            // Collection & Dues
            $query = StudentLedger::with(['student.user'])
                ->where('description', 'like', '%Hostel%');

            if ($from && $to) {
                $query->whereBetween('date', [$from, $to]);
            }

            // For dues calculation, we might need a more complex subquery or join
            // For now, we'll fetch the ledger entries that are related to Hostels
            $data = $query->latest()->get();

            // Calculate totals
            $totalCollection = $data->where('transaction_type', 'Payment')->sum('amount');
            $onlineTotal = $data->where('transaction_type', 'Payment')->where('description', 'like', '%Online%')->sum('amount'); // Example
            $offlineTotal = $totalCollection - $onlineTotal;
            
            // Estimated Fees
            $totalFees = $data->where('transaction_type', 'Fee')->sum('amount');
            $estimatedDues = $totalFees - $totalCollection;

            return response()->json([
                'html' => view('pages.hostel.hostel-report-rows', [
                    'ledgerEntries' => $data,
                    'reportType' => 'collection',
                    'totals' => [
                        'totalCollection' => $totalCollection,
                        'onlineTotal' => $onlineTotal,
                        'offlineTotal' => $offlineTotal,
                        'estimatedDues' => $estimatedDues
                    ]
                ])->render()
            ]);
        }
    }
}
