@extends('layouts.editor')

@section('content')
<div class="min-h-screen bg-gray-50 py-8 px-4 sm:px-6 lg:px-8">
    <div class="max-w-3xl mx-auto">
        <!-- En-tête avec bouton de retour -->
        <div class="mb-6">
            <a href="{{ route('editor.categories.index') }}" class="inline-flex items-center text-blue-600 hover:text-blue-800 transition-colors duration-200">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Retour à la liste des catégories
            </a>
        </div>
        
        <div class="bg-white shadow-xl rounded-xl overflow-hidden">
            <!-- En-tête avec dégradé -->
            <div class="bg-gradient-to-r from-blue-600 to-blue-800 px-6 py-5">
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="text-2xl font-bold text-white">
                            Nouvelle catégorie
                        </h2>
                        <p class="mt-1 text-sm text-blue-100">
                            Remplissez les champs pour créer une nouvelle catégorie
                        </p>
                    </div>
                    <div class="bg-white bg-opacity-20 p-2 rounded-lg">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                        </svg>
                    </div>
                </div>
            </div>
            
            <!-- Formulaire -->
            <div class="px-6 py-8 sm:p-8">
                <form action="{{ route('editor.categories.store') }}" method="POST" class="space-y-6">
                    @csrf
                    
                    <!-- Nom -->
                    <div class="space-y-2">
                        <label for="nom" class="block text-sm font-medium text-gray-700">
                            Nom de la catégorie <span class="text-red-500">*</span>
                        </label>
                        <div class="mt-1">
                            <input type="text" name="nom" id="nom" 
                                   class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition duration-200 sm:text-sm px-4 py-3 border"
                                   value="{{ old('nom') }}" 
                                   placeholder="Ex: Technologie, Santé, Éducation..."
                                   required>
                        </div>
                        @error('nom')
                            <p class="mt-1 text-sm text-red-600 flex items-center">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>
                    
                    <!-- Description -->
                    <div class="space-y-2">
                        <label for="description" class="block text-sm font-medium text-gray-700">
                            Description
                        </label>
                        <div class="mt-1">
                            <textarea id="description" name="description" rows="4"
                                      class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition duration-200 sm:text-sm p-4 border"
                                      placeholder="Décrivez brièvement cette catégorie...">{{ old('description') }}</textarea>
                        </div>
                        <p class="mt-1 text-xs text-gray-500">
                            Cette description aidera les utilisateurs à comprendre le contenu de cette catégorie.
                        </p>
                        @error('description')
                            <p class="mt-1 text-sm text-red-600 flex items-center">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>
                    
                    <!-- Couleur -->
                    <div class="space-y-2">
                        <label for="couleur" class="block text-sm font-medium text-gray-700">
                            Couleur d'identification
                        </label>
                        <div class="mt-1 grid grid-cols-8 gap-2">
                            @php
                                $colors = [
                                    'blue' => 'Bleu',
                                    'red' => 'Rouge',
                                    'green' => 'Vert',
                                    'yellow' => 'Jaune',
                                    'indigo' => 'Indigo',
                                    'purple' => 'Violet',
                                    'pink' => 'Rose',
                                    'gray' => 'Gris'
                                ];
                            @endphp
                            
                            @foreach($colors as $value => $label)
                                <div class="relative">
                                    <input type="radio" name="couleur" id="color-{{ $value }}" 
                                           value="{{ $value }}" 
                                           class="sr-only"
                                           {{ old('couleur', 'blue') === $value ? 'checked' : '' }}>
                                    <label for="color-{{ $value }}" 
                                           class="block w-10 h-10 rounded-full cursor-pointer bg-{{ $value }}-500 hover:opacity-75 transition-opacity duration-200 flex items-center justify-center">
                                        <svg class="w-5 h-5 text-white opacity-0 peer-checked:opacity-100" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                        </svg>
                                    </label>
                                    <span class="sr-only">{{ $label }}</span>
                                </div>
                            @endforeach
                        </div>
                        @error('couleur')
                            <p class="mt-1 text-sm text-red-600 flex items-center">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>
                    
                    <!-- Boutons d'action -->
                    <div class="pt-6 border-t border-gray-200">
                        <div class="flex justify-end space-x-3">
                            <a href="{{ route('editor.categories.index') }}" 
                               class="inline-flex items-center px-6 py-3 border border-gray-300 shadow-sm text-base font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-200">
                                Annuler
                            </a>
                            <button type="submit" 
                                    class="inline-flex items-center px-6 py-3 border border-transparent shadow-sm text-base font-medium rounded-lg text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-200">
                                <svg class="w-5 h-5 mr-2 -ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                Créer la catégorie
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Script pour la sélection de couleur -->
<script>
    document.querySelectorAll('input[type="radio"][name="couleur"]').forEach(radio => {
        radio.addEventListener('change', function() {
            // Supprimer la classe 'ring-2' de tous les labels
            document.querySelectorAll('label[for^="color-"]').forEach(label => {
                label.classList.remove('ring-2', 'ring-offset-2', 'ring-blue-500');
            });
            
            // Ajouter la classe 'ring-2' au label sélectionné
            if (this.checked) {
                const label = document.querySelector(`label[for="${this.id}"]`);
                label.classList.add('ring-2', 'ring-offset-2', 'ring-blue-500');
            }
        });
        
        // Initialiser l'état des boutons radio au chargement de la page
        if (radio.checked) {
            const label = document.querySelector(`label[for="${radio.id}"]`);
            label.classList.add('ring-2', 'ring-offset-2', 'ring-blue-500');
        }
    });
</script>
@endsection
