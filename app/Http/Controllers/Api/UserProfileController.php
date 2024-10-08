<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\CertificateResource;
use App\Http\Resources\LicenceResource;
use App\Http\Resources\UserResource;
use App\Models\Certificate;
use App\Models\License;
use App\Models\User;
use App\Traits\AppResponse;
use Carbon\Carbon;
use Illuminate\Http\Request;

class UserProfileController extends Controller
{
    use AppResponse;

    public function findTalentsProfiles(Request $request)
    {
        $data = $request->all();
        $users = User::query()->blockedUsers()->where('user_type_id', 1);
        if (isset($data['name']) && !empty($data['name'])) {
            $users->where('name', 'LIKE', '%' . $data['name'] . '%');
        }
        if (isset($data['country']) && !empty($data['country'])) {
            $users->whereHas('talent', function ($q) use ($data) {
                $q->where('country_id', $data['country']);
            });
        }
        if (isset($data['gender']) && !empty($data['gender'])) {
            $users->whereHas('talent', function ($q) use ($data) {
                $q->where('gender', $data['gender']);
            });
        }
        if (isset($data['position']) && !empty($data['position'])) {
            $users->whereHas('talent', function ($q) use ($data) {
                $q->where('parent_position_id', $data['position']);
            });
        }
        if (isset($data['preferred_foot']) && !empty($data['preferred_foot'])) {
            $users->whereHas('talent', function ($q) use ($data) {
                $q->where('preferred_foot', $data['preferred_foot']);
            });
        }
        return $this->success(UserResource::collection($users->get()));

    }

    public function findCoachesProfiles(Request $request)
    {
        $data = $request->all();
        $users = User::query()->blockedUsers()->where('user_type_id', 2);
        if (isset($data['name']) && !empty($data['name'])) {
            $users->where('name', 'LIKE', '%' . $data['name'] . '%');
        }
        if (isset($data['country']) && !empty($data['country'])) {
            $users->whereHas('coach', function ($q) use ($data) {
                $q->where('country_id', $data['country']);
            });
        }
        if (isset($data['gender']) && !empty($data['gender'])) {
            $users->whereHas('coach', function ($q) use ($data) {
                $q->where('gender', $data['gender']);
            });
        }
        if (isset($data['experience_from']) && !empty($data['experience_from'])) {
            $users->whereHas('coach', function ($q) use ($data) {
                $q->where('years_of_experience', '>=', $data['experience_from']);
            });
        }
        if (isset($data['experience_to']) && !empty($data['experience_to'])) {
            $users->whereHas('coach', function ($q) use ($data) {
                $q->where('years_of_experience', '<=', $data['experience_to']);
            });
        }
        return $this->success(UserResource::collection($users->get()));
    }

    public function findClubsProfiles(Request $request)
    {
        $data = $request->all();
        $users = User::query()->blockedUsers()->where('user_type_id', 3);

        if (isset($data['name']) && !empty($data['name'])) {
            $users->whereHas('club', function ($q) use ($data) {
                $q->where('name', 'LIKE', '%' . $data['name'] . '%');
            });

        }
        if (isset($data['country']) && !empty($data['country'])) {
            $users->whereHas('club', function ($q) use ($data) {
                $q->where('country_id', $data['country']);
            });
        }
        if (isset($data['year_founded']) && !empty($data['year_founded'])) {
            $users->whereHas('club', function ($q) use ($data) {
                $q->where('year_founded', $data['year_founded']);
            });
        }

        return $this->success(UserResource::collection($users->get()));
    }

    public function findScoutsProfiles(Request $request)
    {
        $data = $request->all();
        $users = User::query()->blockedUsers()->where('user_type_id', 4);

        if (isset($data['name']) && !empty($data['name'])) {
            $users->where('name', 'LIKE', '%' . $data['name'] . '%');
        }
        if (isset($data['country']) && !empty($data['country'])) {
            $users->whereHas('scout', function ($q) use ($data) {
                $q->where('country_id', $data['country']);
            });
        }
        if (isset($data['gender']) && !empty($data['gender'])) {
            $users->whereHas('scout', function ($q) use ($data) {
                $q->where('gender', $data['gender']);
            });
        }
        if (isset($data['experience_from']) && !empty($data['experience_from'])) {
            $users->whereHas('scout', function ($q) use ($data) {
                $q->where('years_of_experience', '>=', $data['experience_from']);
            });
        }
        if (isset($data['experience_to']) && !empty($data['experience_to'])) {
            $users->whereHas('scout', function ($q) use ($data) {
                $q->where('years_of_experience', '<=', $data['experience_to']);
            });
        }
        return $this->success(UserResource::collection($users->get()));
    }


    public function createCertificate(Request $request)
    {
        $data = $request->validate([
            'name' => 'required',
            'issued_by' => 'required',
            'issued_date' => 'required',
            'credential_id' => 'required',
        ]);
        return $this->success(CertificateResource::make(auth()->user()->profile->certificates()->create($data)), 'certificate created successfully !');
    }

    public function editCertificate($certificate_id, Request $request)
    {
        $data = $request->validate([
            'name' => 'required',
            'issued_by' => 'required',
            'issued_date' => 'required|date:Y-m-d',
            'credential_id' => 'required',
        ]);
        $data['issued_date'] = Carbon::make($data['issued_date']);
        Certificate::query()->where('id', $certificate_id)->update($data);
        return $this->success(true, 'certificate updated successfully !');
    }

    public function deleteCertificate(Certificate $certificate)
    {
        $certificate->delete();
        return $this->success(true, 'certificate deleted');
    }

    public function getProfileCertificate(User $user)
    {
        return $this->success(CertificateResource::collection($user->profile->certificates));
    }

    public function createLicence(Request $request)
    {
        $data = $request->validate([
            'name' => 'required',
            'issued_by' => 'required',
            'issued_date' => 'required|date:Y-m-d',
            'expiration_date' => 'required|date:Y-m-d',
            'licence_id' => 'required',
        ]);
        $data['user_id'] = auth()->user()->id;
        $license = License::query()->create($data);
        return $this->success(LicenceResource::make($license), 'licence created successfully !');
    }

    public function editLicence(Request $request, $licence_id)
    {
        $data = $request->validate([
            'name' => 'required',
            'issued_by' => 'required',
            'issued_date' => 'required|date:Y-m-d',
            'expiration_date' => 'required|date:Y-m-d',
            'licence_id' => 'required',
        ]);
        $data['issued_date'] = Carbon::make($data['issued_date']);
        $data['expiration_date'] = Carbon::make($data['expiration_date']);
        License::query()->where('id', $licence_id)->update($data);
        return $this->success(true, 'licence updated successfully !');
    }

    public function getUserLicence(User $user)
    {
        return $this->success(LicenceResource::collection($user->licences));
    }

    public function deleteLicence($licence_id)
    {
        License::query()->where('id', $licence_id)->delete();
        return $this->success(true, 'licence deleted successfully !');
    }

}
