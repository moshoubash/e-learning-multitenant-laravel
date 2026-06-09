<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Tenant;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

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
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'domain' => 'required|string|max:255|unique:domains,domain',
            'data' => 'nullable|array',
        ]);

        $tenant = Tenant::create([
            'name' => $validated['name'],
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

        $validated = $request->validate([
            'name' => 'sometimes|string|max:255',
            'data' => 'nullable|array',
        ]);

        if (array_key_exists('data', $validated)) {
            $tenant->settings = $validated['data'];
        }
        if (array_key_exists('name', $validated)) {
            $tenant->name = $validated['name'];
        }
        $tenant->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Tenant updated successfully.',
            'data' => $tenant,
        ]);
    }

    /**
     * Delete a tenant.
     */
    public function destroy(string $id): JsonResponse
    {
        $tenant = Tenant::findOrFail($id);

        // Delete tenant (this also deletes associated domains and database)
        $tenant->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Tenant deleted successfully.',
        ]);
    }
}
