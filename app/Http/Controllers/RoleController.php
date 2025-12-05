<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\DB;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    function __construct()
    {
        $this->middleware('permission:role-list|role-create|role-edit|role-delete', ['only' => ['index', 'store']]);
        $this->middleware('permission:role-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:role-edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:role-delete', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $roles = Role::where('is_delete', 0) // hanya yang aktif
            ->orderBy('id', 'DESC')
            ->paginate(10);

        return view('roles.index', compact('roles'))
            ->with('i', ($request->input('page', 1) - 1) * 10);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $permissions = Permission::all();

        // Kelompokkan dan urutkan sesuai config/permissions.php
        $grouped = $this->groupAndSortPermissions($permissions);

        return view('roles.create', [
            'groupedPermissions' => $grouped
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|unique:roles,name',
            'permission' => 'required',
        ]);

        $role = Role::create([
            'name' => $request->input('name'),
            'is_delete' => 0
        ]);
        $role->syncPermissions($request->input('permission'));

        return redirect()->route('roles.index')
            ->with('success', 'Role created successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $role = Role::find($id);
        $rolePermissions = Permission::join("role_has_permissions", "role_has_permissions.permission_id", "=", "permissions.id")
            ->where("role_has_permissions.role_id", $id)
            ->get();

        return view('roles.show', compact('role', 'rolePermissions'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $role = Role::find($id);
        $permission = Permission::get();
        $rolePermissions = DB::table("role_has_permissions")->where("role_has_permissions.role_id", $id)
            ->pluck('role_has_permissions.permission_id', 'role_has_permissions.permission_id')
            ->all();

        // Kelompokkan dan urutkan sesuai config/permissions.php
        $grouped = $this->groupAndSortPermissions($permission);

        return view('roles.edit', [
            'role' => $role,
            'permission' => $permission,
            'rolePermissions' => $rolePermissions,
            'groupedPermissions' => $grouped,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'name' => 'required',
            'permission' => 'required',
        ]);

        $role = Role::find($id);
        $role->name = $request->input('name');
        $role->save();
        $role->syncPermissions($request->input('permission'));

        return redirect()->route('roles.index')
            ->with('success', 'Role updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $role = Role::findOrFail($id);
            if ($role) {
                $role->is_delete = 1; // tandai sebagai dihapus
                $role->save();
                return redirect()->route('roles.index')->with('success', 'Role berhasil ditandai terhapus.');
            }
        } catch (\Exception $e) {
            return redirect()->route('roles.index')->with('error', 'Gagal menghapus role.');
        }
    }

    /**
     * Group and sort permissions by config order.
     *
     * @param \Illuminate\Support\Collection|array $permissions
     * @return \Illuminate\Support\Collection
     */
    private function groupAndSortPermissions($permissions)
    {
        $collection = $permissions instanceof \Illuminate\Support\Collection
            ? $permissions
            : collect($permissions);

        $groupsOrder = config('permissions.groups_order', []);
        $itemsOrder = config('permissions.items_order', []);

        $grouped = $collection->groupBy(function ($item) {
            return explode('-', $item->name)[0];
        });

        $sorted = collect();

        // Tambahkan grup sesuai urutan di config
        foreach ($groupsOrder as $group) {
            if ($grouped->has($group)) {
                $sortedGroup = $grouped->get($group)->sortBy(function ($perm) use ($itemsOrder, $group) {
                    $name = $perm->name;
                    $pos = strpos($name, '-');
                    $suffix = $pos !== false ? substr($name, $pos + 1) : $name;
                    $order = $itemsOrder[$group] ?? [];
                    $idx = array_search($suffix, $order, true);
                    return $idx === false ? 1000 + crc32($name) : $idx;
                })->values();
                $sorted->put($group, $sortedGroup);
            }
        }

        // Tambahkan sisa grup yang tidak ada di config (urut alfabet)
        $remaining = $grouped->keys()->diff($groupsOrder)->sort();
        foreach ($remaining as $group) {
            $sorted->put($group, $grouped->get($group)->sortBy('name')->values());
        }

        return $sorted;
    }
}
