<?php

namespace Database\Seeders;

use App\Models\Checklist\ListAssignment;
use App\Models\Checklist\Task;
use App\Models\Checklist\TaskList;
use App\Models\Organisation\Company;
use App\Models\Organisation\Location;
use App\Models\Organisation\User;
use App\Models\Submissions\Submission;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class HorecaScreenshotSeeder extends Seeder
{
    public function run(): void
    {
        $company = Company::updateOrCreate(['email' => 'demo-horeca@taskcheck.test'], [
            'name' => 'Restaurant De Haven', 'company_type' => 'horeca', 'address' => 'Havenstraat 12, Rotterdam',
            'phone' => '010 123 45 67', 'subscription_plan' => 'business', 'subscription_status' => 'trial',
            'trial_ends_at' => now()->addDays(14), 'is_active' => true, 'onboarding_completed_at' => now(),
            'onboarding_step' => Company::ONBOARDING_STEP_COMPLETED, 'working_hours' => Company::defaultWorkingHours(),
        ]);
        $location = Location::updateOrCreate(['company_id' => $company->id, 'name' => 'Rotterdam Centrum'], [
            'address' => 'Havenstraat 12, 3011 AA Rotterdam', 'street' => 'Havenstraat', 'house_number' => '12',
            'postal_code' => '3011 AA', 'city' => 'Rotterdam', 'is_active' => true,
        ]);
        $admin = User::updateOrCreate(['email' => 'horeca@taskcheck.test'], [
            'company_id' => $company->id, 'location_id' => $location->id, 'name' => 'Sophie de Vries',
            'password' => Hash::make('password'), 'role' => 'admin', 'department' => 'Management', 'is_active' => true,
        ]);
        $employee = User::updateOrCreate(['email' => 'keuken@taskcheck.test'], [
            'company_id' => $company->id, 'location_id' => $location->id, 'name' => 'Milan Jansen',
            'password' => Hash::make('password'), 'role' => 'employee', 'department' => 'Keuken', 'is_active' => true,
        ]);

        $definitions = [
            ['Opening keuken', 'Dagelijkse openingscontrole voor een veilige start.', 'daily', 'high', ['Handenwasstation controleren', 'Koelcel temperatuur meten', 'Werkbanken desinfecteren']],
            ['Temperatuurregistratie', 'HACCP-controle van koelingen en vriezers.', 'daily', 'urgent', ['Koelcel 1', 'Werkbankkoeling', 'Vriezer 1']],
            ['Schoonmaak afsluiting', 'Reiniging en afsluiting van keuken en uitgifte.', 'daily', 'medium', ['Werkbanken reinigen', 'Vloer desinfecteren', 'Afvalbakken legen']],
            ['Ingangscontrole leveringen', 'Controle van temperatuur, THT en verpakking.', 'weekly', 'high', ['Temperatuur levering', 'Verpakking en THT', 'Levering accepteren']],
        ];

        foreach ($definitions as $listIndex => [$title, $description, $schedule, $priority, $tasks]) {
            $list = TaskList::updateOrCreate(['company_id' => $company->id, 'title' => $title], [
                'description' => $description, 'created_by' => $admin->id, 'location_id' => $location->id,
                'schedule_type' => $schedule, 'schedule_config' => $schedule === 'weekly' ? ['show_on_days' => ['monday', 'wednesday', 'friday']] : [],
                'priority' => $priority, 'category' => 'Horeca / HACCP', 'is_active' => true, 'requires_review' => true,
            ]);
            ListAssignment::updateOrCreate(['list_id' => $list->id, 'user_id' => $employee->id], ['assigned_date' => today(), 'is_active' => true]);
            foreach ($tasks as $index => $taskTitle) {
                Task::updateOrCreate(['list_id' => $list->id, 'title' => $taskTitle], [
                    'description' => $index === 1 ? 'Registreer de meting en voeg bij een afwijking een toelichting toe.' : 'Controleer en bevestig volgens de werkinstructie.',
                    'required_proof_type' => $index === 1 ? 'text' : 'photo', 'is_required' => true,
                    'validation_rules' => str_contains(strtolower($taskTitle), 'temperatuur') || str_contains(strtolower($taskTitle), 'koel')
                        ? ['metric' => 'temperature', 'max' => 7, 'unit' => '°C', 'critical' => true] : [],
                    'order_index' => $index + 1, 'created_by' => $admin->id, 'is_active' => true,
                ]);
            }

            $submission = Submission::updateOrCreate(['company_id' => $company->id, 'list_id' => $list->id, 'user_id' => $employee->id], [
                'started_at' => now()->subMinutes(45 + $listIndex * 20), 'completed_at' => $listIndex < 3 ? now()->subMinutes(10 + $listIndex * 5) : null,
                'status' => $listIndex < 2 ? 'completed' : ($listIndex === 2 ? 'reviewed' : 'in_progress'),
            ]);
            foreach ($list->tasks as $index => $task) {
                $submission->submissionTasks()->updateOrCreate(['task_id' => $task->id], [
                    'status' => $listIndex === 3 && $index > 0 ? 'pending' : ($listIndex === 2 ? 'approved' : 'completed'),
                    'proof_text' => str_contains(strtolower($task->title), 'koel') ? ($listIndex === 1 ? '8,4 °C — deur stond niet volledig gesloten' : '4,2 °C') : null,
                    'employee_comment' => $listIndex === 1 && $index === 0 ? 'Afwijking gemeld; koeling opnieuw gecontroleerd.' : null,
                    'completed_at' => $listIndex === 3 && $index > 0 ? null : now()->subMinutes(15),
                    'completed_by_user_id' => $employee->id,
                ]);
            }
        }
    }
}
