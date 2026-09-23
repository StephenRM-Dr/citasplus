<?php

namespace App\Http\Controllers;

use App\Models\Business;
use App\Models\Service;
use App\Models\Employee;
use App\Models\Appointment;
use App\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Str;

class PublicController extends Controller
{
    public function booking($businessId)
    {
        $business = Business::with('services')->findOrFail($businessId);
        $services = $business->services;
        
        return view('public.booking', compact('business', 'services'));
    }
    
    public function getAvailableSlots(Request $request, $businessId)
    {
        $business = Business::findOrFail($businessId);
        $service = Service::findOrFail($request->service_id);
        $date = Carbon::parse($request->date);
        
        // Obtener empleados que ofrecen este servicio
        $employees = $business->employees;
        
        // Obtener horario del negocio para este día
        $businessHours = $business->businessHours()
            ->where('day_of_week', $date->dayOfWeek)
            ->first();
            
        if (!$businessHours || $businessHours->is_closed) {
            return response()->json([]);
        }
        
        // Generar slots de tiempo (por ejemplo, cada 30 min)
        $slots = [];
        $startTime = Carbon::parse($date->format('Y-m-d') . ' ' . $businessHours->open_time);
        $endTime = Carbon::parse($date->format('Y-m-d') . ' ' . $businessHours->close_time);
        
        $currentTime = $startTime->copy();
        
        while ($currentTime->copy()->addMinutes($service->duration)->lessThanOrEqualTo($endTime)) {
            $slotEnd = $currentTime->copy()->addMinutes($service->duration);
            
            // Verificar si hay algún empleado disponible en este rango completo [currentTime, slotEnd]
            foreach ($employees as $employee) {
                $hasOverlap = Appointment::where('employee_id', $employee->id)
                    ->where('status', 'confirmed')
                    ->where(function($query) use ($currentTime, $slotEnd) {
                        $query->where(function($q) use ($currentTime, $slotEnd) {
                            $q->where('start_time', '<', $slotEnd)
                              ->where('end_time', '>', $currentTime);
                        });
                    })
                    ->exists();
                        
                if (!$hasOverlap) {
                    $slots[] = [
                        'time' => $currentTime->format('H:i'),
                        'employee_id' => $employee->id,
                        'employee_name' => $employee->name,
                        'end_time' => $slotEnd->format('H:i')
                    ];
                    break; // Si un empleado está libre, el slot es válido
                }
            }
            $currentTime->addMinutes(30); // Intervalos de 30 minutos
        }
        
        return response()->json($slots);
    }
    
    public function bookAppointment(Request $request, $businessId)
    {
        $request->validate([
            'service_id' => 'required|exists:services,id',
            'employee_id' => 'required|exists:employees,id',
            'date' => 'required|date',
            'time' => 'required',
            'name' => 'required|string',
            'email' => 'required|email',
            'phone' => 'required|string'
        ]);
        
        // Buscar o crear usuario cliente
        $client = User::firstOrCreate(
            ['email' => $request->email],
            [
                'name' => $request->name,
                'phone' => $request->phone,
                'password' => bcrypt(Str::random(10)),
                'role' => 'customer'
            ]
        );
        
        $startTime = Carbon::parse($request->date . ' ' . $request->time);
        $service = Service::find($request->service_id);
        $endTime = $startTime->copy()->addMinutes($service->duration);

        // Validar disponibilidad final (Prevención de colisiones)
        $isBooked = Appointment::where('employee_id', $request->employee_id)
            ->where('status', 'confirmed')
            ->where(function($query) use ($startTime, $endTime) {
                $query->where('start_time', '<', $endTime)
                      ->where('end_time', '>', $startTime);
            })
            ->exists();

        if ($isBooked) {
            return response()->json([
                'success' => false,
                'message' => 'Lo sentimos, este horario ya ha sido reservado por otra persona.'
            ], 422);
        }
        
        $appointment = Appointment::create([
            'business_id' => $businessId,
            'client_id' => $client->id,
            'service_id' => $request->service_id,
            'employee_id' => $request->employee_id,
            'start_time' => $startTime,
            'end_time' => $endTime,
            'status' => 'confirmed',
            'notes' => $request->notes
        ]);
        
        return response()->json([
            'success' => true,
            'message' => 'Cita reservada correctamente.',
            'appointment' => $appointment
        ]);
    }
}
