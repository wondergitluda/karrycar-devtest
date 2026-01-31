<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class PatchReferentsData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:patch-referents-data';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Deduplicate referents and clean invalid shipment associations';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $this->info('=== REFERENTS DATA CLEANUP ===');
        $this->newLine();

        // Task 1: Deduplicate referents (required)
        $this->task1DeduplicateReferents();

        // Task 2: Clean invalid associations (optional)
        if ($this->confirm('Do you want to clean invalid referent-shipment associations (different team_id)?', false)) {
            $this->task2CleanInvalidAssociations();
        }

        $this->newLine();
        $this->info('✓ Cleanup completed successfully!');
    }

    /**
     * Task 1: Deduplicate referents based on email and team_id.
     */
    private function task1DeduplicateReferents(): void
    {
        $this->info('TASK 1: Deduplicating referents...');

        $duplicateGroups = DB::table('referents')
            ->select('team_id', 'email', DB::raw('MIN(id) as parent_id'), DB::raw('COUNT(*) as count'))
            ->groupBy('team_id', 'email')
            ->having(DB::raw('COUNT(*)'), '>', 1)
            ->get();

        foreach ($duplicateGroups as $group) {
            $parentId = $group->parent_id;

            // Get all duplicate IDs (excluding the parent)
            $duplicateIds = DB::table('referents')
                ->where('team_id', $group->team_id)
                ->where('email', $group->email)
                ->where('id', '!=', $parentId)
                ->pluck('id')
                ->toArray();

            if (empty($duplicateIds)) {
                continue;
            }

            // Get all pivot records for duplicates
            $pivotRecords = DB::table('referent_shipment')
                ->whereIn('referent_id', $duplicateIds)
                ->get();

            foreach ($pivotRecords as $pivotRecord) {
                // Check if parent already has this shipment+scope combination
                $existingRecord = DB::table('referent_shipment')
                    ->where('referent_id', $parentId)
                    ->where('shipment_id', $pivotRecord->shipment_id)
                    ->where('scope', $pivotRecord->scope)
                    ->exists();

                if ($existingRecord) {
                    // Delete duplicate pivot record instead of updating
                    DB::table('referent_shipment')
                        ->where('id', $pivotRecord->id)
                        ->delete();
                } else {
                    // Safe to update to parent
                    DB::table('referent_shipment')
                        ->where('id', $pivotRecord->id)
                        ->update(['referent_id' => $parentId]);
                }
            }

            // Soft delete the duplicate referents
            DB::table('referents')
                ->whereIn('id', $duplicateIds)
                ->update(['deleted_at' => now()]);
        }

        $totalDuplicates = $duplicateGroups->sum('count') - $duplicateGroups->count();
        $this->info("✓ Deduplicated {$totalDuplicates} referent(s)");
    }

    /**
     * Task 2: Clean invalid referent-shipment associations.
     */
    private function task2CleanInvalidAssociations(): void
    {
        $this->info('TASK 2: Cleaning invalid associations...');

        // Find associations where referent team_id != shipment team_id
        $invalidAssociations = DB::table('referent_shipment as rs')
            ->join('referents as r', 'rs.referent_id', '=', 'r.id')
            ->join('shipments as s', 'rs.shipment_id', '=', 's.id')
            ->whereColumn('r.team_id', '!=', 's.team_id')
            ->select('rs.id', 'rs.referent_id', 'rs.shipment_id', 'r.team_id as referent_team', 's.team_id as shipment_team')
            ->get();

        if ($invalidAssociations->isEmpty()) {
            $this->info('✓ No invalid associations found!');

            return;
        }

        $this->warn("Found {$invalidAssociations->count()} invalid association(s):");

        // Show a sample of invalid associations
        foreach ($invalidAssociations->take(5) as $assoc) {
            $this->line("  - Referent #{$assoc->referent_id} (team {$assoc->referent_team}) linked to Shipment #{$assoc->shipment_id} (team {$assoc->shipment_team})");
        }

        if ($invalidAssociations->count() > 5) {
            $this->line('  ... and '.($invalidAssociations->count() - 5).' more');
        }

        if ($this->confirm('Delete these invalid associations?', true)) {
            $deletedCount = DB::table('referent_shipment')
                ->whereIn('id', $invalidAssociations->pluck('id'))
                ->delete();

            $this->info("✓ Deleted {$deletedCount} invalid association(s)");
        } else {
            $this->warn('Skipped deletion');
        }
    }
}
