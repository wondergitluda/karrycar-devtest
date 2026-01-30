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
    protected $description = 'Deduplicate referents based on email and team_id';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        if (! $this->confirm('This will deduplicate referents based on email and team_id. Do you wish to continue?')) {
            return;
        }

        $duplicateGroups = DB::table('referents')
            ->select('team_id', 'email', DB::raw('MIN(id) as parent_id'), DB::raw('COUNT(*) as count'))
            ->whereNotNull('email')
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

    }
}
