@extends('layouts.app')

@section('content')
    <h1 class="text-3xl font-bold mb-6">Accueil</h1>

    <!-- Conteneur principal pour les cartes -->
    <div class="flex flex-wrap -mx-4">

        <!-- Carte des tâches en retard -->
        <div class="w-full md:w-1/3 px-4 mb-6">
            <div class="bg-red-100 border-red-500 border-l-4 rounded-lg shadow-md p-6">
                <h2 class="text-xl font-semibold text-red-500 mb-4">Tâches en retard</h2>
                <ul class="list-disc pl-5">
                    @forelse($overdueTasks as $task)
                        <li>
                            <a href="{{ route('projects.show', $task->project_id) }}" class="text-blue-500 hover:underline">
                                <strong>{{ $task->title }}</strong> - Échéance : {{ $task->deadline->format('d/m/Y') }}
                            </a>
                        </li>
                    @empty
                        <p>Aucune tâche en retard.</p>
                    @endforelse
                </ul>
            </div>
        </div>

        <!-- Carte des tâches à venir -->
        <div class="w-full md:w-1/3 px-4 mb-6">
            <div class="bg-yellow-100 border-yellow-500 border-l-4 rounded-lg shadow-md p-6">
                <h2 class="text-xl font-semibold text-yellow-500 mb-4">Tâches à venir</h2>
                <ul class="list-disc pl-5">
                    @forelse($upcomingTasks as $task)
                        <li>
                            <a href="{{ route('projects.show', $task->project_id) }}" class="text-blue-500 hover:underline">
                                <strong>{{ $task->title }}</strong> - Échéance : {{ $task->deadline->format('d/m/Y') }}
                            </a>
                        </li>
                    @empty
                        <p>Aucune tâche à venir.</p>
                    @endforelse
                </ul>
            </div>
        </div>

        <!-- Carte des tâches terminées -->
        <div class="w-full md:w-1/3 px-4 mb-6">
            <div class="bg-green-100 border-green-500 border-l-4 rounded-lg shadow-md p-6">
                <h2 class="text-xl font-semibold text-green-500 mb-4">Tâches terminées</h2>
                <ul class="list-disc pl-5">
                    @forelse($completedTasks as $task)
                        <li>
                            <a href="{{ route('projects.show', $task->project_id) }}" class="text-blue-500 hover:underline">
                                <strong>{{ $task->title }}</strong> - Terminé le {{ $task->updated_at->format('d/m/Y') }}
                            </a>
                        </li>
                    @empty
                        <p>Aucune tâche terminée.</p>
                    @endforelse
                </ul>
            </div>
        </div>

    </div>
@endsection
