<?php

namespace App\Services;

use App\Models\User;
use App\Repositories\UserRepository;
use Illuminate\Support\Arr;

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
        $userData = Arr::only($data, [
            'name',
            'email',
            'password',
        ]);

        if (empty($userData['password'])) {
            unset($userData['password']);
        } else {
            $userData['password'] = bcrypt($userData['password']);
        }

        $user->update($userData);

        $detailData = Arr::only($data, [
            'phone',
            'date_of_birth',
            'profile_image',
            'address_line_1',
            'address_line_2',
            'city',
            'state',
            'zip_code',
            'country',
        ]);

        $user->userDetail()->updateOrCreate(['user_id' => $user->id],$detailData);

        return $user;
    }

    public function getUser($id)
    {
        return $this->repository->with('userDetail')->find($id);
    }

    public function delete(User $user): void
    {
        $user->delete();
    }

    public function removeProfileImage($id)
    {
        $user = $this->getUser($id);

        if (!$user || !$user->userDetail) {
            return false;
        }

        $user->userDetail()->update(['profile_image' => '']);

        return true;
    }

}
