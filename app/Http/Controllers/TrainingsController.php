<?php

namespace App\Http\Controllers;

use App\Models\Training;
use App\Repositories\TrainingsRepository;
use App\Repositories\TypesRepository;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TrainingsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @param TrainingsRepository $trainingsRepository
     * @param TypesRepository $typesRepository
     * @param string|null $type
     * @return View
     */
    public function index(
        Request $request,
        TrainingsRepository $trainingsRepository,
        TypesRepository $typesRepository,
        ?string $type = null
    )
    {
        $pageTitle = __('Trainings');

        $types = $typesRepository->allWhereHasTrainings($request, [], ['name', 'asc']);

        $allTrainingsCount = $trainingsRepository->count();
        $trainings = $trainingsRepository->filter($request, $type, 16);

        return view('pages.trainings.index', compact('pageTitle', 'types', 'trainings', 'allTrainingsCount'));
    }

    /**
     * Display the specified resource.
     *
     * @param Training $training
     * @return View
     */
    public function show(Training $training)
    {
        $pageTitle = $training->name;

        $training->load(['files' => fn($query) => $query->orderBy('type', 'desc')]);

        return view('pages.trainings.show', compact('pageTitle', 'training'));
    }
}
