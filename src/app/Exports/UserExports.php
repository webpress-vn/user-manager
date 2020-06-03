<?php

namespace VCComponent\Laravel\User\Exports;

use App\Entities\User;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class UserExports implements FromCollection, WithMapping, WithHeadings
{
    use Exportable;

    public function __construct(Collection $users)
    {
        $this->users = $users;

    }

    public function collection()
    {

        return $this->users;
    }

    public function map($users): array
    {
        return [
            $users->id,
            $users->email_verified_at,
            $users->email,
            $users->phone_number,
            $users->username,
            $users->first_name,
            $users->last_name,
            $users->birth,
            $users->gender,
            $users->created_at,

        ];
    }

    public function headings(): array
    {
        return [
            'id',
            'verified at',
            'email',
            'phone',
            'username',
            'first_name',
            'last_name',
            'birth',
            'gender',
            'created_at',
        ];

    }

}
