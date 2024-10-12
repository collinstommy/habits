<?php

use App\Models\Habit;

use function Livewire\Volt\mount;
use function Livewire\Volt\on;
use function Livewire\Volt\state;

state(['habits' => []]);

mount(function () {
    $this->habits = Habit::all();
});

on(['habitAdded' => fn() => ($this->habits = Habit::all())]);

$deleteHabit = function ($id) {
    $habit = Habit::find($id);
    if ($habit) {
        $habit->delete();
        $this->habits = Habit::all();
    }
};

?>

<div>
    <h3 class="mb-4 text-lg font-semibold">Your Habits</h3>
    <ul class="space-y-2">
        @foreach ($habits as $habit)
            <li class="flex items-center justify-between">
                <a href="{{ route('habit.show', $habit->slug) }}" class="text-blue-600 hover:underline">
                    {{ $habit->name }}
                </a>
                <button wire:click="$emit('confirmDelete', {{ $habit->id }})" class="text-red-600 hover:text-red-800">
                    Delete
                </button>
            </li>
        @endforeach
    </ul>
    <div class="mt-4">
        <livewire:habit-form />
    </div>

    <script>
        window.addEventListener('livewire:load', function() {
            Livewire.on('confirmDelete', habitId => {
                if (confirm(
                        'Are you sure you want to delete this habit? All associated sessions will be removed.'
                    )) {
                    @this.call('deleteHabit', habitId);
                }
            });
        });
    </script>
</div>
