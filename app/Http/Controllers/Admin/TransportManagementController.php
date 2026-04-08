<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\BusStop;
use App\Models\Vehicle;

class TransportManagementController extends Controller
{
    public function index()
    {
        $routes = BusStop::all();
        $vehicles = Vehicle::all();
        $assignments = Vehicle::has('busStops')->with('busStops')->get();
        return view('pages.student.transport-management', compact('routes', 'vehicles', 'assignments'));
    }

    public function storeRoute(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:bus_stops,name',
            'monthly_charge' => 'required|numeric|min:0',
        ]);

        $route = BusStop::create($request->all());
        return response()->json(['success' => true, 'message' => 'Route added successfully', 'data' => $route]);
    }

    public function updateRoute(Request $request, $id)
    {
        $route = BusStop::findOrFail($id);
        $request->validate([
            'name' => 'required|unique:bus_stops,name,' . $id,
            'monthly_charge' => 'required|numeric|min:0',
        ]);

        $route->update($request->all());
        return response()->json(['success' => true, 'message' => 'Route updated successfully', 'data' => $route]);
    }

    public function deleteRoute($id)
    {
        $route = BusStop::findOrFail($id);
        $route->delete();
        return response()->json(['success' => true, 'message' => 'Route deleted successfully']);
    }

    public function storeVehicle(Request $request)
    {
        $request->validate([
            'vehicle_no' => 'required|unique:vehicles,vehicle_no',
            'driver_name' => 'required',
            'driver_phone' => 'required',
            'vehicle_type' => 'required',
        ]);

        $vehicle = Vehicle::create($request->all());
        return response()->json(['success' => true, 'message' => 'Vehicle added successfully', 'data' => $vehicle]);
    }

    public function updateVehicle(Request $request, $id)
    {
        $vehicle = Vehicle::findOrFail($id);
        $request->validate([
            'vehicle_no' => 'required|unique:vehicles,vehicle_no,' . $id,
            'driver_name' => 'required',
            'driver_phone' => 'required',
            'vehicle_type' => 'required',
        ]);

        $vehicle->update($request->all());
        return response()->json(['success' => true, 'message' => 'Vehicle updated successfully', 'data' => $vehicle]);
    }

    public function deleteVehicle($id)
    {
        $vehicle = Vehicle::findOrFail($id);
        $vehicle->delete();
        return response()->json(['success' => true, 'message' => 'Vehicle deleted successfully']);
    }

    public function assignRoutes(Request $request)
    {
        $request->validate([
            'vehicle_id' => 'required|exists:vehicles,id',
            'route_ids' => 'array',
            'route_ids.*' => 'exists:bus_stops,id',
        ]);

        $vehicle = Vehicle::findOrFail($request->vehicle_id);
        $vehicle->busStops()->sync($request->route_ids ?? []);

        return response()->json(['success' => true, 'message' => 'Routes assigned successfully']);
    }
}
