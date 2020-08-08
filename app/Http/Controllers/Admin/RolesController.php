<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\RolesRequest;
use App\Models\Role;
use App\Repositories\RolesRepository;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class RolesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param RolesRepository $rolesRepository
     * @return View
     */
    public function index(RolesRepository $rolesRepository)
    {
        $pageTitle = __('Roles');

        $records = $rolesRepository->paginate(100);

        return view('admin.roles.index', compact('pageTitle', 'records'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return View
     */
    public function create()
    {
        $pageTitle = __('Create role');

        return view('admin.roles.create', compact('pageTitle'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param RolesRequest $request
     * @param RolesRepository $rolesRepository
     * @return RedirectResponse
     */
    public function store(RolesRequest $request, RolesRepository $rolesRepository)
    {
        try {
            $rolesRepository->store($request->all());

            return redirect()->route('admin.roles.index')
                ->with('success', __('app.the_record_has_been_created'));
        } catch (ValidationException $e) {
            return redirect()->back()->withInput()->withErrors($e->getErrors());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param Role $role
     * @return View
     */
    public function show(Role $role)
    {
        $data = [
            'pageTitle' => $role->name,
            'record'    => $role
        ];

        return view('admin.roles.show', $data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Role $role
     * @return View
     */
    public function edit(Role $role)
    {
        $data = [
            'pageTitle' => $role->name,
            'record'    => $role
        ];

        return view('admin.roles.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param RolesRequest $request
     * @param Role $role
     * @param RolesRepository $rolesRepository
     * @return RedirectResponse
     */
    public function update(RolesRequest $request, Role $role, RolesRepository $rolesRepository)
    {
        try {
            $rolesRepository->update($role, $request->all());

            return redirect()->route('admin.roles.index')
                ->with('success', __('app.the_record_has_been_updated'));
        } catch (ValidationException $e) {
            return redirect()->back()->withInput()->withErrors($e->getErrors());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Role $role
     * @return RedirectResponse
     */
    public function destroy(Role $role)
    {
        if ($role->isAdmin()) {
            return redirect()->back()->withInput()
                ->with('warning', __('app.cant_delete_admin_role'));
        }

        if ($role->users->count()) {
            return redirect()->back()->withInput()
                ->with('warning', __('app.record_has_relations'));
        }

        try {
            $role->delete();

            return redirect()->route('admin.roles.index')
                ->with('success', __('app.record_has_deleted'));
        } catch (Exception $e) {
            return redirect()->back()->withInput()->withErrors($e->getMessage());
        }
    }
}
