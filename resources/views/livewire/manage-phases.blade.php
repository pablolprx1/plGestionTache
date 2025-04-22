<div>
    <button wire:click="$set('showModal', true)" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
        Gérer les phases
    </button>

    @if($showModal)
        <div class="fixed inset-0 flex items-center justify-center bg-gray-900 bg-opacity-50 z-50">
            <div class="bg-white rounded-lg shadow-lg p-6 w-1/2">
                <h2 class="text-xl font-bold mb-4">Gérer les phases</h2>

                <!-- Liste des phases -->
                <div class="space-y-4">
                    @foreach($this->phases as $phase)
                        <div class="flex items-center justify-between">
                            @if($editingPhaseId === $phase->id)
                                <input type="text" wire:model="editingPhaseName" class="border rounded px-2 py-1">
                                <button wire:click="updatePhase" class="bg-green-500 hover:bg-green-700 text-white font-bold py-1 px-3 rounded">Enregistrer</button>
                            @else
                                <span>{{ $phase->name }}</span>
                                <div class="flex space-x-2">
                                    <button wire:click="editPhase({{ $phase->id }})" class="bg-yellow-400 hover:bg-yellow-600 text-white font-bold py-1 px-3 rounded">Modifier</button>
                                    <button wire:click="deletePhase({{ $phase->id }})" class="bg-red-500 hover:bg-red-700 text-white font-bold py-1 px-3 rounded">Supprimer</button>
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>

                <!-- Ajouter une nouvelle phase -->
                <div class="mt-4">
                    <input type="text" wire:model="newPhaseName" placeholder="Nom de la nouvelle phase" class="border rounded px-2 py-1">
                    <button wire:click="addPhase" class="bg-green-500 hover:bg-green-700 text-white font-bold py-1 px-3 rounded">Ajouter</button>
                    @error('newPhaseName') <span class="text-red-500">{{ $message }}</span> @enderror
                </div>

                <!-- Bouton de fermeture -->
                <button wire:click="$set('showModal', false)" class="mt-4 bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">Fermer</button>
            </div>
        </div>
    @endif
</div>
