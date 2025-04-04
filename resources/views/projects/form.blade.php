@extends('layouts.app')

@section('content')
    <h1 class="text-3xl font-bold mb-6">
        {{ $project ? 'Modifier le projet' : 'Créer un nouveau projet' }}
    </h1>

    <form action="{{ $project ? route('projects.update', $project) : route('projects.store') }}" method="POST">
        @csrf
        @if($project)
            @method('PUT') <!-- Utilisé pour les mises à jour -->
        @endif

        <div class="mb-4">
            <label for="name" class="block text-gray-700 text-sm font-bold mb-2">Nom du projet</label>
            <input type="text" name="name" id="name"
                   class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                   value="{{ old('name', $project->name ?? '') }}" required>
            @error('name')
                <p class="text-red-500 text-xs italic">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-4">
            <label for="description" class="block text-gray-700 text-sm font-bold mb-2">Description</label>
            <textarea name="description" id="description" rows="4"
                      class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">{{ old('description', $project->description ?? '') }}</textarea>
            @error('description')
                <p class="text-red-500 text-xs italic">{{ $message }}</p>
            @enderror
        </div>

        <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
            {{ $project ? 'Mettre à jour' : 'Créer' }}
        </button>
    </form>
@endsection
