<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TransportAssign2Controller extends Controller
{
    public function index(Request $request)
    {
        $query = \App\Models\StudentTransport::with(['student.user', 'student.class', 'student.section', 'busStop', 'arrivalVehicle', 'departureVehicle']);

        if ($request->filled('class_id')) {
            $query->whereHas('student', fn($q) => $q->where('class_id', $request->class_id));
        }
        if ($request->filled('bus_id')) {
            $query->where(function($q) use ($request) {
                $q->where('arrival_vehicle_id', $request->bus_id)
                  ->orWhere('departure_vehicle_id', $request->bus_id);
            });
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('allotment_no', 'like', "%{$search}%")
                  ->orWhereHas('student.user', fn($sq) => $sq->where('name', 'like', "%{$search}%"));
            });
        }

        $transports = $query->paginate($request->get('pageSize', 10));
        $classes = \App\Models\SchoolClass::all();
        $vehicles = \App\Models\Vehicle::all();
        $busStops = \App\Models\BusStop::all();

        if ($request->ajax()) {
            return response()->json([
                'data' => $transports->items(),
                'total' => $transports->total(),
                'last_page' => $transports->lastPage(),
                'current_page' => $transports->currentPage(),
                'from' => $transports->firstItem(),
                'to' => $transports->lastItem(),
            ]);
        }

        return view('pages.student.transport-assign', compact('transports', 'classes', 'vehicles', 'busStops'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'student_id' => 'required|exists:students,id',
            'bus_stop_id' => 'required|exists:bus_stops,id',
            'arrival_vehicle_id' => 'required|exists:vehicles,id',
            'departure_vehicle_id' => 'required|exists:vehicles,id',
            'start_date' => 'required|date',
        ]);

        $data = $request->only(['student_id', 'bus_stop_id', 'arrival_vehicle_id', 'departure_vehicle_id', 'monthly_charge', 'start_date']);
        $data['status'] = 'Start';

        if ($request->filled('id')) {
            $transport = \App\Models\StudentTransport::findOrFail($request->id);
            $transport->update($data);
        } else {
            $data['allotment_no'] = 'TRAN' . time(); // Basic unique allotment number
            $transport = \App\Models\StudentTransport::create($data);
        }

        return response()->json(['success' => true, 'message' => 'Transport assignment saved successfully!', 'data' => $transport]);
    }

    public function stop($id)
    {
        $transport = \App\Models\StudentTransport::findOrFail($id);
        $transport->update(['status' => 'Stop', 'end_date' => now()]);

        return response()->json(['success' => true, 'message' => 'Transport service stopped for student.']);
    }

    public function searchStudents(Request $request)
    {
        $search = $request->get('q');
        $students = \App\Models\Student::with(['user', 'class'])
            ->whereHas('user', function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%");
            })
            ->orWhere('admission_no', 'like', "%{$search}%")
            ->take(10)
            ->get()
            ->map(function($student) {
                return [
                    'id' => $student->id,
                    'text' => $student->user->name . " [" . ($student->admission_no ?? 'No Reg') . "] - " . ($student->class->name ?? 'No Class')
                ];
            });

        return response()->json($students);
    }
}
