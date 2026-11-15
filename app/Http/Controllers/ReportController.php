<?php

namespace App\Http\Controllers;

use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use RuntimeException;

class ReportController extends Controller
{
    public function members(): Response
    {
        $members = Auth::user()->members()->orderBy('member_number')->get();
        $pdf = new \FPDF;
        $pdf->AddPage();
        $pdf->SetFont('Arial', 'B', 16);
        $pdf->Cell(0, 10, $this->pdfText('Laporan Data Anggota'), 0, 1, 'C');
        $this->printDate($pdf);
        $this->tableHeader($pdf, [
            ['No. Anggota', 35],
            ['Nama', 47],
            ['Alamat', 76],
            ['Telepon', 32],
        ]);

        foreach ($members as $member) {
            $pdf->SetFont('Arial', '', 9);
            $pdf->Cell(35, 8, $this->pdfText($member->member_number), 1);
            $pdf->Cell(47, 8, $this->pdfText($member->name), 1);
            $pdf->Cell(76, 8, $this->pdfText($member->address), 1);
            $pdf->Cell(32, 8, $this->pdfText($member->phone), 1, 1);
        }

        $this->printTotal($pdf, $members->count());

        return $this->pdfResponse($pdf, 'laporan-anggota.pdf');
    }

    public function transactions(): Response
    {
        $transactions = Auth::user()->transactions()->with('member')->orderByDesc('transaction_date')->get();
        $pdf = new \FPDF('L');
        $pdf->AddPage();
        $pdf->SetFont('Arial', 'B', 16);
        $pdf->Cell(0, 10, $this->pdfText('Laporan Transaksi Simpan Pinjam'), 0, 1, 'C');
        $this->printDate($pdf);
        $this->tableHeader($pdf, [
            ['Tanggal', 28],
            ['Kategori', 29],
            ['Jenis', 31],
            ['No. Anggota', 34],
            ['Nama Anggota', 49],
            ['Jumlah (Rp)', 38],
            ['Keterangan', 64],
        ]);

        foreach ($transactions as $transaction) {
            $pdf->SetFont('Arial', '', 9);
            $pdf->Cell(28, 8, $this->pdfText($transaction->transaction_date->format('d/m/Y')), 1);
            $pdf->Cell(29, 8, $this->pdfText(ucfirst($transaction->category)), 1);
            $pdf->Cell(31, 8, $this->pdfText(ucfirst($transaction->type)), 1);
            $pdf->Cell(34, 8, $this->pdfText($transaction->member->member_number), 1);
            $pdf->Cell(49, 8, $this->pdfText($transaction->member->name), 1);
            $pdf->Cell(38, 8, $this->pdfText(number_format((float) $transaction->amount, 0, ',', '.')), 1);
            $pdf->Cell(64, 8, $this->pdfText($transaction->description ?? '-'), 1, 1);
        }

        $this->printTotal($pdf, $transactions->count());

        return $this->pdfResponse($pdf, 'laporan-transaksi.pdf');
    }

    private function printDate(\FPDF $pdf): void
    {
        $pdf->SetFont('Arial', '', 10);
        $pdf->Cell(0, 8, $this->pdfText('Tanggal cetak: '.now()->format('d/m/Y H:i')), 0, 1);
        $pdf->Ln(2);
    }

    private function tableHeader(\FPDF $pdf, array $columns): void
    {
        $pdf->SetFont('Arial', 'B', 9);
        foreach ($columns as [$label, $width]) {
            $pdf->Cell($width, 9, $this->pdfText($label), 1, 0, 'C');
        }
        $pdf->Ln();
    }

    private function printTotal(\FPDF $pdf, int $count): void
    {
        $pdf->Ln(4);
        $pdf->SetFont('Arial', 'B', 10);
        $pdf->Cell(0, 8, $this->pdfText("Jumlah data: {$count}"), 0, 1);
    }

    private function pdfText(string $text): string
    {
        $converted = iconv('UTF-8', 'windows-1252//TRANSLIT', $text);

        if ($converted === false) {
            throw new RuntimeException('Teks laporan tidak dapat dikonversi ke format PDF.');
        }

        return $converted;
    }

    private function pdfResponse(\FPDF $pdf, string $filename): Response
    {
        return response($pdf->Output('S'), 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="'.$filename.'"',
        ]);
    }
}
