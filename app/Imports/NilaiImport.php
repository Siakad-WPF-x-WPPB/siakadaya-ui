<?php
namespace App\Imports;

use App\Models\Nilai;
use App\Models\FrsDetail;
use App\Models\Mahasiswa;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class NilaiImport implements ToCollection, WithHeadingRow
{
    protected $jadwalId;
    protected $errors = [];
    protected $processedRows = 0;

    public function __construct($jadwalId)
    {
        $this->jadwalId = $jadwalId;
        Log::info("NilaiImport initialized for jadwal ID: {$jadwalId}");
    }

    public function collection(Collection $rows)
    {
        Log::info("Processing " . $rows->count() . " rows from Excel");

        foreach ($rows as $index => $row) {
            $this->processedRows++;
            Log::info("Processing row {$this->processedRows}: " . json_encode($row->toArray()));

            // Convert and validate NRP (handle both string and numeric values)
            $nrp = isset($row['nrp']) ? (string) $row['nrp'] : '';
            $nrp = trim($nrp); // Remove any whitespace

            if (empty($nrp)) {
                $error = "Row {$this->processedRows}: NRP is empty";
                $this->errors[] = $error;
                Log::warning($error);
                continue;
            }

            // Validate nilai_angka (handle both string and numeric values)
            $nilaiAngka = isset($row['nilai_angka']) ? $row['nilai_angka'] : '';

            if ($nilaiAngka === '' || $nilaiAngka === null) {
                $error = "Row {$this->processedRows}: Nilai angka is empty for NRP {$nrp}";
                $this->errors[] = $error;
                Log::warning($error);
                continue;
            }

            // Convert to integer and validate range
            $nilaiAngka = (int) $nilaiAngka;

            if ($nilaiAngka < 0 || $nilaiAngka > 100) {
                $error = "Row {$this->processedRows}: Nilai angka must be between 0-100 for NRP {$nrp}";
                $this->errors[] = $error;
                Log::warning($error);
                continue;
            }

            // Find mahasiswa by NRP
            $mahasiswa = Mahasiswa::where('nrp', $nrp)->first();

            if (!$mahasiswa) {
                $error = "Row {$this->processedRows}: Mahasiswa dengan NRP {$nrp} tidak ditemukan";
                $this->errors[] = $error;
                Log::warning($error);
                continue;
            }

            Log::info("Found mahasiswa: {$mahasiswa->nama} (ID: {$mahasiswa->id})");

            // Find FrsDetail
            $frsDetail = FrsDetail::where('jadwal_id', $this->jadwalId)
                ->whereHas('frs', function ($query) use ($mahasiswa) {
                    $query->where('mahasiswa_id', $mahasiswa->id);
                })
                ->where('status', 'disetujui')
                ->first();

            if (!$frsDetail) {
                $error = "Row {$this->processedRows}: Data FRS tidak ditemukan untuk mahasiswa {$nrp}";
                $this->errors[] = $error;
                Log::warning($error);
                continue;
            }

            Log::info("Found FrsDetail ID: {$frsDetail->id}");

            try {
                // Calculate grade details
                $gradeDetails = $this->calculateGradeDetails($nilaiAngka);

                Log::info("Calculated grade for {$nrp}: {$nilaiAngka} -> {$gradeDetails['nilai_huruf']} ({$gradeDetails['status']})");

                // Update or create nilai
                $nilai = Nilai::updateOrCreate(
                    ['frs_detail_id' => $frsDetail->id],
                    [
                        'nilai_angka' => $nilaiAngka,
                        'nilai_huruf' => $gradeDetails['nilai_huruf'],
                        'status' => $gradeDetails['status'],
                    ]
                );

                Log::info("Successfully saved nilai for {$nrp} (Nilai ID: {$nilai->id})");

            } catch (\Exception $e) {
                $error = "Row {$this->processedRows}: Error saving nilai for {$nrp}: " . $e->getMessage();
                $this->errors[] = $error;
                Log::error($error);
            }
        }

        Log::info("Import completed. Processed: {$this->processedRows}, Errors: " . count($this->errors));
    }

    // public function rules(): array
    // {
    //     return [
    //         'nrp' => 'required|string',
    //         'nilai_angka' => 'required|integer|min:0|max:100',
    //     ];
    // }

    public function getErrors()
    {
        return $this->errors;
    }

    public function getProcessedRows()
    {
        return $this->processedRows;
    }

    private function calculateGradeDetails(int $nilaiAngka): array
    {
        $nilaiHuruf = 'E';
        $status = 'tidak lulus';

        if ($nilaiAngka >= 85) $nilaiHuruf = 'A';
        elseif ($nilaiAngka >= 80) $nilaiHuruf = 'AB';
        elseif ($nilaiAngka >= 75) $nilaiHuruf = 'B';
        elseif ($nilaiAngka >= 70) $nilaiHuruf = 'BC';
        elseif ($nilaiAngka >= 60) $nilaiHuruf = 'C';
        elseif ($nilaiAngka >= 50) $nilaiHuruf = 'D';

        if (in_array($nilaiHuruf, ['A', 'AB', 'B', 'BC', 'C'])) {
            $status = 'lulus';
        }

        return ['nilai_huruf' => $nilaiHuruf, 'status' => $status];
    }
}
