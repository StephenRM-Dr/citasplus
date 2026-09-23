<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Business;
use App\Models\Service;
use App\Models\Employee;
use App\Models\BusinessHour;
use App\Models\Appointment;
use App\Models\Payment;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        // 1. Crear Usuario de Negocio
        $admin = User::create([
            'name' => 'Admin Barbería',
            'email' => 'admin@barberia.com',
            'password' => Hash::make('admin'),
            'role' => 'business',
            'phone' => '+1234567890',
        ]);

        // 2. Crear Negocio
        $business = Business::create([
            'user_id' => $admin->id,
            'name' => 'Premium Barber Shop',
            'description' => 'La mejor barbería de la ciudad con servicios premium',
            'address' => 'Calle Principal 123',
            'phone' => '+1234567890',
            'email' => 'info@premiumbarber.com',
        ]);

        // 3. Crear Servicios
        $services = [
            ['name' => 'Corte de Cabello', 'description' => 'Corte moderno', 'duration' => 30, 'price' => 15.00],
            ['name' => 'Arreglo de Barba', 'description' => 'Arreglo y perfilado', 'duration' => 20, 'price' => 10.00],
            ['name' => 'Combo Corte y Barba', 'description' => 'El pack completo', 'duration' => 50, 'price' => 22.00],
        ];

        foreach ($services as $s) {
            Service::create(array_merge($s, ['business_id' => $business->id]));
        }

        // 4. Crear Empleados
        $employees = [
            ['name' => 'Carlos Rodríguez', 'specialty' => 'Cortes modernos'],
            ['name' => 'Luis García', 'specialty' => 'Barbas y tradicional'],
        ];

        foreach ($employees as $e) {
            Employee::create(array_merge($e, ['business_id' => $business->id]));
        }

        // 5. Configurar Horarios
        for ($i = 1; $i <= 6; $i++) {
            BusinessHour::create([
                'business_id' => $business->id,
                'day_of_week' => $i,
                'open_time' => '09:00',
                'close_time' => '18:00',
                'is_closed' => false,
            ]);
        }
        BusinessHour::create([
            'business_id' => $business->id,
            'day_of_week' => 0,
            'is_closed' => true,
        ]);

        // 6. Crear Clientes Demo
        $clients = [
            ['name' => 'María Fernández', 'email' => 'maria@demo.com', 'phone' => '+1234500001'],
            ['name' => 'Jorge Martínez', 'email' => 'jorge@demo.com', 'phone' => '+1234500002'],
            ['name' => 'Ana Torres', 'email' => 'ana@demo.com', 'phone' => '+1234500003'],
            ['name' => 'Diego Ramírez', 'email' => 'diego@demo.com', 'phone' => '+1234500004'],
        ];

        $clientUsers = collect($clients)->map(function ($c) {
            return User::create(array_merge($c, [
                'password' => Hash::make('cliente123'),
                'role' => 'customer',
            ]));
        });

        // 7. Crear Citas y Pagos de Ejemplo (pasadas, de hoy y futuras)
        $employeeModels = Employee::where('business_id', $business->id)->get();
        $serviceModels = Service::where('business_id', $business->id)->get();

        $appointmentPlan = [
            ['days' => -3, 'time' => '10:00', 'status' => 'completed'],
            ['days' => -2, 'time' => '15:00', 'status' => 'completed'],
            ['days' => -1, 'time' => '11:00', 'status' => 'no_show'],
            ['days' => 0, 'time' => '09:30', 'status' => 'confirmed'],
            ['days' => 0, 'time' => '14:00', 'status' => 'confirmed'],
            ['days' => 1, 'time' => '10:30', 'status' => 'confirmed'],
            ['days' => 2, 'time' => '16:00', 'status' => 'pending'],
            ['days' => -5, 'time' => '12:00', 'status' => 'cancelled'],
        ];

        foreach ($appointmentPlan as $i => $plan) {
            $client = $clientUsers[$i % $clientUsers->count()];
            $employee = $employeeModels[$i % $employeeModels->count()];
            $service = $serviceModels[$i % $serviceModels->count()];

            $start = Carbon::today()->addDays($plan['days'])->setTimeFromTimeString($plan['time']);
            $end = $start->copy()->addMinutes($service->duration);

            $appointment = Appointment::create([
                'business_id' => $business->id,
                'client_id' => $client->id,
                'service_id' => $service->id,
                'employee_id' => $employee->id,
                'start_time' => $start,
                'end_time' => $end,
                'status' => $plan['status'],
                'notes' => null,
            ]);

            if ($plan['status'] === 'completed') {
                Payment::create([
                    'appointment_id' => $appointment->id,
                    'amount' => $service->price,
                    'status' => 'completed',
                    'payment_method' => 'cash',
                    'transaction_id' => null,
                ]);
            }
        }
    }
}
