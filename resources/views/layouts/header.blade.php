<header class="bg-white shadow">
    <nav class="container mx-auto px-6 py-3">
        <div class="flex justify-between items-center">
            <div class="text-xl font-semibold text-gray-700">
                <a href="{{ route('dashboard') }}">plGestionTâche</a>
            </div>
            <div class="flex space-x-4 items-center">
                <a href="{{ route('dashboard') }}" class="text-gray-700 hover:text-gray-900">Dashboard</a>
                <a href="{{ route('projects.index') }}" class="text-gray-700 hover:text-gray-900">Projets</a>

                <div class="relative inline-block text-left" id="account-menu-container">
                    <button type="button" class="inline-flex justify-center items-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-gray-100 focus:ring-indigo-500" id="menu-button" aria-expanded="false" aria-haspopup="true">
                        Mon Compte
                        <svg class="-mr-1 ml-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                        </svg>
                    </button>

                    <!-- Élément invisible pour combler l'écart -->
                    <div class="absolute w-full h-3 right-0 -bottom-3" id="gap-filler"></div>

                    <div class="origin-top-right absolute right-0 mt-3 w-56 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 focus:outline-none hidden z-10" role="menu" aria-orientation="vertical" aria-labelledby="menu-button" tabindex="-1" id="dropdown-menu">
                        <div class="py-1" role="none">
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900" role="menuitem" tabindex="-1" id="menu-item-3">
                                    Déconnexion
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </nav>
</header>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const menuButton = document.getElementById('menu-button');
        const accountMenuContainer = document.getElementById('account-menu-container');
        const dropdownMenu = document.getElementById('dropdown-menu');
        const gapFiller = document.getElementById('gap-filler');

        // Utilisez mouseenter/mouseleave plutôt que mouseover/mouseout
        accountMenuContainer.addEventListener('mouseenter', () => {
            dropdownMenu.classList.remove('hidden');
        });

        // Ajouter des listeners pour le gap-filler et le dropdown-menu
        gapFiller.addEventListener('mouseenter', () => {
            dropdownMenu.classList.remove('hidden');
        });

        dropdownMenu.addEventListener('mouseenter', () => {
            dropdownMenu.classList.remove('hidden');
        });

        // Ne masquer que lorsque la souris quitte complètement le container, le gap filler, et le menu
        accountMenuContainer.addEventListener('mouseleave', (e) => {
            // Vérifier si la souris se déplace vers le menu ou le gap-filler
            if (!dropdownMenu.contains(e.relatedTarget) && !gapFiller.contains(e.relatedTarget)) {
                dropdownMenu.classList.add('hidden');
            }
        });

        dropdownMenu.addEventListener('mouseleave', () => {
            dropdownMenu.classList.add('hidden');
        });
    });
</script>
