@extends('layouts.app')

@section('content')
    <h1 class="text-3xl font-bold mb-6">Projets</h1>

    <!-- En-tête avec bouton d'ajout et filtres -->
    <div class="flex justify-between items-center mb-6">
        <!-- Bouton d'ajout -->
        <div>
            <a href="{{ route('projects.create') }}" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded-full transition-colors duration-200">
                Ajouter
            </a>
        </div>

        <!-- Filtres de statut -->
        <div class="space-x-2">
            <a href="{{ route('projects.index') }}" class="inline-block px-4 py-2 rounded-full text-white font-bold transition-colors duration-200
                           {{ !$status ? 'bg-blue-500 hover:bg-blue-700' : 'bg-gray-300 text-gray-700 hover:bg-gray-400' }}">
                Tous
            </a>
            <a href="{{ route('projects.index', ['status' => 'completed']) }}" class="inline-block px-4 py-2 rounded-full text-white font-bold transition-colors duration-200
                           {{ $status === 'completed' ? 'bg-green-500 hover:bg-green-700' : 'bg-gray-300 text-gray-700 hover:bg-gray-400' }}">
                Terminés
            </a>
            <a href="{{ route('projects.index', ['status' => 'incomplete']) }}" class="inline-block px-4 py-2 rounded-full text-white font-bold transition-colors duration-200
                           {{ $status === 'incomplete' ? 'bg-yellow-500 hover:bg-yellow-700' : 'bg-gray-300 text-gray-700 hover:bg-gray-400' }}">
                En cours
            </a>
        </div>
    </div>

    <!-- Grille des projets -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
    @forelse($projects as $project)
        <!-- Carte de projet -->
        <div class="bg-white rounded-lg shadow-md overflow-hidden transition-transform duration-200 hover:scale-105 flex flex-col justify-between">
            <div class="p-6 flex-1 flex flex-col">
                <h2 class="text-xl font-semibold text-gray-800 mb-2">{{ $project->name }}</h2>
                <p class="text-gray-600 mb-4">{{ Str::limit($project->description, 100) }}</p>
                <span class="text-sm font-semibold {{ $project->is_completed ? 'text-green-500' : 'text-yellow-500' }}">
                    {{ $project->is_completed ? 'Terminé' : 'En cours' }}
                </span>
            </div>

            <div class="flex items-center justify-between bg-gray-50 px-6 py-4">
                <!-- Bouton Tâches à gauche -->
                <a href="{{ route('projects.show', $project) }}"
                   class="inline-block bg-gray-300 hover:bg-gray-400 text-gray-700 font-semibold py-2 px-4 rounded-full transition-colors duration-200">
                    Tâches
                </a>
                <!-- Actions à droite -->
                @if($project->isCreator(auth()->user()))
                    <div class="flex space-x-2">
                        <a href="{{ route('projects.edit', $project) }}"
                           class="inline-block bg-gray-300 hover:bg-gray-400 text-gray-700 font-semibold py-2 px-4 rounded-full transition-colors duration-200">
                            Modifier
                        </a>
                        <form action="{{ route('projects.destroy', $project) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                    class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded-full transition-colors duration-200">
                                Supprimer
                            </button>
                        </form>
                    </div>
                @endif
            </div>
        </div>
    @empty
        <p class="text-gray-500">Aucun projet trouvé.</p>
    @endforelse
</div>



    <!-- Pagination -->
    <div class="mt-8">
        {{ $projects->links() }}
    </div>
@endsection
