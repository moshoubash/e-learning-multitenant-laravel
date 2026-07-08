<?php

namespace App\Imports;

use App\Models\Tenant\User;
use App\Services\Admin\UserImportService;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Validators\Failure;

class UsersImport implements ToModel, WithHeadingRow, SkipsOnError, SkipsOnFailure, WithValidation
{
    protected UserImportService $importService;

    public int $imported = 0;
    public int $skipped = 0;
    public array $errors = [];
    public array $passwords = [];

    public function __construct()
    {
        $this->importService = app(UserImportService::class);
    }

    public function model(array $row)
    {
        if (! $this->importService->hasCapacity()) {
            $this->errors[] = __('messages.Tenant has reached its maximum user limit.');
            return null;
        }

        $role = strtolower(trim($row['role']));

        if (! in_array($role, ['admin', 'instructor', 'student'])) {
            $this->skipped++;
            $this->errors[] = __('messages.Invalid role') . ": {$row['role']}";
            return null;
        }

        if (User::where('email', $row['email'])->exists()) {
            $this->skipped++;
            $this->errors[] = __('messages.Email already exists') . ": {$row['email']}";
            return null;
        }

        $this->imported++;

        $plainPassword = Str::password(16);

        $user = User::create([
            'name' => $row['name'],
            'email' => $row['email'],
            'password' => Hash::make($plainPassword),
        ]);

        $this->passwords[$row['email']] = $plainPassword;

        $user->assignRole($role);

        return $user;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'role' => 'required|string',
        ];
    }

    public function onFailure(Failure ...$failures): void
    {
        foreach ($failures as $failure) {
            $this->errors[] = "Row {$failure->row()}: " . implode(', ', $failure->errors());
        }
    }

    public function onError(\Throwable $e): void
    {
        $this->errors[] = $e->getMessage();
    }
}
