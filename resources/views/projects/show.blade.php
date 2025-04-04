@extends('layouts.app')

@section('content')
    <h1 class="text-3xl font-bold mb-6">{{ $project->name }}</h1>

    <!-- Notifications -->
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <!-- Actions -->

    <!-- Phases et tâches -->
    <div class="relative">
        <livewire:tasks-table :project-id="$project->id" />
    </div>
@endsection
