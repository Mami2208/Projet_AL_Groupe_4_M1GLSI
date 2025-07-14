@extends('layouts.admin')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold">Gestion des jetons d'API</h1>
    </div>

    @if(session('status'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            {{ session('status') }}
        </div>
    @endif

    <div class="bg-white rounded-lg shadow overflow-hidden mb-8">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">Créer un nouveau jeton</h3>
        </div>
        <div class="px-6 py-4">
            <form id="create-token-form">
                @csrf
                <div class="mb-4">
                    <label for="token-name" class="block text-sm font-medium text-gray-700 mb-2">Nom du jeton</label>
                    <input type="text" id="token-name" name="name" required
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                </div>
                
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Permissions</label>
                    <div class="space-y-2">
                        <div class="flex items-center">
                            <input id="ability-read" name="abilities[]" type="checkbox" value="read"
                                   class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded" checked>
                            <label for="ability-read" class="ml-2 block text-sm text-gray-900">
                                Lecture (read)
                            </label>
                        </div>
                        <div class="flex items-center">
                            <input id="ability-write" name="abilities[]" type="checkbox" value="write"
                                   class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                            <label for="ability-write" class="ml-2 block text-sm text-gray-900">
                                Écriture (write)
                            </label>
                        </div>
                        <div class="flex items-center">
                            <input id="ability-delete" name="abilities[]" type="checkbox" value="delete"
                                   class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                            <label for="ability-delete" class="ml-2 block text-sm text-gray-900">
                                Suppression (delete)
                            </label>
                        </div>
                    </div>
                </div>
                
                <button type="submit" 
                        class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    Créer le jeton
                </button>
            </form>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">Jetons existants</h3>
        </div>
        <div class="divide-y divide-gray-200">
            @forelse($tokens as $token)
                <div class="px-6 py-4">
                    <div class="flex justify-between items-center">
                        <div>
                            <div class="font-medium text-gray-900">{{ $token->name }}</div>
                            <div class="text-sm text-gray-500">Créé le {{ $token->created_at->format('d/m/Y \à H:i') }}</div>
                            @if($token->last_used_at)
                                <div class="text-xs text-gray-500">Dernière utilisation : {{ $token->last_used_at->diffForHumans() }}</div>
                            @endif
                        </div>
                        <form action="{{ route('admin.api.tokens.destroy', $token->id) }}" method="POST" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="button" onclick="confirmDelete(this)" 
                                    class="text-red-600 hover:text-red-900 text-sm font-medium">
                                Supprimer
                            </button>
                        </form>
                    </div>
                </div>
            @empty
                <div class="px-6 py-4 text-center text-gray-500">
                    Aucun jeton d'API créé pour le moment.
                </div>
            @endforelse
        </div>
    </div>
</div>

<!-- Modal pour afficher le nouveau jeton -->
<div id="token-modal" class="fixed z-10 inset-0 overflow-y-auto hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        <div class="inline-block align-bottom bg-white rounded-lg px-4 pt-5 pb-4 text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full sm:p-6">
            <div>
                <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-green-100">
                    <svg class="h-6 w-6 text-green-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                </div>
                <div class="mt-3 text-center sm:mt-5">
                    <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                        Jeton créé avec succès !
                    </h3>
                    <div class="mt-2">
                        <p class="text-sm text-gray-500">
                            Voici votre nouveau jeton d'API. Copiez-le maintenant, car vous ne pourrez plus le voir plus tard.
                        </p>
                        <div class="mt-4">
                            <div class="mt-1 relative rounded-md shadow-sm">
                                <input type="text" id="new-token" readonly 
                                       class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5" 
                                       value="">
                                <button onclick="copyToken()" 
                                        class="absolute inset-y-0 right-0 px-3 py-2 border-l border-gray-300 bg-gray-50 text-sm font-medium text-gray-700 hover:bg-gray-100 focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500">
                                    Copier
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="mt-5 sm:mt-6">
                <button type="button" onclick="closeModal()"
                        class="inline-flex justify-center w-full rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:text-sm">
                    Fermer
                </button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Gestion du formulaire de création de jeton
    document.getElementById('create-token-form').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        const abilities = [];
        document.querySelectorAll('input[name="abilities[]"]:checked').forEach(checkbox => {
            abilities.push(checkbox.value);
        });
        
        fetch('{{ route("admin.api.tokens.store") }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                name: formData.get('name'),
                abilities: abilities
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                document.getElementById('new-token').value = data.token;
                document.getElementById('token-modal').classList.remove('hidden');
                // Recharger la page après la création pour afficher le nouveau jeton
                setTimeout(() => {
                    window.location.reload();
                }, 3000);
            }
        })
        .catch(error => {
            console.error('Erreur:', error);
            alert('Une erreur est survenue lors de la création du jeton.');
        });
    });
    
    // Fonction pour copier le jeton dans le presse-papier
    function copyToken() {
        const tokenInput = document.getElementById('new-token');
        tokenInput.select();
        document.execCommand('copy');
        
        // Changer le texte du bouton temporairement
        const copyButton = tokenInput.nextElementSibling;
        const originalText = copyButton.textContent;
        copyButton.textContent = 'Copié !';
        
        setTimeout(() => {
            copyButton.textContent = originalText;
        }, 2000);
    }
    
    // Fonction pour fermer la modale
    function closeModal() {
        document.getElementById('token-modal').classList.add('hidden');
    }
    
    // Confirmation de suppression
    function confirmDelete(button) {
        if (confirm('Êtes-vous sûr de vouloir supprimer ce jeton ? Toutes les applications utilisant ce jeton cesseront de fonctionner.')) {
            button.closest('form').submit();
        }
    }
</script>
@endpush
@endsection
