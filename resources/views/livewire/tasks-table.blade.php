<div>
    <!-- Filtre des utilisateurs -->
    <div class="flex items-center mb-4">
        <label for="filterUser" class="block text-gray-700 text-sm font-bold mr-2">
            Filtrer par utilisateur :
        </label>
        <select id="filterUser" wire:model.live="filterUserId" class="mt-1 block border-gray-300 rounded-md shadow-sm text-sm w-36">
            <option value="">Tous</option>
            @foreach($projectUsers as $user)
                <option value="{{ $user->id }}">{{ $user->name }}</option>
            @endforeach
        </select>
    </div>

    <!-- Tableau des tâches -->
    <div class="bg-white shadow-md rounded-lg overflow-hidden">
        <table class="min-w-full leading-normal">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-2 py-2 border w-12 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">
                        État
                    </th>
                    <th class="px-2 py-2 border text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                        Tâche
                    </th>
                    <th class="px-5 py-2 border w-24 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">
                        Assignation
                    </th>
                    <th class="px-2 py-2 border w-24 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">
                        Échéance
                    </th>
                    <th class="px-2 py-2 border w-24 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">
                        Actions
                    </th>
                </tr>
            </thead>
            <tbody class="bg-white">
                @foreach($tasks as $task)
                <tr>
                    <td class="px-2 py-2 border text-center">
                        <input type="checkbox" wire:click="toggleTaskState({{ $task->id }})" {{ $task->is_completed ? 'checked' : '' }} class="form-checkbox h-5 w-5 text-blue-600">
                    </td>
                    <td class="px-2 py-2 border text-sm">
                        <p class="text-gray-900 whitespace-no-wrap">{{ $task->title }}</p>
                    </td>
                    <td class="px-2 py-2 border text-center">
                        {{ $task->assignedUser ? $task->assignedUser->name : 'Non assigné' }}
                    </td>
                    <td class="px-2 py-2 border text-center">
                        {{ $task->deadline ? $task->deadline->format('d/m/Y') : 'Non définie' }}
                    </td>
                    <td class="px-2 py-2 border text-center">
                        <div class="flex justify-center space-x-1">
                            <button wire:click="openEditTaskModal({{ $task->id }})"
                                    class="bg-yellow-400 hover:bg-yellow-600 text-white font-bold py-1 px-2 rounded-full text-xs"
                                    title="Modifier">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                </svg>
                            </button>
                            <button wire:click="deleteTask({{ $task->id }})"
                                    class="bg-red-500 hover:bg-red-700 text-white font-bold py-1 px-2 rounded-full text-xs"
                                    title="Supprimer">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                            </button>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="5" class="px-2 py-2 border">
                        <form wire:submit.prevent="addTask" class="flex items-center space-x-2">
                            <input type="text" wire:model="newTaskName" placeholder="Nom de la nouvelle tâche"
                                   class="border rounded px-2 py-1 flex-grow text-sm" />
                            <button type="submit" class="bg-green-500 hover:bg-green-700 text-white font-bold py-1 px-2 rounded-full text-xs">
                                Ajouter
                            </button>
                        </form>
                    </td>
                </tr>
            </tfoot>
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
    const assignedUserId = taskData?.assignedUserId || ''; // Récupérer l'utilisateur assigné
    console.log("Utilisateur assigné :", assignedUserId);
    const projectUsers = taskData?.projectUsers || []; // Récupérer les utilisateurs du projet
    console.log("Utilisateurs du projet :", projectUsers);

    // Convertir la date au format jj/mm/aaaa sans décalage
    const formattedDeadline = deadline ? deadline.split('T')[0] : '';
    console.log("Date d'échéance formatée :", formattedDeadline);

    Swal.fire({
        title: 'Modifier la tâche',
        width: '600px', // Réduire la largeur
        html: `
            <div class="flex flex-col space-y-4">
                <div>
                    <label for="swal-input1" class="block text-gray-700 text-sm font-bold mb-2">Titre</label>
                    <input id="swal-input1" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" value="${title}">
                </div>
                <div>
                    <label for="swal-input2" class="block text-gray-700 text-sm font-bold mb-2">Description</label>
                    <textarea id="swal-input2" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline h-32">${description}</textarea>
                </div>
                <div>
                    <label for="swal-input3" class="block text-gray-700 text-sm font-bold mb-2">Date d'échéance</label>
                    <input id="swal-input3" type="date" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" value="${formattedDeadline}">
                </div>
                <div>
                    <label for="swal-input4" class="block text-gray-700 text-sm font-bold mb-2">Assigner à</label>
                    <select id="swal-input4" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                        <option value="">Aucun utilisateur</option>
                        ${projectUsers.map(user => `
                            <option value="${user.id}" ${user.id === assignedUserId ? 'selected' : ''}>
                                ${user.name}
                            </option>
                        `).join('')}
                    </select>
                </div>
            </div>
        `,
        focusConfirm: false,
        showCancelButton: true,
        confirmButtonText: 'Enregistrer',
        cancelButtonText: 'Annuler',
        buttonsStyling: false,
        customClass: {
            confirmButton: 'bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-full mr-2',
            cancelButton: 'bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded-full'
        },
        preConfirm: () => {
            const newTitle = document.getElementById('swal-input1').value;
            const newDescription = document.getElementById('swal-input2').value;
            const newDeadline = document.getElementById('swal-input3').value;
            const newAssignedUserId = document.getElementById('swal-input4').value;

            @this.call('updateTaskData', newTitle, newDescription, newDeadline, newAssignedUserId);
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
