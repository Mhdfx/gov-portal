<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class SubmissionsExport implements FromCollection, WithHeadings, WithMapping, WithStyles, WithTitle
{
    protected $type;
    protected $user;
    protected $submissions;

    public function __construct(string $type, $user)
    {
        $this->type = $type;
        $this->user = $user;
        $this->submissions = $this->getSubmissions();
    }

    public function collection()
    {
        return $this->submissions;
    }

    public function headings(): array
    {
        return [
            'ID',
            'Type',
            'Submission Number',
            'Status',
            'User Email',
            'Created At',
            'Updated At'
        ];
    }

    public function map($submission): array
    {
        return [
            $submission->id,
            $submission->submission_type ?? 'unknown',
            $submission->submission_number ?? 'N/A',
            $submission->status,
            $submission->user->email ?? 'N/A',
            $submission->created_at->format('Y-m-d H:i:s'),
            $submission->updated_at->format('Y-m-d H:i:s'),
        ];
    }

    public function title(): string
    {
        return ucfirst($this->type) . ' Submissions';
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '4472C4']
                ],
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                ],
            ],
        ];
    }

    private function getSubmissions()
    {
        $submissions = collect();
        
        if ($this->type === 'all' || $this->type === 'investment') {
            $query = \App\Models\InvestmentSubmission::with('user');
            if ($this->user->role === 'user') {
                $query->where('user_id', $this->user->id);
            }
            $submissions = $submissions->merge(
                $query->get()->map(function($item) {
                    $item->submission_type = 'investment';
                    return $item;
                })
            );
        }
        
        if ($this->type === 'all' || $this->type === 'project-carrier') {
            $query = \App\Models\ProjectCarrierSubmission::with('user');
            if ($this->user->role === 'user') {
                $query->where('user_id', $this->user->id);
            }
            $submissions = $submissions->merge(
                $query->get()->map(function($item) {
                    $item->submission_type = 'project-carrier';
                    return $item;
                })
            );
        }
        
        if ($this->type === 'all' || $this->type === 'auto-entrepreneur') {
            $query = \App\Models\AutoEntrepreneurSubmission::with('user');
            if ($this->user->role === 'user') {
                $query->where('user_id', $this->user->id);
            }
            $submissions = $submissions->merge(
                $query->get()->map(function($item) {
                    $item->submission_type = 'auto-entrepreneur';
                    return $item;
                })
            );
        }
        
        return $submissions;
    }
}














