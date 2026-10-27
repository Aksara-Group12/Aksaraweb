<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Style\Font;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class TrackingProdukExport implements FromArray, WithHeadings, WithStyles, ShouldAutoSize
{
	protected $data;

	public function __construct(array $data)
	{
		$this->data = $data;
	}

	public function array(): array
	{
		return $this->data;
	}

	public function headings(): array
	{
		return [
			'Tanggal Beli',
			'Pemasok',
			'Kontak Pemasok',
			'Kota/Kabupaten',
			'Gramasi',
			'Gramasi/Seri',
			'Nama Customer',
			'Kontak Customer',
			'Kota/Kabupaten',
			'Tanggal Terjual'
		];
	}

	public function styles($sheet)
	{
		$lastRow = count($this->data) + 1; // +1 untuk header

		// Style header (baris 1)
		$sheet->getStyle('A1:J1')->applyFromArray([
			'font' => [
				'bold' => true,
				'color' => ['rgb' => 'FFFFFF'],
			],
			'fill' => [
				'fillType' => Fill::FILL_SOLID,
				'startColor' => ['rgb' => '4CAF50'], // Hijau
			],
			'alignment' => [
				'horizontal' => Alignment::HORIZONTAL_CENTER,
				'vertical' => Alignment::VERTICAL_CENTER,
				'wrapText' => true,
			],
		]);

		// Set row height for header
		$sheet->getRowDimension(1)->setRowHeight(25);

		// Style data rows dan highlight rows belum terjual (G-J kosong)
		for ($row = 2; $row <= $lastRow; $row++) {
			// Cek apakah kolom G (index 6) atau H (index 7) kosong
			$cellG = $sheet->getCellByColumnAndRow(7, $row); // Column G = 7
			
			// Jika kolom G kosong, ini berarti belum terjual
			if (is_null($cellG->getValue()) || $cellG->getValue() === '') {
				// Highlight kolom G-J dengan background abu-abu muda
				$sheet->getStyle('G' . $row . ':J' . $row)->applyFromArray([
					'fill' => [
						'fillType' => Fill::FILL_SOLID,
						'startColor' => ['rgb' => 'E8E8E8'], // Abu-abu muda
					],
					'font' => [
						'color' => ['rgb' => 'CCCCCC'],
					],
				]);
			}

			// Center alignment untuk semua kolom
			$sheet->getStyle('A' . $row . ':J' . $row)->applyFromArray([
				'alignment' => [
					'horizontal' => Alignment::HORIZONTAL_LEFT,
					'vertical' => Alignment::VERTICAL_CENTER,
					'wrapText' => false,
				],
			]);

			// Alignment center untuk tanggal
			$sheet->getStyle('A' . $row)->applyFromArray([
				'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
			]);
			$sheet->getStyle('J' . $row)->applyFromArray([
				'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
			]);
		}

		return [];
	}
}
