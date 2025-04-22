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

        <!-- Nom du projet -->
        <div class="mb-4">
            <label for="name" class="block text-gray-700 text-sm font-bold mb-2">Nom du projet</label>
            <input type="text" name="name" id="name"
                   class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                   value="{{ old('name', $project->name ?? '') }}" required>
            @error('name')
                <p class="text-red-500 text-xs italic">{{ $message }}</p>
            @enderror
        </div>

        <!-- Description -->
        <div class="mb-4">
            <label for="description" class="block text-gray-700 text-sm font-bold mb-2">Description</label>
            <textarea name="description" id="description" rows="4"
                      class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">{{ old('description', $project->description ?? '') }}</textarea>
            @error('description')
                <p class="text-red-500 text-xs italic">{{ $message }}</p>
            @enderror
        </div>

        <!-- Bouton de soumission -->
        <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded mb-6">
            {{ $project ? 'Mettre à jour' : 'Créer' }}
        </button>
    </form>

    <!-- Gestion des collaborateurs (uniquement si un projet existe) -->
    @if($project && auth()->id() == $project->user_id)
        <div class="mt-8 bg-gray-100 p-6 rounded-lg">
            <h2 class="text-xl font-bold mb-4">Gestion des collaborateurs</h2>

            <!-- Formulaire d'ajout de collaborateur -->
            <form action="{{ route('projects.users.add', $project) }}" method="POST" class="mb-6 flex items-center space-x-4">
                @csrf
                <input type="text" name="username" placeholder="Nom d'utilisateur"
                       class="shadow appearance-none border rounded py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline flex-grow">
                <button type="submit" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                    Ajouter Collaborateur
                </button>
            </form>
            @error('username')
                @php $message = $message ?? ''; @endphp
                <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
            @enderror

            <!-- Liste des collaborateurs -->
            <h3 class="font-semibold mb-2">Collaborateurs actuels</h3>
            @if($project->users && $project->users->count() > 0)
                <div class="bg-white rounded-lg overflow-hidden">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nom</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nom d'utilisateur</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($project->users as $user)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $user->name }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $user->username }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right">
                                        <form action="{{ route('projects.users.remove', [$project, $user]) }}" method="POST" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="bg-red-500 hover:bg-red-700 text-white font-bold py-1 px-3 rounded text-sm">
                                                Supprimer
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <p class="text-gray-500 italic">Aucun collaborateur pour le moment.</p>
            @endif
        </div>
    @endif
@endsection
