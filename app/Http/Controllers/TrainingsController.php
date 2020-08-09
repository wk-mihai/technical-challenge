<?php

namespace App\Http\Controllers;

use App\Repositories\TrainingsRepository;
use App\Repositories\TypesRepository;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
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

        if ($type !== null) {
            $currentType = $typesRepository->getTypeWithAccess($type);

            if (is_null($currentType)) {
                abort(404);
            }
        }

        $types = $typesRepository->allWhereHasTrainings($request, [], ['name', 'asc']);

        /** @var LengthAwarePaginator $trainings */
        $trainings = $trainingsRepository->filter($request, $type, 16);
        $trainings->setPath(route('trainings.index', array_merge($request->only('search'), ['type' => $type])));

        return view('pages.trainings.index', compact('pageTitle', 'types', 'trainings'));
    }

    /**
     * Display the specified resource.
     *
     * @param int $trainingId
     * @param TrainingsRepository $trainingsRepository
     * @return View
     */
    public function show(int $trainingId, TrainingsRepository $trainingsRepository)
    {
        $training = $trainingsRepository->getTrainingByIdWhereHasType(
            $trainingId,
            ['files' => fn($query) => $query->orderBy('type', 'desc')]
        );

        $data = [
            'pageTitle' => $training->name,
            'training'  => $training
        ];

        return view('pages.trainings.show', $data);
    }
}
