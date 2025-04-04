<header class="bg-white shadow">
    <nav class="container mx-auto px-6 py-3">
        <div class="flex justify-between items-center">
            <div class="text-xl font-semibold text-gray-700">
                <a href="{{ route('dashboard') }}">Gestion de Projet</a>
            </div>
            <div class="flex space-x-4">
                <a href="{{ route('dashboard') }}" class="text-gray-700 hover:text-gray-900">Dashboard</a>
                <a href="{{ route('projects.index') }}" class="text-gray-700 hover:text-gray-900">Projets</a>
                <a href="#" class="text-gray-700 hover:text-gray-900">Mon Compte</a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="text-gray-700 hover:text-gray-900">Déconnexion</button>
                </form>
            </div>
        </div>
    </nav>
</header>
