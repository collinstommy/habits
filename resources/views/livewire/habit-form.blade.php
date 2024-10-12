<?php

use App\Models\Habit;
use Illuminate\Support\Str;

use function Livewire\Volt\rules;
use function Livewire\Volt\state;
use function Livewire\Volt\computed;

state(['name' => '', 'slug' => '']);

rules(['name' => 'required|min:3|max:255|unique:habits,name']);

$updateSlug = function ($value) {
    $this->slug = Str::slug($value);
};

$createHabit = function () {
    $this->validate();

    Habit::create([
        'name' => $this->name,
        'slug' => $this->slug,
    ]);

    $this->reset('name', 'slug');
    $this->dispatch('habitAdded');
};

?>

<div>
    <form wire:submit="createHabit" class="mt-4">
        <div class="flex items-center">
            <input wire:model.live="name" wire:change="updateSlug($event.target.value)" type="text"
                placeholder="New habit name" class="flex-grow px-3 py-2 mr-2 border rounded-md">
            <button type="submit" class="px-4 py-2 text-white bg-blue-600 rounded-md hover:bg-blue-700">
                Add Habit
            </button>
        </div>
        @error('name')
            <span class="text-sm text-red-600">{{ $message }}</span>
        @enderror
        <div class="mt-2">
            <span class="text-sm text-gray-600">Slug: {{ $slug }}</span>
        </div>
    </form>
</div>
