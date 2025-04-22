@extends('layouts.app')

@section('content')
    <h1 class="text-3xl font-bold mb-6">
        {{ $project ? 'Modifier le projet' : 'Créer un nouveau projet' }}
    </h1>

    <form action="{{ isset($project) ? route('projects.update', $project) : route('projects.store') }}" method="POST">
        @csrf
        @if(isset($project))
            @method('PUT')
        @endif

        <!-- Carte Informations générales et Actions -->
        <div class="bg-white shadow-md rounded-lg p-6 mb-6">
            <h2 class="text-xl font-semibold mb-4">Informations générales</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Nom du projet -->
                <div>
                    <label for="name" class="block text-gray-700 text-sm font-bold mb-2">Nom du projet</label>
                    <input type="text" name="name" id="name"
                           class="shadow appearance-none border border-gray-300 rounded w-full py-2 px-3 text-gray-700 leading-tight focus:ring focus:ring-blue-200 focus:border-blue-500 bg-gray-50"
                           value="{{ old('name', $project->name ?? '') }}" required>
                    @error('name')
                        <p class="text-red-500 text-xs italic">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Description -->
                <div>
                    <label for="description" class="block text-gray-700 text-sm font-bold mb-2">Description</label>
                    <textarea name="description" id="description" rows="4"
                              class="shadow appearance-none border border-gray-300 rounded w-full py-2 px-3 text-gray-700 leading-tight focus:ring focus:ring-blue-200 focus:border-blue-500 bg-gray-50">{{ old('description', $project->description ?? '') }}</textarea>
                    @error('description')
                        <p class="text-red-500 text-xs italic">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Actions en bas à droite -->
            <div class="flex justify-end mt-6">
                @if($project)
                <!-- Bouton Mettre à jour -->
                <button type="submit" class="bg-blue-500 hover:bg-blue-500 hover:bg-opacity-80 text-white font-bold py-2 px-4 rounded-full transition-colors duration-200 ml-2">
                    Mettre à jour
                </button>

                    <!-- Bouton Supprimer -->
                    <form action="{{ route('projects.destroy', $project) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="bg-red-500 hover:bg-red-500 hover:bg-opacity-80 text-white font-bold py-2 px-4 rounded-full transition-colors duration-200 ml-2">
                            Supprimer
                        </button>
                    </form>

                    <!-- Bouton Valider/Invalider -->
                    <form action="{{ route('projects.changeStatus', $project) }}" method="POST">
                        @csrf
                        <button type="submit" class="bg-green-500 hover:bg-green-500 hover:bg-opacity-80 text-white font-bold py-2 px-4 rounded-full transition-colors duration-200 ml-2">
                            {{ $project->is_completed ? 'Invalider' : 'Valider' }}
                        </button>
                    </form>
                @else
                    <!-- Bouton Créer -->
                    <button type="submit" class="bg-blue-500 hover:bg-blue-500 hover:bg-opacity-80 text-white font-bold py-2 px-4 rounded-full transition-colors duration-200 ml-2">
                        Créer
                    </button>
                @endif
            </div>
        </div>
    </form>

    <!-- Gestion des collaborateurs (uniquement si un projet existe) -->
    @if($project && auth()->id() == $project->user_id)
        <div class="bg-white shadow-md rounded-lg p-6 mb-6">
            <h2 class="text-xl font-semibold mb-4">Gestion des collaborateurs</h2>
            <form action="{{ route('projects.users.add', $project) }}" method="POST" class="mb-4">
                @csrf
                <div class="flex items-center">
                    <input type="text" name="username" id="username" placeholder="Nom d'utilisateur"
                           class="shadow appearance-none border border-gray-300 rounded w-full py-2 px-3 text-gray-700 leading-tight focus:ring focus:ring-blue-200 focus:border-blue-500 bg-gray-50"
                           required>
                    <button type="submit" class="ml-2 bg-green-500 hover:bg-green-500 hover:bg-opacity-80 text-white font-bold py-2 px-4 rounded-full transition-colors duration-200">
                        Ajouter
                    </button>
                </div>
            </form>

            <h3>Collaborateurs actuels</h3>
            @if($project->users && $project->users->count() > 0)
                <div class="overflow-x-auto">
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
                                            <button type="submit" class="bg-red-500 hover:bg-red-500 hover:bg-opacity-80 text-white font-bold py-2 px-4 rounded-full transition-colors duration-200">
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
                <p>Aucun collaborateur pour le moment.</p>
            @endif
        </div>
    @endif
@endsection
