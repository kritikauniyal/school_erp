<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class RebuildStudentLedgers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'accounting:rebuild-ledgers {student_id?} {--all}';

    protected $description = 'Rebuilds chronological running balances for student ledgers from scratch';

    public function handle(\App\Services\AccountingService $accounting)
    {
        $studentId = $this->argument('student_id');
        $all = $this->option('all');

        if (!$studentId && !$all) {
            $this->error('Please specify a student ID or use the --all flag.');
            return 1;
        }

        $query = \App\Models\Student::query();
        if ($studentId) {
            $query->where('id', $studentId);
        }

        $count = $query->count();
        if ($count === 0) {
            $this->info("No students found.");
            return 0;
        }

        $this->info("Starting ledger rebuild for {$count} student(s)...");
        $bar = $this->output->createProgressBar($count);
        $bar->start();
        
        $query->chunk(100, function ($students) use ($accounting, $bar) {
            foreach ($students as $student) {
                $accounting->rebuildStudentLedger($student->id);
                $bar->advance();
            }
        });
        
        $bar->finish();
        $this->newLine();
        $this->info("Ledger rebuild complete!");
        
        return 0;
    }
}
