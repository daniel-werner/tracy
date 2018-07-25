<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUser;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use DateTimeZone;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Symfony\Component\HttpFoundation\File\Exception\AccessDeniedException;

class UsersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = User::paginate(10);
        return view( 'users.index', compact( 'users' ) );
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $timezones = DateTimeZone::listIdentifiers(DateTimeZone::ALL);
        return view('users.create')->with(compact('timezones'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreUser $request)
    {
        $user = $this->save($request);

        return redirect( action('UsersController@edit', [ 'id' => $user->id ] ) );
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $timezones = DateTimeZone::listIdentifiers(DateTimeZone::ALL);
        $user = User::where(['id' => $id] )
            ->first();

        return view( 'users.edit' )->with(compact('user','timezones'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function profile()
    {
        $timezones = DateTimeZone::listIdentifiers(DateTimeZone::ALL);
        $user = User::where(['id' => Auth::id()] )
            ->first();

        return view( 'users.profile' )->with(compact('user','timezones'));
    }


    public function profile_update(StoreUser $request)
    {
        $this->save($request, Auth::id());
        return back();
    }

    protected function save(StoreUser $request, $id = null)
    {
        $request->validated();

        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'timezone' => $request->timezone
        ];

        if( !empty($request->role_id) ){
            $data['role_id'] = $request->role_id;
        }

        if( !empty( $request->password ) ){
            $data['password'] = Hash::make($request->password);
        }

        if(!empty($id)){
            $user = User::where(['id' => $id] )->first();

            if( $user->role_id < Auth::user()->role_id ){
                abort(403, __('Not allowed!') );
            }

            $user->fill($data);
            $user->save();
        }
        else{
            $user = User::create($data);
        }

        return $user;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(StoreUser $request, $id)
    {
        $this->save($request, $id);
        return back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        $user = User::findOrFail($id);
        if($user->delete()){
            $request->session()->flash('status', 'The user has been deleted!');
        }
        else{
            $request->session()->flash('status', 'Unable to delete user!');
        }

        return redirect(action('UsersController@index'));
    }
}
