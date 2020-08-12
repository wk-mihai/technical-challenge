<?php

namespace App\Http\Controllers\Admin;

use App\Exceptions\ValidationFilesException;
use App\Http\Controllers\Controller;
use App\Http\Requests\TrainingsRequest;
use App\Models\Training;
use App\Repositories\TrainingsRepository;
use App\Repositories\TypesRepository;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class TrainingsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param TrainingsRepository $trainingsRepository
     * @return View
     */
    public function index(TrainingsRepository $trainingsRepository)
    {
        $pageTitle = __('Trainings');

        $records = $trainingsRepository->paginate(100, ['type']);

        return view('admin.trainings.index', compact('pageTitle', 'records'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param TypesRepository $typesRepository
     * @return View
     */
    public function create(TypesRepository $typesRepository)
    {
        $pageTitle = __('Create training');

        $types = $typesRepository->dropdown();

        return view('admin.trainings.create', compact('pageTitle', 'types'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param TrainingsRequest $request
     * @param TrainingsRepository $trainingsRepository
     * @return RedirectResponse
     */
    public function store(TrainingsRequest $request, TrainingsRepository $trainingsRepository)
    {
        try {
            $trainingsRepository->store($request->all());

            return redirect()->route('admin.trainings.index')
                ->with('success', __('app.the_record_has_been_created'));
        } catch (Exception $e) {
            return redirect()->back()->withErrors($e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param Training $training
     * @param TrainingsRepository $trainingsRepository
     * @return View
     */
    public function show(Training $training, TrainingsRepository $trainingsRepository)
    {
        $images = $trainingsRepository->getImageFiles($training);
        $videos = $trainingsRepository->getVideoFiles($training);

        $data = array_merge(
            [
                'pageTitle' => $training->name,
                'record'    => $training,
                'typeName'  => isset($training->type) ? $training->type->name : '-',

            ],
            compact('images', 'videos')
        );

        return view('admin.trainings.show', $data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Training $training
     * @param TrainingsRepository $trainingsRepository
     * @param TypesRepository $typesRepository
     * @return View
     */
    public function edit(Training $training, TrainingsRepository $trainingsRepository, TypesRepository $typesRepository)
    {
        $types = $typesRepository->dropdown();
        $images = $trainingsRepository->getImageFiles($training);
        $videos = $trainingsRepository->getVideoFiles($training);

        $data = array_merge(
            [
                'pageTitle' => $training->name,
                'record'    => $training
            ],
            compact('types', 'images', 'videos')
        );

        return view('admin.trainings.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param TrainingsRequest $request
     * @param Training $training
     * @param TrainingsRepository $trainingsRepository
     * @return RedirectResponse
     */
    public function update(TrainingsRequest $request, Training $training, TrainingsRepository $trainingsRepository)
    {
        try {
            $trainingsRepository->update($training, $request->all());

            return redirect()->route('admin.trainings.index')
                ->with('success', __('app.the_record_has_been_updated'));
        } catch (Exception $e) {
            return redirect()->back()->withErrors($e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Training $training
     * @param TrainingsRepository $trainingsRepository
     * @return RedirectResponse
     */
    public function destroy(Training $training, TrainingsRepository $trainingsRepository)
    {
        try {
            $trainingsRepository->deleteFiles($training);
            $training->delete();

            return redirect()->route('admin.trainings.index')->with('success', __('app.record_has_deleted'));
        } catch (Exception $e) {
            return redirect()->back()->withInput()->withErrors($e->getMessage());
        }
    }
}
