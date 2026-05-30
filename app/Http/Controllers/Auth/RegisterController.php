<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\StoreResidentRequest;
use App\Interfaces\ResidentRepositoryInterface;

class RegisterController extends Controller
{
    private ResidentRepositoryInterface $residentRepository;

    public function __construct(ResidentRepositoryInterface $residentRepository)
    {
        $this->residentRepository = $residentRepository;
    }


    public function index()
    {
        return view('pages.auth.register');
    }


    public function store(StoreResidentRequest $request)
    {
        $data = $request->validated();
        $data['avatar'] = 'asset/avatar/default.png';

        $this->residentRepository->createResident($data);

        return redirect()->route('login')->with('success', "Yeyy Pendaftaran Berhasil. Silahkan Login");
    }
}
