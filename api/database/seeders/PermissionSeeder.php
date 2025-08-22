<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\User;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * Executa o seeder de permissões
     *
     * @return void
     */
    public function run()
    {
        // Módulos principais com CRUD
        $this->createModuleWithCrud('schedule', 'Agenda');

        $this->createModuleWithCrud('contacts', 'Contatos');
        $this->createOrUpdatePermission('contacts.show', 'Visualizar Detalhes', $this->getParentId('contacts'));

        $this->createModuleWithCrud('attendances', 'Atendimentos');
        $this->createOrUpdatePermission('attendances.toggle-status', 'Ativar/Desativar Atendimento', $this->getParentId('attendances'));
        $this->createOrUpdatePermission('attendances.show', 'Visualizar Detalhes', $this->getParentId('attendances'));

        //        $this->createOrUpdatePermission('messages', 'Canal do Cidadão');
        //        $this->createOrUpdatePermission('messages.index', 'Visualizar Configurações', $this->getParentId('messages'));
        //        $this->createOrUpdatePermission('messages.save', 'Salvar Configurações', $this->getParentId('messages'));

        $this->createModuleWithCrud('projects', 'Projetos');

        $this->createOrUpdatePermission('documents-configs', 'Configurar Documentos');
        $this->createOrUpdatePermission('documents-configs.index', 'Visualizar Configurações', $this->getParentId('documents-configs'));
        $this->createOrUpdatePermission('documents-configs.save', 'Salvar Configurações', $this->getParentId('documents-configs'));

        $this->createModuleWithCrud('documents', 'Gerar Documentos');

        $this->createModuleWithCrud('document-uploads', 'Uploads de Documentos');
        $this->createOrUpdatePermission('document-uploads.toggle-status', 'Ativar/Desativar Upload', $this->getParentId('document-uploads'));

        $this->createModuleWithCrud('users', 'Usuários');

        $this->createOrUpdatePermission('cabinet-details', 'Detalhes do Gabinete');
        $this->createOrUpdatePermission('cabinet-details.index', 'Visualizar Detalhes', $this->getParentId('cabinet-details'));
        $this->createOrUpdatePermission('cabinet-details.elected', 'Salvar/Alterar Dados do Eleito', $this->getParentId('cabinet-details'));
        $this->createOrUpdatePermission('cabinet-details.responsible', 'Salvar/Alterar Dados do Responsável', $this->getParentId('cabinet-details'));
        $this->createOrUpdatePermission('cabinet-details.address', 'Salvar/Alterar Endereço do Gabinete', $this->getParentId('cabinet-details'));

        $this->createPermissionsToUsers();
    }

    /**
     * Cria ou atualiza uma permissão
     *
     * @param  string  $name  Nome da permissão
     * @param  string|null  $description  Descrição da permissão
     * @param  int|null  $parentId  ID da permissão pai
     * @return Permission
     */
    private function createOrUpdatePermission($name, $description = null, $parentId = null)
    {
        $slug = str_replace('.', '/', $name);

        $permission = Permission::updateOrCreate(
            ['slug' => $slug],
            [
                'name' => $name,
                'description' => $description,
                'parent_id' => $parentId,
            ]
        );

        return $permission;
    }

    /**
     * Obtém o ID de uma permissão pai pelo nome
     *
     * @param  string  $name  Nome da permissão pai
     * @return int|null ID da permissão
     */
    private function getParentId($name)
    {
        $parent = Permission::where('name', $name)->first();

        return $parent ? $parent->id : null;
    }

    /**
     * Cria um módulo pai e suas permissões CRUD
     *
     * @param  string  $module  Nome do módulo
     * @param  string  $moduleName  Nome de exibição do módulo
     * @return void
     */
    private function createModuleWithCrud($module, $moduleName)
    {
        // Criar módulo pai
        $parent = $this->createOrUpdatePermission($module, $moduleName);

        // Criar permissões CRUD
        $this->createCrudPermissions($module, $moduleName, $parent->id);
    }

    /**
     * Cria as permissões CRUD para um recurso específico
     *
     * @param  string  $resource  Nome do recurso
     * @param  string  $resourceName  Nome de exibição do recurso
     * @param  int  $parentId  ID da permissão pai
     * @return void
     */
    private function createCrudPermissions($resource, $resourceName, $parentId)
    {
        $actions = [
            'index' => "Listar {$resourceName}",
            'create' => "Criar {$resourceName}",
            'store' => "Salvar/Alterar {$resourceName}",
            'edit' => "Editar {$resourceName}",
            'destroy' => "Excluir {$resourceName}",
        ];

        foreach ($actions as $action => $description) {
            $this->createOrUpdatePermission("{$resource}.{$action}", $description, $parentId);
        }
    }

    /**
     * Cria ou atualiza uma permissão
     */
    private function createPermissionsToUsers(): void
    {
        $permissions = Permission::all()->pluck('id')->toArray();

        $users = User::all();

        foreach ($users as $user) {
            $user->permissions()->sync($permissions);
        }
    }
}
