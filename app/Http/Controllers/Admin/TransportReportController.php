<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\BusStop;
use App\Models\Vehicle;
use App\Models\StudentTransport;
use App\Models\SchoolClass;
use Illuminate\Support\Facades\DB;

class TransportReportController extends Controller
{
    public function index()
    {
        $classes = SchoolClass::all();
        $sessions = ['2025-2026', '2024-2025']; // Mock sessions if not in DB
        return view('pages.student.transport-report', compact('classes', 'sessions'));
    }

    public function generate(Request $request)
    {
        $type = $request->get('type', 'route');
        $session = $request->get('session');
        $classId = $request->get('class_id');

        if ($type === 'route') {
            $data = BusStop::withCount('transports as students_count')
                ->withSum('transports as total_collection', 'monthly_charge')
                ->with(['vehicles' => function($q) {
                    $q->select('vehicles.id', 'vehicle_no', 'driver_name');
                }])
                ->get()
                ->map(function($r) {
                    return [
                        'route' => $r->name,
                        'vehicle' => $r->vehicles->pluck('vehicle_no')->implode(', ') ?: 'N/A',
                        'driver' => $r->vehicles->pluck('driver_name')->implode(', ') ?: 'N/A',
                        'students' => $r->students_count,
                        'collection' => $r->total_collection ?: 0
                    ];
                });
        } elseif ($type === 'vehicle') {
            $data = Vehicle::withCount(['arrivals as arrival_students', 'departures as departure_students'])
                ->withSum('arrivals as arrival_collection', 'monthly_charge')
                ->withSum('departures as departure_collection', 'monthly_charge')
                ->withCount('busStops as routes_count')
                ->get()
                ->map(function($v) {
                    $students = max($v->arrival_students, $v->departure_students); // Simplified: count unique students is complex with this schema
                    $collection = ($v->arrival_collection ?: 0) + ($v->departure_collection ?: 0); 
                    // Wait, student_transports has both arrival and departure vehicle. 
                    // I should count unique student_transports for this vehicle.
                    $uniqueStudents = StudentTransport::where('arrival_vehicle_id', $v->id)
                        ->orWhere('departure_vehicle_id', $v->id)
                        ->count();
                    $uniqueCollection = StudentTransport::where('arrival_vehicle_id', $v->id)
                        ->orWhere('departure_vehicle_id', $v->id)
                        ->sum('monthly_charge');
                        
                    return [
                        'vehicle' => $v->vehicle_no,
                        'driver' => $v->driver_name,
                        'routes' => $v->routes_count,
                        'students' => $uniqueStudents,
                        'collection' => $uniqueCollection
                    ];
                });
        } elseif ($type === 'class') {
            $query = StudentTransport::join('students', 'student_transports.student_id', '=', 'students.id')
                ->join('classes', 'students.class_id', '=', 'classes.id')
                ->select('classes.name as class', DB::raw('count(*) as students'), DB::raw('sum(monthly_charge) as collection'));
            
            if ($classId) {
                $query->where('students.class_id', $classId);
            }
            
            $data = $query->groupBy('classes.name')->get();
        } else { // overall
            $totalStudents = StudentTransport::count();
            $totalCollection = StudentTransport::sum('monthly_charge');
            $activeVehicles = Vehicle::count();
            $totalRoutes = BusStop::count();
            
            $data = [
                ['metric' => 'Total Students', 'value' => $totalStudents],
                ['metric' => 'Total Collection', 'value' => '₹' . number_format($totalCollection, 2)],
                ['metric' => 'Active Vehicles', 'value' => $activeVehicles],
                ['metric' => 'Total Routes', 'value' => $totalRoutes],
            ];
        }

        return response()->json(['success' => true, 'data' => $data]);
    }
}
