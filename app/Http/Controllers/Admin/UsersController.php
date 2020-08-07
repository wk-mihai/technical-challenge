<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\UsersRequest;
use App\Models\User;
use App\Repositories\RolesRepository;
use App\Repositories\UsersRepository;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class UsersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param UsersRepository $usersRepository
     * @return View
     */
    public function index(UsersRepository $usersRepository)
    {
        $pageTitle = __('Users');

        $records = $usersRepository->paginate(100, ['role']);

        return view('admin.users.index', compact('pageTitle', 'records'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param RolesRepository $rolesRepository
     * @return View
     */
    public function create(RolesRepository $rolesRepository)
    {
        $pageTitle = __('Create user');

        $roles = $rolesRepository->dropdown();

        return view('admin.users.create', compact('pageTitle', 'roles'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param UsersRequest $request
     * @param UsersRepository $usersRepository
     * @return RedirectResponse
     */
    public function store(UsersRequest $request, UsersRepository $usersRepository)
    {
        try {
            $usersRepository->store($request->all());

            return redirect()->route('admin.users.index')
                ->with('success', __('app.the_record_has_been_created'));
        } catch (ValidationException $e) {
            return redirect()->back()->withInput()->withErrors($e->getErrors());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param User $user
     * @return View
     */
    public function show(User $user)
    {
        $data = [
            'pageTitle' => $user->name,
            'record'    => $user,
            'roleName'  => isset($user->role) ? $user->role->name : '-',
        ];

        return view('admin.users.show', $data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param User $user
     * @param RolesRepository $rolesRepository
     * @return View
     */
    public function edit(User $user, RolesRepository $rolesRepository)
    {
        $data = [
            'pageTitle' => $user->name,
            'record'    => $user,
            'roles'     => $rolesRepository->dropdown()
        ];

        return view('admin.users.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UsersRequest $request
     * @param User $user
     * @param UsersRepository $usersRepository
     * @return RedirectResponse
     */
    public function update(UsersRequest $request, User $user, UsersRepository $usersRepository)
    {
        try {
            $usersRepository->update($user, $request->all());

            return redirect()->route('admin.users.index')
                ->with('success', __('app.the_record_has_been_updated'));
        } catch (ValidationException $e) {
            return redirect()->back()->withInput()->withErrors($e->getErrors());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param User $user
     * @return RedirectResponse
     */
    public function destroy(User $user)
    {
        if ($user->id === auth()->user()->id) {
            return redirect()->back()->withInput()
                ->with('warning', __('app.cant_delete_own_user'));
        }

        try {
            $user->delete();

            return redirect()->route('admin.users.index')
                ->with('success', __('app.record_has_deleted'));
        } catch (Exception $e) {
            return redirect()->back()->withInput()->withErrors($e->getMessage());
        }
    }
}
