<?php

namespace common\models;

use yii\base\Model;
use common\models\AbsensiLog;

class SalaryCalculator extends Model
{
    public $hourlyRate;

    public function PengecekanHari($tanggal)
    {
        list($tahun, $bulan, $hari) = explode('-', $tanggal);

        // Tentukan nama hari
        $namaHari = date('l', strtotime($tanggal)); // Format huruf lengkap (misal: Monday, Tuesday)

        return [
            'tanggal' => $tanggal,
            'hari' => $namaHari
        ];
    }
    
    public function calculateMonthlySalaryPerEmployee($createdBy)
    {
        // Misal ambil data absensi berdasarkan 'created_by' dari tabel absensi_log
        $absensiLogs = AbsensiLog::find()->where(['created_by' => $createdBy])->all();

        // Inisialisasi array untuk menyimpan hasil perhitungan per karyawan
        $result = [];

        foreach ($absensiLogs as $absensiLog) {
            $tanggal = $absensiLog->tanggal_absensi;

            // Panggil fungsi PengecekanHari untuk mendapatkan hasil pengecekan hari
            $hasilPengecekan = $this->PengecekanHari($tanggal);

            // Hitung total jam kerja berdasarkan hari
            $totalJamKerja = 0;
            if ($hasilPengecekan['hari'] != 'Saturday') {
                $totalJamKerja = 8; // 8 jam untuk Senin - Jumat
            } else {
                $totalJamKerja = 6; // 6 jam untuk Sabtu
            }

            // Hitung gaji bulanan
            $totalSalary = $totalJamKerja * $this->hourlyRate;

            // Simpan hasil perhitungan untuk setiap tanggal
            $result[] = [
                'tanggal' => $tanggal,
                'hari' => $hasilPengecekan['hari'],
                'totalJamKerja' => $totalJamKerja,
                'totalSalary' => $totalSalary
            ];
        }

        return $result;
    }
    
    public function calculateMonthlySalary()
    {
        // Misal ambil tanggal dari tabel absensi_log
        $absensiLogs = AbsensiLog::find()->all();

        // Inisialisasi array untuk menyimpan total gaji per karyawan
        $totalSalaryPerEmployee = [];

        foreach ($absensiLogs as $absensiLog) {
            $createdBy = $absensiLog->created_by;

            // Hitung gaji bulanan per karyawan
            $monthlySalaryPerEmployee = $this->calculateMonthlySalaryPerEmployee($createdBy);

            // Jumlahkan total gaji per karyawan
            $totalSalary = 0;
            foreach ($monthlySalaryPerEmployee as $salaryData) {
                $totalSalary += $salaryData['totalSalary'];
            }

            // Simpan total gaji per karyawan
            if (!isset($totalSalaryPerEmployee[$createdBy])) {
                $totalSalaryPerEmployee[$createdBy] = 0;
            }
            $totalSalaryPerEmployee[$createdBy] += $totalSalary;
        }

        return $totalSalaryPerEmployee;
    }
}
