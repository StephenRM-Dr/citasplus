<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Business;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class BusinessController extends Controller
{
    public function dashboard()
    {
        $business = Auth::user()->business;

        if (!$business) {
            return redirect('/')->with('error', 'Tu cuenta no tiene un negocio asociado.');
        }

        // Citas de hoy
        $todayAppointments = Appointment::with(['client', 'service', 'employee'])
            ->where('business_id', $business->id)
            ->whereDate('start_time', Carbon::today())
            ->orderBy('start_time')
            ->get();
            
        // Resumen de ingresos (mes actual)
        $monthlyRevenue = Payment::whereHas('appointment', function($query) use ($business) {
                $query->where('business_id', $business->id);
            })
            ->where('status', 'completed')
            ->whereMonth('created_at', Carbon::now()->month)
            ->sum('amount');
            
        // Tasa de ocupación (ejemplo simplificado)
        $totalSlots = $business->employees->count() * 8; // 8 slots de 1h por empleado
        $bookedSlots = Appointment::where('business_id', $business->id)
            ->whereDate('start_time', Carbon::today())
            ->count();
            
        $occupancyRate = $totalSlots > 0 ? round(($bookedSlots / $totalSlots) * 100) : 0;
        
        return view('business.dashboard', compact(
            'todayAppointments', 
            'monthlyRevenue',
            'occupancyRate'
        ));
    }
    
    public function calendar()
    {
        $business = Auth::user()->business;

        if (!$business) {
            return redirect('/')->with('error', 'Tu cuenta no tiene un negocio asociado.');
        }

        $employees = $business->employees;
        $services = $business->services;

        return view('business.calendar', compact('employees', 'services'));
    }

    public function calendarEvents()
    {
        $business = Auth::user()->business;

        if (!$business) {
            return response()->json([]);
        }

        $colors = [
            'pending' => '#f59e0b',
            'confirmed' => '#1d4ed8',
            'completed' => '#10b981',
            'cancelled' => '#ef4444',
            'no_show' => '#6b7280',
        ];

        $appointments = Appointment::with(['client', 'service', 'employee'])
            ->where('business_id', $business->id)
            ->get();

        $events = $appointments->map(function ($appointment) use ($colors) {
            return [
                'title' => $appointment->service->name . ' - ' . $appointment->client->name,
                'start' => $appointment->start_time->toIso8601String(),
                'end' => $appointment->end_time->toIso8601String(),
                'color' => $colors[$appointment->status] ?? '#1d4ed8',
            ];
        });

        return response()->json($events);
    }
}
