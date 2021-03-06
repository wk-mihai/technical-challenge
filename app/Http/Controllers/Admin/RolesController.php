<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\RolesRequest;
use App\Models\Role;
use App\Repositories\RolesRepository;
use App\Repositories\TypesRepository;
use Exception;
use Illuminate\Http\RedirectResponse;
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

        $records = $rolesRepository->paginate(100, ['types']);

        return view('admin.roles.index', compact('pageTitle', 'records'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param TypesRepository $typesRepository
     * @return View
     */
    public function create(TypesRepository $typesRepository)
    {
        $pageTitle = __('Create role');

        $types = $typesRepository->dropdown(false);

        return view('admin.roles.create', compact('pageTitle', 'types'));
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
        $rolesRepository->store($request->all());

        return redirect()->route('admin.roles.index')
            ->with('success', __('app.the_record_has_been_created'));
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
     * @param TypesRepository $typesRepository
     * @return View
     */
    public function edit(Role $role, TypesRepository $typesRepository)
    {
        $data = [
            'pageTitle' => $role->name,
            'types'     => $typesRepository->dropdown(false),
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
        $rolesRepository->update($role, $request->all());

        return redirect()->route('admin.roles.index')
            ->with('success', __('app.the_record_has_been_updated'));
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
            $role->roleTypes()->delete();
            $role->delete();

            return redirect()->route('admin.roles.index')
                ->with('success', __('app.record_has_deleted'));
        } catch (Exception $e) {
            return redirect()->back()->withInput()->withErrors($e->getMessage());
        }
    }
}
