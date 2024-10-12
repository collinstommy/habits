<?php

use App\Models\Habit;

use function Livewire\Volt\mount;
use function Livewire\Volt\state;

state([
    'habit' => null,
    'activeSession' => null,
    'lastSevenDays' => [],
    'maxMinutesPerDay' => 0,
]);

mount(function (Habit $habit) {
    $this->habit = $habit;
    $this->activeSession = $this->habit->activeSessions()->first();
    $this->calculateStats();
});

$startSession = function () {
    $this->habit->sessions()->create(['start_time' => now()]);
    $this->activeSession = $this->habit->activeSessions()->first();
};

$endCurrentSession = function () {
    if ($this->activeSession) {
        $this->activeSession->update(['end_time' => now()]);
        $this->activeSession = null;
        $this->calculateStats();
    }
};

$calculateStats = function () {
    $this->lastSevenDays = $this->habit
        ->sessions()
        ->where('start_time', '>=', now()->subDays(7))
        ->get()
        ->groupBy(function ($session) {
            return $session->start_time->format('Y-m-d');
        })
        ->map(function ($sessions) {
            return $sessions->sum(function ($session) {
                $endTime = $session->end_time ?? now();

                return $endTime->diffInMinutes($session->start_time);
            });
        });

    $this->maxMinutesPerDay = $this->lastSevenDays->max() ?? 0;
};

?>

<div>
    <h2 class="mb-4 text-2xl font-bold">{{ $habit->name }}</h2>

    <div class="mb-6">
        @if ($activeSession)
            <p class="text-lg">Session in progress</p>
            <button wire:click="endCurrentSession"
                class="px-4 py-2 mt-2 text-white bg-red-600 rounded-md hover:bg-red-700">
                End Session
            </button>
        @else
            <button wire:click="startSession" class="px-4 py-2 text-white bg-green-600 rounded-md hover:bg-green-700">
                Start Session
            </button>
        @endif
    </div>

    <div class="mb-6">
        <h3 class="mb-2 text-xl font-semibold">Stats</h3>
        <p>Max minutes per day: {{ $maxMinutesPerDay }}</p>
        <h4 class="mt-4 mb-2 text-lg font-semibold">Last 7 days:</h4>
        <ul>
            @foreach ($lastSevenDays as $date => $minutes)
                <li>{{ $date }}: {{ floor($minutes / 60) }}h {{ $minutes % 60 }}m</li>
            @endforeach
        </ul>
    </div>

    <div class="mb-6">
        <h3 class="mb-2 text-xl font-semibold">Graphs</h3>
        <p>Implement graphs here using a JavaScript charting library like Chart.js</p>
    </div>
</div>
