<div>
    <!-- Tableau des tâches -->
    <div class="overflow-x-auto max-h-[500px]">
        <table class="min-w-full bg-white border border-gray-300">
            <thead>
                <tr>
                    <th class="px-4 py-2 border">Tâche</th>
                    <th class="px-4 py-2 border w-16 text-center">État</th>
                    <th class="px-4 py-2 border w-32 text-center">Échéance</th>
                    <th class="px-4 py-2 border w-32 text-center">Actions</th>
                </tr>
            </thead>
            <tbody wire:sortable="updateTaskOrder">
                @foreach($tasks as $task)
                    <tr class="sortable-item" wire:key="task-{{ $task->id }}" wire:sortable.item="{{ $task->id }}">
                        <td class="px-4 py-2 border">{{ $task->title }}</td>
                        <td class="px-4 py-2 border text-center">
                            <div class="flex justify-center">
                                <input type="checkbox" wire:click="toggleTaskState({{ $task->id }})" {{ $task->is_completed ? 'checked' : '' }} class="form-checkbox h-5 w-5 text-blue-600">
                            </div>
                        </td>
                        <td class="px-4 py-2 border text-center">
                            {{ $task->deadline ? $task->deadline->format('d/m/Y') : 'Non définie' }}
                        </td>
                        <td class="px-4 py-2 border text-center">
                            <div class="flex justify-center space-x-2">
                                <button wire:click="openEditTaskModal({{ $task->id }})"
                                        class="bg-yellow-400 hover:bg-yellow-600 text-white rounded-full p-2"
                                        title="Modifier">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                    </svg>
                                </button>
                                <button wire:click="deleteTask({{ $task->id }})"
                                        class="bg-red-500 hover:bg-red-700 text-white rounded-full p-2"
                                        title="Supprimer">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1v3M4 7h16" />
                                    </svg>
                                </button>
                            </div>
                        </td>
                    </tr>
                @endforeach
                <!-- Formulaire d'ajout de tâche -->
                <tr>
                    <td colspan="4" class="px-4 py-2 border">
                        <form wire:submit.prevent="addTask" class="flex items-center space-x-2">
                            <!-- Champ d'entrée pour le nom de la tâche -->
                            <input type="text" wire:model="newTaskName" placeholder="Nom de la nouvelle tâche"
                                   class="border rounded px-2 py-1 flex-grow" />

                            <!-- Bouton Ajouter Tâche -->
                            <button type="submit" class="bg-green-500 hover:bg-green-700 text-white font-bold py-1 px-3 rounded">
                                Ajouter Tâche
                            </button>
                        </form>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

<script>
    window.addEventListener('open-edit-task-modal', event => {
        console.log("Données reçues dans l'event :", event.detail);

        const taskData = event.detail[0]; // Accéder directement à l'objet
        const title = taskData?.title || ''; // Récupérer le titre
        console.log("Titre de la tâche :", title);
        const description = taskData?.description || ''; // Récupérer la description
        console.log("Description de la tâche :", description);
        const deadline = taskData?.deadline || ''; // Récupérer la date d'échéance
        console.log("Date d'échéance de la tâche :", deadline);

        // Convertir la date au format jj/mm/aaaa sans décalage
        const formattedDeadline = deadline ? deadline.split('T')[0] : '';
        console.log("Date d'échéance formatée :", formattedDeadline);

        Swal.fire({
            title: 'Modifier la tâche',
            width: '800px',
            html: `
                <div style="text-align: left; width: 100%; max-width: 700px; margin: auto;">
                    <div style="margin-bottom: 15px;">
                        <label for="swal-input1">Titre</label>
                        <input id="swal-input1" class="swal2-input" value="${title}">
                    </div>
                    <div style="margin-bottom: 15px;">
                        <label for="swal-input2">Description</label>
                        <textarea id="swal-input2" class="swal2-textarea">${description}</textarea>
                    </div>
                    <div style="margin-bottom: 15px;">
                        <label for="swal-input3">Date d'échéance</label>
                        <input id="swal-input3" type="date" class="swal2-input" value="${formattedDeadline}">
                    </div>
                </div>
            `,
            focusConfirm: false,
            showCancelButton: true,
            confirmButtonText: 'Enregistrer',
            preConfirm: () => {
                const newTitle = document.getElementById('swal-input1').value;
                const newDescription = document.getElementById('swal-input2').value;
                const newDeadline = document.getElementById('swal-input3').value;

                @this.call('updateTaskData', newTitle, newDescription, newDeadline);
            }
        });
    });

    const style = document.createElement('style');
    style.innerHTML = `
        .swal2-cancel-red {
            background-color: #e3342f !important; /* Rouge */
            color: white !important;
        }
        .swal2-cancel-red:hover {
            background-color: #cc1f1a !important; /* Rouge foncé */
        }
        .swal2-actions-left {
            justify-content: flex-start !important; /* Aligner les boutons à gauche */
            margin-top: 20px; /* Espacement entre les champs et les boutons */
        }
        .swal2-popup {
            text-align: center !important; /* Centrer le contenu de la popup */
        }
        .swal2-html-container {
            text-align: left !important; /* Aligner le contenu HTML à gauche */
        }
        .swal2-input, .swal2-textarea {
            margin: 0 auto; /* Centrer les champs horizontalement */
            width: 100%; /* S'assurer que les champs occupent toute la largeur disponible */
            box-sizing: border-box; /* Inclure les bordures et le padding dans la largeur */
        }
        .swal2-input {
            margin-bottom: 15px; /* Espacement entre les champs */
        }
        .swal2-textarea {
            height: 150px; /* Hauteur fixe pour le champ Description */
            margin-bottom: 15px; /* Espacement entre les champs */
        }
        .swal2-label {
            font-weight: bold;
            margin-bottom: 5px;
            display: block; /* Forcer les labels à être sur une ligne séparée */
        }
    `;
    document.head.appendChild(style);

    window.addEventListener('close-edit-task-modal', () => {
        Swal.close();
    });
</script>
