<?php

namespace App\Imports;

use App\Models\Area;
use App\Models\State;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class AreasImport implements ToCollection, WithHeadingRow
{
    private $successCount = 0;
    private $failCount = 0;
    private $updatedCount = 0;
    private $failedRows = [];

    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {
            try {
                // Normalize column names (case insensitive)
                $rowArray = $row->toArray();
                $normalizedRow = array_change_key_case($rowArray, CASE_LOWER);
                
                // Get values with fallback for different column name variations
                $stateName = $normalizedRow['state name'] ?? $normalizedRow['state'] ?? $normalizedRow['state_name'] ?? null;
                $areaName = $normalizedRow['area'] ?? $normalizedRow['area name'] ?? $normalizedRow['area_name'] ?? null;
                $shipping = $normalizedRow['shipping'] ?? $normalizedRow['shipping cost'] ?? $normalizedRow['shipping_cost'] ?? 0;

                if (empty($stateName)) {
                    throw new \Exception("State name is required");
                }

                if (empty($areaName)) {
                    throw new \Exception("Area name is required");
                }

                // Find state by name
                $state = State::where('state', $stateName)->first();
                
                if (!$state) {
                    throw new \Exception("State '$stateName' not found");
                }

                // Check if area already exists
                $existingArea = Area::where('state_id', $state->id)
                    ->where('area', $areaName)
                    ->first();

                if ($existingArea) {
                    // Update existing area
                    $existingArea->update([
                        'shipping' => $shipping,
                    ]);
                    $this->updatedCount++;
                } else {
                    // Create new area
                    Area::create([
                        'state_id' => $state->id,
                        'area' => $areaName,
                        'shipping' => $shipping,
                    ]);
                    $this->successCount++;
                }
            } catch (\Exception $e) {
                $this->failCount++;
                $this->failedRows[] = [
                    'row' => $row->toArray(),
                    'error' => $e->getMessage()
                ];
            }
        }
    }

    public function getSuccessCount()
    {
        return $this->successCount;
    }

    public function getUpdatedCount()
    {
        return $this->updatedCount;
    }

    public function getFailCount()
    {
        return $this->failCount;
    }

    public function getFailedRows()
    {
        return $this->failedRows;
    }
}