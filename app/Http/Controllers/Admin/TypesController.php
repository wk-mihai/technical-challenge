<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\TypesRequest;
use App\Models\Type;
use App\Repositories\TypesRepository;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class TypesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param TypesRepository $typesRepository
     * @return View
     */
    public function index(TypesRepository $typesRepository)
    {
        $pageTitle = __('Types');

        $records = $typesRepository->paginate(100);

        return view('admin.types.index', compact('pageTitle', 'records'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return View
     */
    public function create()
    {
        $pageTitle = __('Create type');

        return view('admin.types.create', compact('pageTitle'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param TypesRequest $request
     * @param TypesRepository $typesRepository
     * @return RedirectResponse
     */
    public function store(TypesRequest $request, TypesRepository $typesRepository)
    {
        $typesRepository->store($request->all());

        return redirect()->route('admin.types.index')
            ->with('success', __('app.the_record_has_been_created'));
    }

    /**
     * Display the specified resource.
     *
     * @param Type $type
     * @return View
     */
    public function show(Type $type)
    {
        $data = [
            'pageTitle' => $type->name,
            'record'    => $type
        ];

        return view('admin.types.show', $data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Type $type
     * @return View
     */
    public function edit(Type $type)
    {
        $data = [
            'pageTitle' => $type->name,
            'record'    => $type
        ];

        return view('admin.types.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param TypesRequest $request
     * @param Type $type
     * @param TypesRepository $typesRepository
     * @return RedirectResponse
     */
    public function update(TypesRequest $request, Type $type, TypesRepository $typesRepository)
    {
        $typesRepository->update($type, $request->all());

        return redirect()->route('admin.types.index')
            ->with('success', __('app.the_record_has_been_updated'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Type $type
     * @return RedirectResponse
     */
    public function destroy(Type $type)
    {
        if ($type->trainings->count()) {
            return redirect()->back()->withInput()
                ->with('warning', __('app.record_has_relations'));
        }

        try {
            $type->roleTypes()->delete();
            $type->delete();

            return redirect()->route('admin.types.index')
                ->with('success', __('app.record_has_deleted'));
        } catch (Exception $e) {
            return redirect()->back()->withInput()->withErrors($e->getMessage());
        }
    }
}
