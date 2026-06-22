<?php

namespace App\Livewire\Admin;

use App\Models\Tenant\Integration;
use Livewire\Component;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Masmerise\Toaster\Toaster;

#[Layout('layouts.admin')]
class Integrations extends Component
{
    public $showCreateModal = false;
    public $showEditModal = false;
    public $showDeleteModal = false;

    public $editingIntegration = null;
    public $deletingIntegration = null;

    // Create form fields
    public $createProvider = 'google';
    public $createClientId = '';
    public $createClientSecret = '';
    public $createRedirectUrl = '';
    public $createIsActive = false;

    // Edit form fields
    public $editClientId = '';
    public $editClientSecret = '';
    public $editRedirectUrl = '';
    public $editIsActive = false;

    public function openCreateModal()
    {
        $this->resetCreateForm();
        $this->showCreateModal = true;
    }

    public function openEditModal($id)
    {
        $this->editingIntegration = Integration::find($id);

        if (! $this->editingIntegration) {
            Toaster::error(__('messages.Integration not found.'));
            return;
        }

        $this->editClientId = $this->editingIntegration->client_id;
        $this->editClientSecret = $this->editingIntegration->client_secret;
        $this->editRedirectUrl = $this->editingIntegration->redirect_url;
        $this->editIsActive = $this->editingIntegration->is_active;
        $this->showEditModal = true;
    }

    public function openDeleteModal($id)
    {
        $this->deletingIntegration = Integration::find($id);
        $this->showDeleteModal = true;
    }

    public function closeModal()
    {
        $this->showCreateModal = false;
        $this->showEditModal = false;
        $this->showDeleteModal = false;
        $this->resetFormFields();
    }

    public function resetCreateForm()
    {
        $this->createProvider = 'google';
        $this->createClientId = '';
        $this->createClientSecret = '';
        $this->createRedirectUrl = '';
        $this->createIsActive = false;
    }

    public function resetFormFields()
    {
        $this->editingIntegration = null;
        $this->deletingIntegration = null;
        $this->editClientId = '';
        $this->editClientSecret = '';
        $this->editRedirectUrl = '';
        $this->editIsActive = false;
    }

    public function store()
    {
        $this->validate($this->createRules());

        $exists = Integration::whereRaw('LOWER(provider) = ?', [strtolower($this->createProvider)])->exists();
        if ($exists) {
            $this->addError('createProvider', __('messages.Provider already exists.'));
            return;
        }

        Integration::create([
            'provider' => $this->createProvider,
            'client_id' => $this->createClientId,
            'client_secret' => $this->createClientSecret,
            'redirect_url' => $this->createRedirectUrl,
            'is_active' => $this->createIsActive,
        ]);

        $this->closeModal();
        Toaster::success(__('messages.Integration created successfully!'));
    }

    public function update()
    {
        $this->validate($this->updateRules());

        if (! $this->editingIntegration) {
            Toaster::error(__('messages.Integration not found.'));
            return;
        }

        $this->editingIntegration->update([
            'client_id' => $this->editClientId,
            'client_secret' => $this->editClientSecret,
            'redirect_url' => $this->editRedirectUrl,
            'is_active' => $this->editIsActive,
        ]);

        $this->closeModal();
        Toaster::success(__('messages.Integration updated successfully!'));
    }

    public function delete()
    {
        if ($this->deletingIntegration) {
            $this->deletingIntegration->delete();
            $this->closeModal();
            Toaster::success(__('messages.Integration deleted successfully!'));
        }
    }

    public function toggleActive($id)
    {
        $integration = Integration::find($id);

        if ($integration) {
            $integration->update(['is_active' => ! $integration->is_active]);
            Toaster::success($integration->is_active
                ? __('messages.Integration activated successfully!')
                : __('messages.Integration deactivated successfully!'));
        }
    }

    #[Computed]
    public function availableProviders(): array
    {
        $existingProviders = Integration::pluck('provider')->map(fn($v) => strtolower($v))->toArray();

        return collect(['google', 'paypal'])
            ->reject(fn($provider) => in_array($provider, $existingProviders))
            ->values()
            ->toArray();
    }

    public function render()
    {
        $integrations = Integration::orderBy('provider')->paginate(10);

        return view('livewire.admin.integrations', [
            'integrations' => $integrations,
        ]);
    }

    protected function createRules(): array
    {
        return [
            'createProvider' => 'required|string|max:50|unique:integrations,provider',
            'createClientId' => 'required|string',
            'createClientSecret' => 'required|string',
            'createRedirectUrl' => 'nullable|url',
            'createIsActive' => 'boolean',
        ];
    }

    protected function updateRules(): array
    {
        return [
            'editClientId' => 'required|string',
            'editClientSecret' => 'required|string',
            'editRedirectUrl' => 'nullable|url',
            'editIsActive' => 'boolean',
        ];
    }
}
