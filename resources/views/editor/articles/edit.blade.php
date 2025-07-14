@extends('layouts.editor')

@section('content')
<div class="min-h-screen bg-gray-50 py-8 px-4 sm:px-6 lg:px-8">
    <div class="max-w-5xl mx-auto">
        <!-- En-tête avec bouton de retour -->
        <div class="mb-6">
            <a href="{{ route('editor.articles.index') }}" class="inline-flex items-center text-blue-600 hover:text-blue-800 transition-colors duration-200">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Retour à la liste des articles
            </a>
        </div>
        
        <div class="bg-white shadow-xl rounded-xl overflow-hidden">
            <!-- En-tête avec dégradé -->
            <div class="bg-gradient-to-r from-blue-600 to-blue-800 px-6 py-5">
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="text-2xl font-bold text-white">
                            Modifier l'article
                        </h2>
                        <p class="mt-1 text-sm text-blue-100">
                            Modifiez les champs ci-dessous pour mettre à jour l'article
                        </p>
                    </div>
                    <div class="bg-white bg-opacity-20 p-2 rounded-lg">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                        </svg>
                    </div>
                </div>
            </div>
            
            <!-- Contenu du formulaire -->
            <div class="px-6 py-8 sm:p-8">
                <form action="{{ route('editor.articles.update', $article->id) }}" method="POST" enctype="multipart/form-data" class="space-y-8">
                    @csrf
                    @method('PUT')
                    
                    <!-- Section 1 : Informations de base -->
                    <div class="space-y-8">
                        <!-- Titre de l'article -->
                        <div class="space-y-2">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 bg-blue-100 p-1 rounded-full">
                                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                    </svg>
                                </div>
                                <h3 class="ml-3 text-lg font-medium text-gray-900">Informations de base</h3>
                            </div>
                            
                            <div class="mt-4 space-y-6 pl-9">
                                <!-- Champ Titre -->
                                <div class="space-y-2">
                                    <label for="titre" class="block text-sm font-medium text-gray-700">
                                        Titre de l'article <span class="text-red-500">*</span>
                                    </label>
                                    <div class="mt-1 relative rounded-md shadow-sm">
                                        <input type="text" name="titre" id="titre" 
                                               class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition duration-200 sm:text-sm px-4 py-3 border"
                                               value="{{ old('titre', $article->titre) }}" 
                                               placeholder="Donnez un titre percutant à votre article"
                                               required>
                                    </div>
                                    @error('titre')
                                        <p class="mt-1 text-sm text-red-600 flex items-center">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                            {{ $message }}
                                        </p>
                                    @enderror
                                </div>
                                
                                <!-- Slug (généré automatiquement) -->
                                <input type="hidden" name="slug" id="slug" value="{{ old('slug', $article->slug) }}">
                                
                                <!-- Catégorie -->
                                <div class="space-y-2">
                                    <label for="categorie_id" class="block text-sm font-medium text-gray-700">
                                        Catégorie <span class="text-red-500">*</span>
                                    </label>
                                    <div class="mt-1">
                                        <select id="categorie_id" name="categorie_id" 
                                                class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition duration-200 sm:text-sm px-4 py-3 border appearance-none bg-white bg-[url(&quot;data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%236b7280' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='M6 8l4 4 4-4'/%3e%3c/svg%3e")] bg-[right_0.5rem_center] bg-[length:1.5em_1.5em] bg-no-repeat"
                                                required>
                                            <option value="">Sélectionnez une catégorie</option>
                                            @foreach($categories as $categorie)
                                                <option value="{{ $categorie->id }}" {{ old('categorie_id', $article->categorie_id) == $categorie->id ? 'selected' : '' }}>
                                                    {{ $categorie->nom }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    @error('categorie_id')
                                        <p class="mt-1 text-sm text-red-600 flex items-center">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                            {{ $message }}
                                        </p>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        
                        <!-- Section 2 : Contenu -->
                        <div class="space-y-2">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 bg-purple-100 p-1 rounded-full">
                                    <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"></path>
                                    </svg>
                                </div>
                                <h3 class="ml-3 text-lg font-medium text-gray-900">Contenu de l'article</h3>
                            </div>
                            
                            <div class="mt-4 space-y-6 pl-9">
                                <!-- Contenu -->
                                <div class="space-y-2">
                                    <div class="flex items-center justify-between">
                                        <label for="contenu" class="block text-sm font-medium text-gray-700">
                                            Contenu <span class="text-red-500">*</span>
                                        </label>
                                        <span class="text-xs text-gray-500">Minimum 100 caractères</span>
                                    </div>
                                    <div class="mt-1">
                                        <textarea id="contenu" name="contenu" rows="12" 
                                                  class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition duration-200 sm:text-sm p-4 border"
                                                  placeholder="Écrivez votre contenu ici..."
                                                  required>{{ old('contenu', $article->contenu) }}</textarea>
                                    </div>
                                    @error('contenu')
                                        <p class="mt-1 text-sm text-red-600 flex items-center">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                            {{ $message }}
                                        </p>
                                    @enderror
                                </div>
                                
                                <!-- Résumé -->
                                <div class="space-y-2">
                                    <label for="resume" class="block text-sm font-medium text-gray-700">
                                        Résumé
                                    </label>
                                    <div class="mt-1">
                                        <textarea id="resume" name="resume" rows="3"
                                               class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition duration-200 sm:text-sm p-4 border"
                                               placeholder="Un court résumé de l'article (facultatif)">{{ old('resume', $article->resume) }}</textarea>
                                    </div>
                                    @error('resume')
                                        <p class="mt-1 text-sm text-red-600 flex items-center">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                            {{ $message }}
                                        </p>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        
                        <!-- Section 3 : Médias -->
                        <div class="space-y-2">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 bg-green-100 p-1 rounded-full">
                                    <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                </div>
                                <h3 class="ml-3 text-lg font-medium text-gray-900">Médias</h3>
                            </div>
                            
                            <div class="mt-4 space-y-6 pl-9">
                                <!-- Image de couverture -->
                                <div class="space-y-2">
                                    <label class="block text-sm font-medium text-gray-700">
                                        Image de couverture
                                    </label>
                                    
                                    @if($article->image)
                                        <div class="mt-2">
                                            <img src="{{ asset('storage/' . $article->image) }}" alt="Image actuelle" class="h-32 w-auto rounded-lg object-cover">
                                            <div class="mt-2 flex items-center">
                                                <input type="checkbox" name="remove_image" id="remove_image" class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                                <label for="remove_image" class="ml-2 block text-sm text-gray-700">
                                                    Supprimer l'image actuelle
                                                </label>
                                            </div>
                                        </div>
                                        <p class="mt-2 text-sm text-gray-500">
                                            Ou téléchargez une nouvelle image pour la remplacer :
                                        </p>
                                    @endif
                                    
                                    <div class="mt-1 flex items-center">
                                        <div class="relative group w-full">
                                            <div class="flex flex-col items-center justify-center pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-lg group-hover:border-blue-500 transition-colors duration-200">
                                                <svg class="w-10 h-10 mb-3 text-gray-400 group-hover:text-blue-500 transition-colors duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                                </svg>
                                                <p class="mb-2 text-sm text-gray-500">
                                                    <span class="font-semibold">Cliquez pour télécharger</span> ou glissez-déposez
                                                </p>
                                                <p class="text-xs text-gray-500">
                                                    PNG, JPG, GIF (MAX. 2MB)
                                                </p>
                                                <input type="file" name="image" id="image" 
                                                       class="absolute inset-0 w-full h-full opacity-0 cursor-pointer"
                                                       accept="image/*">
                                            </div>
                                        </div>
                                    </div>
                                    @error('image')
                                        <p class="mt-1 text-sm text-red-600 flex items-center">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                            {{ $message }}
                                        </p>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        
                        <!-- Boutons d'action -->
                        <div class="pt-8 border-t border-gray-200">
                            <div class="flex justify-between">
                                <button type="button" onclick="window.history.back()" class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                    Annuler
                                </button>
                                <div class="space-x-3">
                                    <button type="submit" name="action" value="draft" class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                        Enregistrer comme brouillon
                                    </button>
                                    <button type="submit" name="action" value="publish" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                        <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                        </svg>
                                        Mettre à jour l'article
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Aperçu du nom du fichier -->
<script>
    document.getElementById('image').addEventListener('change', function(e) {
        const fileName = e.target.files[0]?.name || 'Aucun fichier sélectionné';
        const fileInfo = document.createElement('div');
        fileInfo.className = 'mt-2 text-sm text-gray-600 flex items-center';
        fileInfo.innerHTML = `
            <svg class="w-4 h-4 mr-2 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            ${fileName}
        `;
        
        // Supprime l'ancien message s'il existe
        const oldFileInfo = document.querySelector('.file-info-message');
        if (oldFileInfo) {
            oldFileInfo.remove();
        }
        
        fileInfo.classList.add('file-info-message');
        this.parentNode.parentNode.appendChild(fileInfo);
    });
    
    // Génération automatique du slug à partir du titre
    document.getElementById('titre').addEventListener('input', function() {
        const slug = this.value
            .toLowerCase()
            .trim()
            .replace(/[^\w\s-]/g, '') // Supprime les caractères spéciaux
            .replace(/[\s_-]+/g, '-') // Remplace les espaces et tirets par un seul tiret
            .replace(/^-+|-+$/g, ''); // Supprime les tirets en début et fin
            
        document.getElementById('slug').value = slug;
    });
</script>

@push('styles')
<style>
    /* Style personnalisé pour le champ de sélection */
    select {
        -webkit-appearance: none;
        -moz-appearance: none;
        appearance: none;
    }
    
    /* Style pour le champ de fichier */
    .file-upload {
        position: relative;
        overflow: hidden;
        display: inline-block;
    }
    
    .file-upload-input {
        position: absolute;
        left: 0;
        top: 0;
        opacity: 0;
        cursor: pointer;
        height: 100%;
        width: 100%;
    }
</style>
@endpush
@endsection
