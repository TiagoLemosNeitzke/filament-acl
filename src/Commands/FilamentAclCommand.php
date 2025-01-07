<?php

namespace TiagoLemosNeitzke\FilamentAcl\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use TiagoLemosNeitzke\FilamentAcl\Models\Permission;
use TiagoLemosNeitzke\FilamentAcl\Models\Role;

class FilamentAclCommand extends Command
{
    protected $signature = 'acl:install';

    protected $description = 'Install acl roles and permissions';

    public function handle(): void
    {
        $path = app_path('Models/');
        $files = File::allFiles($path);
        $userId = $this->selectUser();

        if (! $userId) {
            $this->error('User not found!');
            $this->selectUser();
        }

        foreach ($files as $file) {
            $className = $file->getFilenameWithoutExtension();
            $policyName = $className . 'Policy';

            $this->generatePermissionsAndRoles($userId, $className);

            if (! class_exists("App\\Policies\\{$policyName}")) {
                $stubPath = base_path('vendor/tiagolemosneitzke/filamentacl/stubs/policy.stub');
                $stubContent = file_get_contents($stubPath);
                $stubContent = str_replace('{{policyName}}', $policyName, $stubContent);
                $stubContent = str_replace('{{modelName}}', strtolower($className), $stubContent);

                $policyPath = app_path("Policies/{$policyName}.php");

                if (! File::exists(app_path('Policies'))) {
                    File::makeDirectory(app_path('Policies'), 0755, true);
                }

                file_put_contents($policyPath, $stubContent);

                $this->info("Policy {$policyName} created for model {$className}");
            } else {
                $this->info("Policy {$policyName} already exists.");
            }
        }
        $this->info("'admin' role and permissions associated directly with the user (ID: {$userId}).");
    }

    protected function selectUser(): ?int
    {
        $users = User::get();
        $admin = null;
        $users->each(function (User $user) use (&$admin) {
            if ($user->hasRole('admin')) {
                $admin = $user->id;
            }
            return null;
        });

        if ($admin) {
            return $admin;
        }

        return $this->createUser();
    }

    protected function generatePermissionsAndRoles($userId, string $className): void
    {
        $prefixes = config('acl.permission.prefixes');

        foreach ($prefixes as $index => $prefix) {
            Permission::query()->updateOrCreate([
                'name' => strtolower($className) . '_' . $prefix,
            ]);
        }

        foreach (config('acl.roles_prefixes') as $role) {
            $role = Role::updateOrCreate([
                'name' => $role,
            ]);
        }

        $permissions = Permission::all();

        $role->syncPermissions($permissions);

        $this->assignPermissionsToRolesOrUsers($userId, $role, $permissions);
    }

    protected function assignPermissionsToRolesOrUsers(int $userId, $role, $permissions): void
    {
        // Atribui o papel 'admin' ao usuário
        DB::table('model_has_roles')->updateOrInsert([
            'role_id' => $role->id,
            'model_type' => User::class,
            'model_id' => $userId,
        ]);

        // Associar permissões diretamente à tabela model_has_permissions
        foreach ($permissions as $permission) {
            DB::table('model_has_permissions')->updateOrInsert([
                'permission_id' => $permission->id,
                'model_type' => User::class,
                'model_id' => $userId,
            ]);
        }
    }

    private function createUser(): ?int
    {
        $this->table(
            ['ID', 'Name', 'Email'],
            $user = User::all(['id', 'name', 'email'])->map(fn($user) => [
                'ID' => $user->id,
                'Name' => $user->name,
                'Email' => $user->email,
            ])->toArray()
        );

        if (empty($user)) {
            $user = new User();
            $result = $this->choice('No users found. Do you want to create?', ['Sim', 'Não'], 0);

            if ($result === 'Sim') {
                $name = $this->ask('Enter the username:');
                $email = $this->ask('Enter the user email:');
                $password = $this->secret('Enter the user password:');

                $user = $user->create([
                    'name' => $name,
                    'email' => $email,
                    'password' => bcrypt($password),
                ]);

                $this->info("User {$user->name} created successfully!");
            } else {
                $this->error('Operation canceled.');
                return 0;
            }

            return $user->id;
        }

        $userId = $this->ask('Enter the ID of the user who will be the admin');

        $user = User::find($userId);
        if (!$user) {
            return null;
        }

        return (int) $userId;
    }
}
