<?php

namespace App\Livewire\Admin;

use App\Models\Tenant;
use Livewire\Component;
use Livewire\WithFileUploads;
use Masmerise\Toaster\Toaster;
use Illuminate\Support\Str;

class TenantSettings extends Component
{
    use WithFileUploads;

    // Tenant Information
    public $tenantId;
    public $name = '';
    public $slug = '';
    public $domain = '';
    public $plan = 'free';
    public $is_active = true;

    // Settings
    public $settings = [];
    public $branding_name = '';
    public $branding_logo = null;
    public $branding_primary_color = '#3B82F6';

    // Modal states
    public $showDeleteModal = false;
    public $deletingTenant = null;

    protected $listeners = [
        'closeModal' => 'closeModal',
    ];

    public function mount()
    {
        $this->loadTenantData();
    }

    public function loadTenantData()
    {
        $tenant = tenant();

        if ($tenant) {
            $this->tenantId = $tenant->id;
            $this->name = $tenant->name;
            $this->slug = $tenant->slug;
            $this->domain = $tenant->domain ?? '';
            $this->plan = $tenant->plan;
            $this->is_active = $tenant->is_active;

            // Load settings JSON
            $settings = $tenant->settings ?? [];
            $this->settings = is_array($settings) ? $settings : json_decode($settings, true) ?? [];
            $this->branding_name = $settings['branding']['name'] ?? '';
            $this->branding_primary_color = $settings['branding']['primary_color'] ?? '#3B82F6';
        }
    }

    public function updatedName($value)
    {
        // Auto-generate slug from name if slug is empty or matches old name
        if (empty($this->slug) || $this->slug === Str::slug($value)) {
            $this->slug = Str::slug($value);
        }
    }

    public function save()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:tenants,slug,' . $this->tenantId,
            'domain' => 'nullable|string|max:255',
            'plan' => 'required|in:free,pro,enterprise',
        ]);

        $tenant = tenant();

        if (!$tenant) {
            Toaster::error('Tenant not found!');
            return;
        }

        // Update tenant
        $tenant->name = $this->name;
        $tenant->slug = $this->slug;
        $tenant->domain = $this->domain ?: null;
        $tenant->plan = $this->plan;
        $tenant->is_active = $this->is_active;

        // Build settings JSON
        $settings = [
            'branding' => [
                'name' => $this->branding_name,
                'primary_color' => $this->branding_primary_color,
            ],
        ];
        $tenant->settings = $settings;
        $tenant->save();

        Toaster::success('Tenant settings updated successfully!');
    }

    public function saveBranding()
    {
        $this->validate([
            'branding_name' => 'nullable|string|max:255',
            'branding_primary_color' => 'nullable|string|max:7',
        ]);

        $tenant = tenant();

        if (!$tenant) {
            Toaster::error('Tenant not found!');
            return;
        }

        $settings = $tenant->settings ?? [];
        $settings['branding'] = [
            'name' => $this->branding_name,
            'primary_color' => $this->branding_primary_color,
        ];

        $tenant->settings = $settings;
        $tenant->save();

        Toaster::success('Branding settings saved successfully!');
    }

    public function openDeleteModal()
    {
        $this->deletingTenant = tenant();
        $this->showDeleteModal = true;
    }

    public function closeModal()
    {
        $this->showDeleteModal = false;
        $this->deletingTenant = null;
    }

    public function deactivateTenant()
    {
        $tenant = tenant();

        if (!$tenant) {
            Toaster::error('Tenant not found!');
            return;
        }

        $tenant->is_active = false;
        $tenant->save();

        $this->closeModal();
        Toaster::success('Tenant deactivated successfully!');
    }

    public function activateTenant()
    {
        $tenant = tenant();

        if (!$tenant) {
            Toaster::error('Tenant not found!');
            return;
        }

        $tenant->is_active = true;
        $tenant->save();

        Toaster::success('Tenant activated successfully!');
    }

    public function getPlanBadgeClass($plan)
    {
        return match ($plan) {
            'enterprise' => 'bg-purple-100 text-purple-800',
            'pro' => 'bg-blue-100 text-blue-800',
            'free' => 'bg-gray-100 text-gray-800',
            default => 'bg-gray-100 text-gray-800',
        };
    }

    public function render()
    {
        $tenant = tenant();

        return view('livewire.admin.tenant-settings', [
            'tenant' => $tenant,
            'planOptions' => [
                ['value' => 'free', 'label' => 'Free', 'description' => 'Basic features for small teams'],
                ['value' => 'pro', 'label' => 'Pro', 'description' => 'Advanced features for growing organizations'],
                ['value' => 'enterprise', 'label' => 'Enterprise', 'description' => 'Full features with priority support'],
            ],
        ]);
    }
}