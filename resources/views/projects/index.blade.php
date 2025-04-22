@extends('layouts.app')

@section('content')
    <h1 class="text-3xl font-bold mb-6">Projets</h1>

    <div class="flex justify-between items-center mb-4">
        <!-- Bouton d'ajout -->
        <div>
            <a href="{{ route('projects.create') }}" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">Ajouter</a>
        </div>

        <!-- Boutons de tri -->
        <div class="flex items-center space-x-2">
            <a href="{{ route('projects.index') }}" class="px-4 py-2 bg-blue-500 text-white rounded {{ !$status ? 'bg-blue-700' : '' }}">Tous</a>
            <a href="{{ route('projects.index', ['status' => 'completed']) }}" class="px-4 py-2 bg-green-500 text-white rounded {{ $status === 'completed' ? 'bg-green-700' : '' }}">Terminés</a>
            <a href="{{ route('projects.index', ['status' => 'incomplete']) }}" class="px-4 py-2 bg-yellow-500 text-white rounded {{ $status === 'incomplete' ? 'bg-yellow-700' : '' }}">En cours</a>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
        @foreach($projects as $project)
            <div class="relative bg-white p-4 rounded shadow cursor-pointer group">
                <h2 class="text-xl font-semibold mb-2">{{ $project->name }}</h2>
                <p class="text-gray-600 mb-2">{{ Str::limit($project->description, 100) }}</p>
                <p class="text-sm {{ $project->is_completed ? 'text-green-500' : 'text-yellow-500' }}">
                    {{ $project->is_completed ? 'Terminé' : 'En cours' }}
                </p>

                <!-- Boutons visibles au survol -->
                <div class="absolute inset-0 flex items-center justify-center bg-gray-900 bg-opacity-50 opacity-0 group-hover:opacity-100 transition-opacity">
                    <a href="{{ route('projects.show', $project) }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded mr-2">Détail</a>

                    @if($project->isCreator(auth()->user())) <!-- Vérifie si l'utilisateur est le créateur -->
                        <a href="{{ route('projects.edit', $project) }}" class="bg-yellow-400 hover:bg-yellow-600 text-white font-bold py-2 px-4 rounded mr-2">Modifier</a>
                        <form action="{{ route('projects.destroy', $project) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">Supprimer</button>
                        </form>
                    @endif
                </div>
            </div>
        @endforeach
    </div>

    <div class="mt-4">
        {{ $projects->links() }}
    </div>
@endsection
