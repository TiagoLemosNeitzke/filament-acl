<?php

namespace TiagoLemosNeitzke\FilamentAcl\Commands;

use Illuminate\Console\Command;
use TiagoLemosNeitzke\FilamentAcl\Models\Permission;
use TiagoLemosNeitzke\FilamentAcl\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class FilamentAclCommand extends Command
{
    protected $signature = 'acl:install';

    protected $description = 'Install acl roles and permissions';
    public function handle(): void
    {
        $path = app_path('Models/');
        $files = File::allFiles($path);
        $userId = $this->selectUser();

        if (!$userId) {
            $this->error('Usuário não encontrado!');
            $this->selectUser();
        };

        foreach ($files as $file) {
            $className = $file->getFilenameWithoutExtension();
            $policyName = $className.'Policy';

            $this->generatePermissionsAndRoles($userId, $className);

            if (!class_exists("App\\Policies\\{$policyName}")) {
                $stubPath = base_path('vendor/tiagolemosneitzke/filamentacl/stubs/policy.stub');
                $stubContent = file_get_contents($stubPath);
                $stubContent = str_replace('{{policyName}}', $policyName, $stubContent);
                $stubContent = str_replace('{{modelName}}', strtolower($className), $stubContent);

                $policyPath = app_path("Policies/{$policyName}.php");

                if (!File::exists(app_path('Policies'))) {
                    File::makeDirectory(app_path('Policies'), 0755, true);
                }

                file_put_contents($policyPath, $stubContent);

                $this->info("Policy {$policyName} created for model {$className}");
            } else {
                $this->info("Policy {$policyName} already exists.");
            }
        }
        $this->info("Papel 'admin' e permissões associadas diretamente ao usuário (ID: {$userId}).");
    }

    protected function selectUser(): ?string
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
            $result = $this->choice('Nenhum usuário encontrado. Deseja criar um?', ['Sim', 'Não'], 0);

            if ($result === 'Sim') {
                $name = $this->ask('Digite o nome do usuário:');
                $email = $this->ask('Digite o email do usuário:');
                $password = $this->secret('Digite a senha do usuário:');

                $user->create([
                    'name' => $name,
                    'email' => $email,
                    'password' => bcrypt($password),
                ]);

                $this->info("Usuário {$user->name} criado com sucesso!");
            } else {
                $this->error('Operação cancelada.');
                return null;
            }
        }

        $userId = $this->ask('Digite o ID do usuário que será o admin');

        $user = User::find($userId);
        if (!$user) {
            return null;
        }

        return $userId;
    }

    protected function generatePermissionsAndRoles($userId, string $className): void
    {
        $prefixes = config('acl.permission.prefixes');

        foreach ($prefixes as $index => $prefix) {
            Permission::query()->updateOrCreate([
                'name' => strtolower($className).'_'.$prefix,
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

    protected function assignPermissionsToRolesOrUsers(string $userId, $role, $permissions): void
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
}
