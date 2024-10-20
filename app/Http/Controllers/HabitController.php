<?php

namespace App\Http\Controllers;

use App\Models\Habit;
use Illuminate\View\View;

class HabitController extends Controller
{
    public function show(string $id): View
    {
        $habit = Habit::findOrFail($id);

        return view('livewire.pages.habit-page', [
            'habit' => $habit,
        ]);
    }
}
