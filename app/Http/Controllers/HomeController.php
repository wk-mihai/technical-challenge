<?php

namespace App\Http\Controllers;

use App\Repositories\TrainingsRepository;
use Illuminate\View\View;

class HomeController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @param TrainingsRepository $trainingsRepository
     * @return View
     */
    public function index(TrainingsRepository $trainingsRepository)
    {
        $trainingsWithFiles = $trainingsRepository->trainingsWithFiles();

        $data = [
            'pageTitle'      => __('Airline Client Trainings'),
            'totalTrainings' => $trainingsWithFiles->count(),
            'totalFiles'     => $trainingsWithFiles->sum('files_count')
        ];

        return view('home', $data);
    }
}
