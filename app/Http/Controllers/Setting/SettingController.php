<?php

namespace App\Http\Controllers\Setting;

use App\Http\Controllers\Controller;
use App\Models\Auth\Module;
use App\Models\Auth\Permission;
use App\Models\Auth\PermissionRole;
use App\Models\Auth\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class SettingController extends Controller
{
    public function getTranslate()
    {
        $data = [
            'kh' => [
                'title' => 'ខ្មែរ',
                'flag' => 'seed/cambodia-flag.svg',
                'data' => [
                    "navigation.home" => "ទំព័រដើម",
                    "navigation.sample" => "គំរូ",
                    "navigation.user" => "អ្នកប្រើប្រាស់",
                    "navigation.setting" => "ការកំណត់",
                    "navigation.setting.role" => "តួនាទី",
                    "navigation.setting.permission" => "សិទ្ធិ",
                    "navigation.setting.config" => "រចនាសម្ព័ន្ធ",
                    "navigation.account" => "គណនី",
                    "navigation.test" => "សាកល្បង",

                    "app.title" => "ការកំណត់កម្មវិធី",
                    "app.description" => "កំណត់ការកំណត់មូលដ្ឋានរបស់កម្មវិធី រចនាបទ និងទិដ្ឋភាព",
                    "app.name.label" => "ឈ្មោះកម្មវិធី",
                    "app.name.placeholder" => "បញ្ចូលឈ្មោះកម្មវិធីរបស់អ្នក",
                    "app.name.hint" => "ឈ្មោះនេះនឹងបង្ហាញនៅក្នុងក្បាល និងចំណងជើងកម្មវិធី",
                    "general.title" => "ការកំណត់ទូទៅ",
                    "general.subtitle" => "កំណត់ព័ត៌មានមូលដ្ឋាននិងស្លាកសញ្ញា",
                    "layout.label" => "រចនាបទ",
                    "layout.placeholder" => "ជ្រើសរើសរចនាបទ",
                    "layout.hint" => "ជ្រើសរើសរបៀបបង្ហាញម៉ឺនុយ",
                    "skin.label" => "រូបរាង",
                    "skin.placeholder" => "ជ្រើសរើសរូបរាង",
                    "skin.hint" => "ជ្រើសរើសរូបរាងដែលអ្នកចូលចិត្ត",
                    "font.title" => "ពុម្ពអក្សរ",
                    "font.subtitle" => "កំណត់រចនាប័ទ្មពុម្ពអក្សរសម្រាប់ UI",
                    "font.label" => "ប្រភេទពុម្ពអក្សរ",
                    "font.hint" => "ជ្រើសរើសពុម្ពអក្សរដែលនឹងប្រើ",
                    "datetime.title" => "កាលបរិច្ឆេទ និងម៉ោង",
                    "datetime.subtitle" => "កំណត់ទ្រង់ទ្រាយកាលបរិច្ឆេទនិងម៉ោង",
                    "datetime.label" => "ទ្រង់ទ្រាយកាលបរិច្ឆេទ",
                    "reset" => "កំណត់ឡើងវិញ",
                    "save" => "រក្សាទុកការកំណត់",
                    "confirm.title" => "បញ្ជាក់",
                    "confirm.message" => "តើអ្នកប្រាកដថា?",
                    "updated.success" => "បានកែប្រែដោយជោគជ័យ",
                    "notification.no_comment" => "គ្មានមតិ",
                    "notification.file" => "ឯកសារ",
                ],
            ],
            'en' => [
                'title' => 'English',
                'flag' => 'seed/english-flag.svg',
                'data' => [
                    "navigation.home" => "Home",
                    "navigation.sample" => "Sample",
                    "navigation.user" => "User",
                    "navigation.setting" => "Setting",
                    "navigation.setting.role" => "Role",
                    "navigation.setting.permission" => "Permission",
                    "navigation.setting.config" => "Config",
                    "navigation.account" => "Account",
                    "navigation.test" => "Test",

                    "app.title" => "Application Configuration",
                    "app.description" => "Configure your application's basic settings, layout preferences, and visual appearance",
                    "app.name.label" => "Application Name",
                    "app.name.placeholder" => "Enter your application name",
                    "app.name.hint" => "This name will appear in the header and browser title",
                    "general.title" => "General Settings",
                    "general.subtitle" => "Configure basic application information and branding",
                    "layout.label" => "Layout Style",
                    "layout.placeholder" => "Select layout",
                    "layout.hint" => "Choose how your navigation will be displayed",
                    "skin.label" => "Visual Theme",
                    "skin.placeholder" => "Select theme",
                    "skin.hint" => "Choose your preferred visual style",
                    "font.title" => "Font",
                    "font.subtitle" => "Configure Font Style for UI",
                    "font.label" => "Font Family",
                    "font.hint" => "Choose which font will be used",
                    "datetime.title" => "Datetime",
                    "datetime.subtitle" => "Configure Datetime Format to Display",
                    "datetime.label" => "Datetime Format",
                    "reset" => "Reset",
                    "save" => "Save Configuration",
                    "confirm.title" => "Confirm",
                    "confirm.message" => "Are you sure?",
                    "updated.success" => "Updated Successfully",
                    "notification.no_comment" => "No comment",
                    "notification.file" => "File",
                ],
            ],
        ];

        return response()->json([
            'data' => $data
        ]);
    }

    public function getRoles(Request $request)
    {
        //:::::::::::::::::::::::::::::::::::::::::: GET FILTER
        $validated = $request->validate([
            'with'          => 'nullable|string|in:module,permission',
        ]);

        //:::::::::::::::::::::::::::::::::::::::::: VALIDATE FILTER
        $with   =  $validated['with'] ?? null;

        if ($with === 'module') {
            $data = Role::with([
                'permissions:id,name,module_id',
                'permissions.module:id,name'
            ])
                ->select(['id', 'name'])
                ->get();
            return response()->json([
                'data' => $data,
            ], 200);
        } else if ($with === 'permission') {
            $data = Role::with(['permissions:id,name'])
                ->select(['id', 'name'])
                ->get();
            return response()->json([
                'data' => $data,
            ], 200);
        } else {
            $data = Role::select(['id', 'name'])
                ->get();
            return response()->json([
                'data' => $data
            ], 200);
        }
    }

    public function getRoleWithPermission()
    {
        $roles = Role::with(['permissions:id'])->select(['id', 'name'])->get();

        $data = $roles->map(function ($role) {
            return [
                'id' => $role->id,
                'name' => $role->name,
                'permission_ids' => $role->permissions->pluck('id')->toArray(),
            ];
        });

        return response()->json(['data' => $data], 200);
    }

    public function createPermission(Request $request, Module $module)
    {
        try {
            //:::::::::::::::::::::::::::::::::::: VALIDATE
            $validated = $request->validate([
                'permissions' => 'required|array|min:1',
                'permissions.*.name' => 'required|string|min:1|max:100',
            ]);

            DB::beginTransaction();

            foreach ($validated['permissions'] as $perm) {
                Permission::create([
                    'name' => $perm['name'],
                    'module_id' => $module->id,
                ]);
            }

            DB::commit();

            return response()->json(['message' => 'Permissions created successfully']);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e);
            return response()->json([
                'error' => 'Failed to create permissions'
            ], 500);
        }
    }

    public function createModule(Request $request)
    {
        try {
            //:::::::::::::::::::::::::::::::::::: VALIDATE
            $validated = $request->validate([
                'name'              => 'required|string|min:1|max:150',
            ]);

            //:::::::::::::::::::::::::::::::::::: CREATE
            Module::create([
                'name'          => $validated['name'],
            ]);

            return response()->json(['message' => 'Created successfully']);
        } catch (ValidationException $e) {
            return response()->json(
                [
                    'success' => false,
                    'errors' => $e->errors()
                ],
                422
            );
        } catch (\Exception $e) {
            Log::error($e);
            return response()->json(
                [
                    'error' => 'Failed to create'
                ],
                500
            );
        }
    }

    public function listRole(Request $request)
    {
        //:::::::::::::::::::::::::::::::::::::::::: GET FILTER
        $validated = $request->validate([
            'per_page'          => 'integer|min:1|max:100',
            'keyword'           => 'nullable|string|max:255',
            'sort_direction'    => 'in:asc,desc',
            'is_active'         => 'nullable|in:0,1',
            'with'              => 'nullable|string|in:module,permission',
        ]);

        //:::::::::::::::::::::::::::::::::::::::::: VALIDATE FILTER
        $perPage        = $validated['per_page'] ?? 10;
        $keyword        = $validated['keyword'] ?? null;
        $sortDirection  = $validated['sort_direction'] ?? 'desc';
        $isActive       = $validated['is_active'] ?? null;
        $with           = $validated['with'] ?? null;

        //:::::::::::::::::::::::::::::::::::::::::: QUERY
        $rolesQuery = Role::query();

        //:::::::::::::::::::::::::::::::::::::::::: EAGER LOADING
        if ($with === 'module') {
            $rolesQuery->with([
                'permissions:id,name,module_id',
                'permissions.module:id,name'
            ])->select(['id', 'name', 'is_active', 'created_at']);
        } elseif ($with === 'permission') {
            $rolesQuery->with(['permissions:id,name'])
                ->select(['id', 'name', 'is_active', 'created_at']);
        } else {
            $rolesQuery->select(['id', 'name', 'is_active', 'created_at']);
        }

        //:::::::::::::::::::::::::::::::::::::::::: SEARCH
        if ($keyword) {
            $rolesQuery->where(function ($query) use ($keyword) {
                $query->where('name', 'like', "%{$keyword}%");
            });
        }

        //:::::::::::::::::::::::::::::::::::::::::: FILTER
        if (!is_null($isActive)) {
            $rolesQuery->where('is_active', $isActive);
        }

        //:::::::::::::::::::::::::::::::::::::::::: SORT
        $rolesQuery->orderBy('created_at', $sortDirection);

        //:::::::::::::::::::::::::::::::::::::::::: PAGINATION
        $roles = $rolesQuery->paginate($perPage);

        //:::::::::::::::::::::::::::::::::::::::::: RESPONSE
        return response()->json([
            'data' => $roles->items(),
            'meta' => [
                'current_page' => $roles->currentPage(),
                'per_page' => $roles->perPage(),
                'total' => $roles->total(),
                'last_page' => $roles->lastPage(),
            ],
        ], 200);
    }

    public function createRole(Request $request)
    {
        try {

            DB::beginTransaction();

            //:::::::::::::::::::::::::::::::::::: VALIDATE
            $validated = $request->validate([
                'name'              => 'required|string|min:1|max:100',
                'permission_id'     => 'required|array|min:1',
                'permission_id.*'   => 'integer|exists:permissions,id',
            ]);

            //:::::::::::::::::::::::::::::::::::: CREATE ROLE
            $newRole = Role::create([
                'name'          => $validated['name'],
            ]);

            //:::::::::::::::::::::::::::::::::::: CREATE ROLE PERMISSION
            foreach ($validated['permission_id'] as $permissionId) {
                PermissionRole::create([
                    'permission_id'     => $permissionId,
                    'role_id'           => $newRole->id,
                ]);
            }

            DB::commit();

            return response()->json(['message' => 'Created successfully']);
        } catch (ValidationException $e) {
            return response()->json(
                [
                    'success' => false,
                    'errors' => $e->errors()
                ],
                422
            );
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e);
            return response()->json(
                [
                    'error' => 'Failed to create' . $e
                ],
                500
            );
        }
    }

    public function deleteRole(Role $role)
    {
        try {

            DB::beginTransaction();
            $role->delete();

            DB::commit();

            return response()->json(['message' => 'Deleted successfully']);
        } catch (ValidationException $e) {
            return response()->json(
                [
                    'success' => false,
                    'errors' => $e->errors()
                ],
                422
            );
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e);
            return response()->json(
                [
                    'error' => 'Failed to create'
                ],
                500
            );
        }
    }

    public function toggleRole(Role $role)
    {
        try {

            //:::::::::::::::::::::::::::::::::::: update
            $role->update([
                'is_active'  => !$role->is_active
            ]);
            return response()->json(['message' => 'Updated successfully']);
        } catch (ValidationException $e) {
            return response()->json(
                [
                    'success' => false,
                    'errors' => $e->errors()
                ],
                422
            );
        } catch (\Exception $e) {
            Log::error($e);
            return response()->json(
                [
                    'error' => 'Failed to create'
                ],
                500
            );
        }
    }

    public function updateRole(Request $request, Role $role)
    {
        try {
            DB::beginTransaction();

            //:::::::::::::::::::::::::::::::::::: VALIDATE
            $validated = $request->validate([
                'name'              => 'required|string|min:1|max:100',
                'permission_id'     => 'required|array|min:1',
                'permission_id.*'   => 'integer|exists:permissions,id',
            ]);

            //:::::::::::::::::::::::::::::::::::: UPDATE ROLE NAME
            $role->update([
                'name' => $validated['name'],
            ]);

            //:::::::::::::::::::::::::::::::::::: SYNC PERMISSIONS
            // Remove old and attach new permissions
            $role->permissions()->sync($validated['permission_id']);

            DB::commit();

            return response()->json(['message' => 'Updated successfully']);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e);
            return response()->json([
                'error' => 'Failed to update'
            ], 500);
        }
    }


    public function getRoleById(Role $role)
    {
        $role->load('permissions');

        return response()->json([
            'data' => [
                'id' => $role->id,
                'name' => $role->name,
                'is_active' => $role->is_active,
                'created_at' => $role->created_at,
                'updated_at' => $role->updated_at,
                'permission_ids' => $role->permissions->pluck('id'),
            ]
        ], 200);
    }


    public function listPermission()
    {
        $permissions =  Permission::with(['module:id,name'])
            ->select(['id', 'name', 'is_active', 'module_id'])
            ->get();

        return response()->json([
            'data' => $permissions
        ], 200);
    }

    public function togglePermission(Permission $permission)
    {
        try {

            //:::::::::::::::::::::::::::::::::::: update
            $permission->update([
                'is_active'  => !$permission->is_active
            ]);
            return response()->json(['message' => 'Updated successfully']);
        } catch (ValidationException $e) {
            return response()->json(
                [
                    'success' => false,
                    'errors' => $e->errors()
                ],
                422
            );
        } catch (\Exception $e) {
            Log::error($e);
            return response()->json(
                [
                    'error' => 'Failed to create'
                ],
                500
            );
        }
    }

    public function deletePermission(Permission $permission)
    {
        try {

            //:::::::::::::::::::::::::::::::::::: update
            $permission->delete();
            return response()->json(['message' => 'Deleted successfully']);
        } catch (ValidationException $e) {
            return response()->json(
                [
                    'success' => false,
                    'errors' => $e->errors()
                ],
                422
            );
        } catch (\Exception $e) {
            Log::error($e);
            return response()->json(
                [
                    'error' => 'Failed to create'
                ],
                500
            );
        }
    }

    public function listModule()
    {
        $modules = Module::select(['id', 'name', 'is_active'])
            ->with(['permissions:id,name,is_active,module_id'])
            ->orderBy('id', 'desc') // ✅ Correct query builder usage
            ->get();

        return response()->json([
            'data' => $modules
        ], 200);
    }


    public function toggleModule(Module $module)
    {
        try {

            //:::::::::::::::::::::::::::::::::::: update
            $module->update([
                'is_active'  => !$module->is_active
            ]);
            return response()->json(['message' => 'Updated successfully']);
        } catch (ValidationException $e) {
            return response()->json(
                [
                    'success' => false,
                    'errors' => $e->errors()
                ],
                422
            );
        } catch (\Exception $e) {
            Log::error($e);
            return response()->json(
                [
                    'error' => 'Failed to create'
                ],
                500
            );
        }
    }

    public function deleteModule(Module $module)
    {
        try {
            //:::::::::::::::::::::::::::::::::::: delete all related permissions
            $module->permissions()->delete();

            //:::::::::::::::::::::::::::::::::::: delete the module
            $module->delete();

            return response()->json(['message' => 'Deleted successfully']);
        } catch (ValidationException $e) {
            return response()->json(
                [
                    'success' => false,
                    'errors' => $e->errors()
                ],
                422
            );
        } catch (\Exception $e) {
            Log::error($e);
            return response()->json(
                [
                    'error' => 'Failed to delete'
                ],
                500
            );
        }
    }


    public function setup(Request $request)
    {
        //:::::::::::::::::::::::::::::::::::: GET
        $selects = explode(',', $request->query('select', ''));

        $data = [];

        //:::::::::::::::::::::::::::::::::::: CONDITIONAL
        if (in_array('role', $selects)) {
            $data['roles'] = Role::select(['id', 'name'])->get();
        }

        //:::::::::::::::::::::::::::::::::::: CONDITIONAL
        if (in_array('module', $selects)) {
            $data['modules'] = Module::select(['id', 'name'])->get();
        }

        return response()->json([
            'data' => $data
        ], 200);
    }
}
