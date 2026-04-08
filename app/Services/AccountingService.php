<?php

namespace App\Services;

use App\Models\StudentLedger;
use App\Models\AuditLog;
use Illuminate\Support\Facades\DB;

class AccountingService
{
    /**
     * Insert a new immutable record into the ledger.
     * Rebuilds the balance from this date forward to ensure integrity.
     */
    public function addLedgerEntry($studentId, $date, $type, $amount, $referenceType = null, $referenceId = null, $description = null, $session = null)
    {
        return DB::transaction(function () use ($studentId, $date, $type, $amount, $referenceType, $referenceId, $description, $session) {
            
            // Just insert with balance 0 initially
            $ledger = StudentLedger::create([
                'student_id' => $studentId,
                'date' => $date,
                'transaction_type' => $type,
                'amount' => $amount,
                'reference_type' => $referenceType,
                'reference_id' => $referenceId,
                'description' => $description,
                'session' => $session,
                'balance' => 0,
                'created_by' => auth()->id() ?? null,
            ]);

            // Rebuild the ledger specifically for this student to maintain the running balance
            $this->rebuildStudentLedger($studentId);

            return $ledger;
        });
    }

    /**
     * Recalculates the running balance chronologically for a specific student.
     * Debit types ('Fee', 'Refund') increase the balance owed.
     * Credit types ('Payment', 'Reversal', 'Discount') decrease the balance owed.
     */
    public function rebuildStudentLedger($studentId)
    {
        $ledgers = StudentLedger::where('student_id', $studentId)
            ->orderBy('date', 'asc')
            ->orderBy('id', 'asc') // tie breaker
            ->get();

        $balance = 0;
        foreach ($ledgers as $ledger) {
            // "Balance" here represents the amount the student OWES the school.
            if (in_array($ledger->transaction_type, ['Fee', 'Refund'])) {
                $balance += $ledger->amount;
            } elseif (in_array($ledger->transaction_type, ['Payment', 'Reversal', 'Discount'])) {
                $balance -= $ledger->amount;
            }

            if ($ledger->balance != $balance) {
                // Raw update to prevent triggering eloquent events if any
                StudentLedger::where('id', $ledger->id)->update(['balance' => $balance]);
            }
        }

        return $balance;
    }

    /**
     * Cancel a payment immutably by applying a reversing ledger entry.
     */
    public function cancelPayment($paymentId, $reason)
    {
        return DB::transaction(function () use ($paymentId, $reason) {
            $payment = \App\Models\Payment::findOrFail($paymentId);
            
            if ($payment->is_cancelled) {
                throw new \Exception("Payment is already cancelled.");
            }

            // Mark as cancelled immutably
            $payment->update([
                'is_cancelled' => true,
                'cancellation_reason' => $reason,
                'cancelled_at' => now(),
                'cancelled_by' => auth()->id() ?? null,
            ]);

            // Add reversion to the ledger.
            // A Payment was a Credit. Reversing it means we add a Debit.
            // According to rebuildStudentLedger, 'Refund' increases the balance owed.
            $this->addLedgerEntry(
                $payment->student_id, 
                now(), 
                'Refund', 
                $payment->amount, 
                get_class($payment), 
                $payment->id, 
                "Receipt Cancelled: " . $reason
            );

            // Audit
            $this->logAudit('Cancel Payment', get_class($payment), $payment->id, null, ['is_cancelled' => true, 'reason' => $reason]);

            return $payment;
        });
    }

    /**
     * Log an audit trail securely.
     */
    public function logAudit($action, $modelType, $modelId, $oldValues = null, $newValues = null, $reason = null)
    {
        return AuditLog::create([
            'user_id' => auth()->id() ?? null,
            'action' => $action,
            'model_type' => $modelType,
            'model_id' => $modelId,
            'old_values' => $oldValues,
            'new_values' => $newValues,
            'ip_address' => request()->ip(),
            'reason' => $reason,
        ]);
    }
}
