<?php

namespace App\Services;

use App\Models\User;
use App\Repositories\UserRepository;

class UserService extends BaseService
{
    protected string $repositoryName = UserRepository::class;

    public function create(array $data): User
    {
        $user = $this->repository->create($data);

        $user->userDetail()->create($data);

        return $user;
    }

    public function update(User $user, array $data): User
    {
        $user->update($data);

        if ($user->userDetail) {
            $user->userDetail()->update($data);
        }

        return $user;
    }


    public function delete(User $user): void
    {
        $user->delete();
    }

}
