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
                        <h2 class="text-2xl font-bold text-white">Modifier la catégorie</h2>
                        <p class="mt-1 text-blue-100">Mettez à jour les informations de la catégorie</p>
                    </div>
                </div>
            </div>
            
            <div class="px-6 py-8 sm:p-8">
                <form action="{{ route('editor.categories.update', $categorie) }}" method="POST" class="space-y-6">
                    @csrf
                    @method('PUT')
                    
                    <!-- Nom -->
                    <div class="space-y-2">
                        <label for="nom" class="block text-sm font-medium text-gray-700">
                            Nom de la catégorie <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="nom" id="nom" value="{{ old('nom', $categorie->nom) }}" 
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm @error('nom') border-red-500 @enderror"
                               required>
                        @error('nom')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <!-- Description -->
                    <div class="space-y-2">
                        <label for="description" class="block text-sm font-medium text-gray-700">
                            Description
                        </label>
                        <textarea name="description" id="description" rows="3"
                                  class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">{{ old('description', $categorie->description) }}</textarea>
                    </div>
                    
                    <!-- Couleur -->
                    <div class="space-y-2">
                        <label for="couleur" class="block text-sm font-medium text-gray-700">
                            Couleur d'identification <span class="text-red-500">*</span>
                        </label>
                        <div class="mt-1 grid grid-cols-8 gap-2">
                            @php
                                $couleurs = [
                                    'bg-red-500' => '#EF4444',
                                    'bg-pink-500' => '#EC4899',
                                    'bg-purple-500' => '#A855F7',
                                    'bg-indigo-500' => '#6366F1',
                                    'bg-blue-500' => '#3B82F6',
                                    'bg-cyan-500' => '#06B6D4',
                                    'bg-teal-500' => '#14B8A6',
                                    'bg-green-500' => '#10B981',
                                    'bg-lime-500' => '#84CC16',
                                    'bg-yellow-500' => '#EAB308',
                                    'bg-amber-500' => '#F59E0B',
                                    'bg-orange-500' => '#F97316',
                                    'bg-gray-500' => '#6B7280',
                                    'bg-slate-500' => '#64748B',
                                    'bg-zinc-500' => '#71717A',
                                    'bg-neutral-500' => '#737373',
                                ];
                            @endphp
                            
                            @foreach($couleurs as $classe => $valeur)
                                <div>
                                    <input type="radio" name="couleur" id="couleur-{{ $loop->index }}" 
                                           value="{{ $valeur }}" class="sr-only"
                                           {{ old('couleur', $categorie->couleur) === $valeur ? 'checked' : '' }}>
                                    <label for="couleur-{{ $loop->index }}" 
                                           class="h-8 w-full rounded-md cursor-pointer flex items-center justify-center {{ $classe }} text-white hover:opacity-80 transition-opacity">
                                        <span class="sr-only">{{ $valeur }}</span>
                                    </label>
                                </div>
                            @endforeach
                        </div>
                        @error('couleur')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div class="flex justify-end space-x-3 pt-4">
                        <a href="{{ route('editor.categories.index') }}" 
                           class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            Annuler
                        </a>
                        <button type="submit" 
                                class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            Enregistrer les modifications
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Script pour la sélection de couleur -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const colorRadios = document.querySelectorAll('input[type="radio"][name="couleur"]');
        
        colorRadios.forEach(radio => {
            radio.addEventListener('change', function() {
                // Mettre à jour la bordure de sélection
                document.querySelectorAll('label[for^="couleur-"]').forEach(label => {
                    label.classList.remove('ring-2', 'ring-offset-2', 'ring-blue-500');
                });
                
                if (this.checked) {
                    const label = document.querySelector(`label[for="${this.id}"]`);
                    label.classList.add('ring-2', 'ring-offset-2', 'ring-blue-500');
                }
            });
            
            // Initialiser la sélection au chargement de la page
            if (radio.checked) {
                const label = document.querySelector(`label[for="${radio.id}"]`);
                label.classList.add('ring-2', 'ring-offset-2', 'ring-blue-500');
            }
        });
    });
</script>
@endsection
