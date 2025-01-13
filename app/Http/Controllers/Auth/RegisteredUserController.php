<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\PasienRequest;
use App\Models\Pasien;
use App\Models\User;
use Error;
use Illuminate\Auth\Events\Registered;
use Illuminate\Database\UniqueConstraintViolationException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Validation\Rules;
use Inertia\Inertia;
use Inertia\Response;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): Response
    {
        return Inertia::render('Auth/Register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|lowercase|email|max:255|unique:' . User::class,
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        event(new Registered($user));

        Auth::login($user);

        return redirect('/');
    }


    public function pasienCreate(): Response
    {
        return Inertia::render('Auth/PasienRegister');
    }


    public function pasienStore(PasienRequest $req)
    {

        DB::beginTransaction();
        try {
  
    
    
    
            $user = User::create([
                'name' => $req['nama'],
                'email' => $req->email,
                'password' => Hash::make($req->password),
                'role' => 'pasien',
            ]);
            
            $result =  Pasien::create([
                'nama' => $req['nama'],
                'nik' => $req['nik'],
                'email' => $req->email,
                'jk' => $req['jk'],
                'tempat_lahir' => $req['tempat_lahir'],
                'tanggal_lahir' => $req['tanggal_lahir'],
                'alamat' => $req['alamat'],
                'kontak' => $req['kontak'],
                'user_id' => $user['id'],
            ]);
            DB::commit();
            event(new Registered($user));
            Auth::login($user);
            return redirect("/");
        } catch (\PDOException $th) {
            DB::rollBack();
            if($th->errorInfo[0] =="23505"){
                return Redirect::back()->withErrors(["message"=>"user ".$req->email . ' sudah digunakan']);
            }
            return Redirect::back()->withErrors($th->getMessage());
        }

        // $request->validate([
        //     'name' => 'required|string|max:255',
        //     'email' => 'required|string|lowercase|email|max:255|unique:' . User::class,
        //     'password' => ['required', 'confirmed', Rules\Password::defaults()],
        // ]);

        // $user = User::create([
        //     'name' => $request->name,
        //     'email' => $request->email,
        //     'password' => Hash::make($request->password),
        // ]);

        // event(new Registered($user));

        // Auth::login($user);

        // return redirect(route('login', absolute: false));
    }
}
