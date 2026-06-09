<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Tenant;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class TenantController extends Controller
{
    /**
     * List all tenants.
     */
    public function index(): JsonResponse
    {
        $tenants = Tenant::with('domains')->get();

        return response()->json([
            'status' => 'success',
            'data' => $tenants,
        ]);
    }

    /**
     * Create a new tenant.
     */
    public function store(Request $request): JsonResponse
    {
        $allowedPlans = ['free', 'pro', 'enterprise'];

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:tenants,slug',
            'domain' => 'required|string|max:255|unique:domains,domain',
            'plan' => ['nullable', Rule::in($allowedPlans)],
            'is_active' => 'nullable|boolean',
            'data' => 'nullable|array',
        ]);

        $tenant = Tenant::create([
            'name' => $validated['name'],
            'slug' => $validated['slug'] ?? \Illuminate\Support\Str::slug($validated['name']),
            'plan' => $validated['plan'] ?? 'free',
            'is_active' => $validated['is_active'] ?? true,
            'settings' => $validated['data'] ?? [],
        ]);

        $tenant->domains()->create([
            'domain' => $validated['domain'],
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Tenant created successfully.',
            'data' => $tenant->load('domains'),
        ], 201);
    }

    /**
     * Get a specific tenant.
     */
    public function show(string $id): JsonResponse
    {
        $tenant = Tenant::with('domains')->findOrFail($id);

        return response()->json([
            'status' => 'success',
            'data' => $tenant,
        ]);
    }

    /**
     * Update a tenant.
     */
    public function update(Request $request, string $id): JsonResponse
    {
        $tenant = Tenant::findOrFail($id);
        $allowedPlans = ['free', 'pro', 'enterprise'];

        $validated = $request->validate([
            'name' => 'sometimes|string|max:255',
            'slug' => 'sometimes|string|max:255|unique:tenants,slug,'.$tenant->id,
            'plan' => ['sometimes', Rule::in($allowedPlans)],
            'is_active' => 'sometimes|boolean',
            'data' => 'nullable|array',
        ]);

        if (array_key_exists('name', $validated)) {
            $tenant->name = $validated['name'];
        }
        if (array_key_exists('slug', $validated)) {
            $tenant->slug = $validated['slug'];
        }
        if (array_key_exists('plan', $validated)) {
            $tenant->plan = $validated['plan'];
        }
        if (array_key_exists('is_active', $validated)) {
            $tenant->is_active = (bool) $validated['is_active'];
        }
        if (array_key_exists('data', $validated)) {
            $tenant->settings = $validated['data'];
        }
        $tenant->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Tenant updated successfully.',
            'data' => $tenant,
        ]);
    }

    /**
     * Activate a tenant (set is_active = true).
     */
    public function activate(string $id): JsonResponse
    {
        $tenant = Tenant::findOrFail($id);
        $tenant->is_active = true;
        $tenant->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Tenant activated.',
            'data' => $tenant,
        ]);
    }

    /**
     * Deactivate a tenant (set is_active = false).
     */
    public function deactivate(string $id): JsonResponse
    {
        $tenant = Tenant::findOrFail($id);
        $tenant->is_active = false;
        $tenant->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Tenant deactivated.',
            'data' => $tenant,
        ]);
    }

    /**
     * Delete a tenant.
     */
    public function destroy(string $id): JsonResponse
    {
        $tenant = Tenant::findOrFail($id);

        $tenant->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Tenant deleted successfully.',
        ]);
    }
}
