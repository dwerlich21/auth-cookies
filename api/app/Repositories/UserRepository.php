<?php

namespace App\Repositories;

use App\Exceptions\NotFoundException;
use App\Models\User;

class UserRepository extends BaseRepository
{
    /**
     * UserRepository constructor.
     */
    public function __construct(User $model)
    {
        parent::__construct($model);
    }

    /**
     * @throws NotFoundException
     */
    public function show(int $id): array
    {
        $user = $this->model->with(['address', 'permissions'])->find($id);

        if (! $user) {
            throw new NotFoundException('Usuário não encontrado');
        }

        return [
            'basicInfo' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'cpf' => $user->cpf,
                'type' => $user->type,
                'position' => $user->position,
                'phone' => $user->phone,
                'avatar' => $user->avatar,
                'active' => $user->active,
            ],
            'address' => $user->address ? [
                'zipCode' => $user->address->zip_code,
                'uf' => $user->address->uf,
                'city' => $user->address->city,
                'neighborhood' => $user->address->neighborhood,
                'address' => $user->address->address,
                'number' => $user->address->number,
                'complement' => $user->address->complement,
            ] : [],
        ];
    }
}
