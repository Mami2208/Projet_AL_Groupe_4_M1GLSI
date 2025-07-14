@extends('layouts.editor')

@section('content')
<div class="min-h-screen bg-gray-50 py-8 px-4 sm:px-6 lg:px-8">
    <div class="max-w-5xl mx-auto">
        <!-- En-tête avec bouton de retour -->
        <div class="mb-6">
            <a href="{{ route('editor.dashboard') }}" class="inline-flex items-center text-blue-600 hover:text-blue-800 transition-colors duration-200">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Retour au tableau de bord
            </a>
        </div>
        
        <div class="bg-white shadow-xl rounded-xl overflow-hidden">
            <!-- En-tête avec dégradé -->
            <div class="bg-gradient-to-r from-blue-600 to-blue-800 px-6 py-5">
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="text-2xl font-bold text-white">
                            Nouvel article
                        </h2>
                        <p class="mt-1 text-sm text-blue-100">
                            Remplissez les champs ci-dessous pour créer un nouvel article
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
                <form action="{{ route('editor.articles.store') }}" method="POST" enctype="multipart/form-data" class="space-y-8">
                    @csrf
                    
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
                                               value="{{ old('titre') }}" 
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
                                <input type="hidden" name="slug" id="slug" value="{{ old('slug') }}">
                                
                                <!-- Catégorie -->
                                <div class="space-y-2">
                                    <label for="categorie_id" class="block text-sm font-medium text-gray-700">
                                        Catégorie <span class="text-red-500">*</span>
                                    </label>
                                    <div class="mt-1 relative">
                                        <select id="categorie_id" name="categorie_id" 
                                                class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition duration-200 sm:text-sm pl-4 pr-10 py-3 border appearance-none bg-white"
                                                required>
                                            <option value="">Sélectionnez une catégorie</option>
                                            @foreach($categories as $id => $categorie)
                                                <option value="{{ $id }}" {{ old('categorie_id') == $id ? 'selected' : '' }} 
                                                        data-color="{{ $categorie['couleur'] ?? '#6b7280' }}"
                                                        class="flex items-center">
                                                    <span class="inline-block w-3 h-3 rounded-full mr-2" style="background-color: {{ $categorie['couleur'] ?? '#6b7280' }}"></span>
                                                    {{ $categorie['nom'] }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                            <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                                <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 11.168l3.71-3.938a.75.75 0 111.08 1.04l-4.25 4.5a.75.75 0 01-1.08 0l-4.25-4.5a.75.75 0 01.02-1.06z" clip-rule="evenodd" />
                                            </svg>
                                        </div>
                                    </div>
                                    <style>
                                        select option {
                                            padding: 0.5rem 1rem;
                                        }
                                        select option:hover, select option:focus {
                                            background-color: #f3f4f6;
                                        }
                                    </style>
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
                                        <span class="text-xs text-gray-500">Minimum 300 caractères</span>
                                    </div>
                                    <div class="mt-1">
                                        <textarea id="contenu" name="contenu" rows="12" 
                                                  class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition duration-200 sm:text-sm p-4 border"
                                                  placeholder="Écrivez votre contenu ici..."
                                                  required>{{ old('contenu') }}</textarea>
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
                            </div>
                        </div>
                        
                        <!-- Section 3 : Médias et options -->
                        <div class="space-y-2">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 bg-green-100 p-1 rounded-full">
                                    <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                </div>
                                <h3 class="ml-3 text-lg font-medium text-gray-900">Médias et options</h3>
                            </div>
                            
                            <div class="mt-4 space-y-6 pl-9">
                        
                                <!-- Image de couverture -->
                                <div class="space-y-2">
                                    <label class="block text-sm font-medium text-gray-700">
                                        Image de couverture
                                    </label>
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
                                
                                <!-- Résumé -->
                                <div class="space-y-2">
                                    <label for="resume" class="block text-sm font-medium text-gray-700">
                                        Résumé
                                    </label>
                                    <div class="mt-1">
                                        <textarea id="resume" name="resume" rows="3"
                                               class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition duration-200 sm:text-sm p-4 border"
                                               placeholder="Un court résumé de l'article qui apparaîtra dans les aperçus">{{ old('resume') }}</textarea>
                                    </div>
                                    <p class="mt-1 text-xs text-gray-500">
                                        Ce résumé sera affiché dans les aperçus de l'article (500 caractères max).
                                    </p>
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
                        
                        <!-- Boutons d'action -->
                        <div class="pt-8 border-t border-gray-200">
                            <div class="flex justify-between items-center">
                                <div class="flex items-center text-sm text-gray-500">
                                    <svg class="h-5 w-5 mr-2 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    <span>Les champs marqués d'un <span class="text-red-500">*</span> sont obligatoires</span>
                                </div>
                                <div class="flex space-x-3">
                                    <a href="{{ route('editor.dashboard') }}" 
                                       class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-200">
                                        Annuler
                                    </a>
                                    <button type="submit" name="action" value="draft"
                                            class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-colors duration-200">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                        </svg>
                                        Enregistrer comme brouillon
                                    </button>
                                    <button type="submit" name="action" value="publish"
                                            class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-lg text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-200">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                        </svg>
                                        Publier l'article
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Script pour le sélecteur de catégorie -->
<script>
    // Mise à jour de l'aperçu de la couleur de la catégorie sélectionnée
    const categorySelect = document.getElementById('categorie_id');
    const categoryColorPreview = document.createElement('div');
    categoryColorPreview.className = 'absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none';
    categoryColorPreview.innerHTML = '<span class="w-3 h-3 rounded-full"></span>';
    categorySelect.parentNode.insertBefore(categoryColorPreview, categorySelect.nextSibling);
    
    function updateCategoryColor() {
        const selectedOption = categorySelect.options[categorySelect.selectedIndex];
        const color = selectedOption.getAttribute('data-color') || '#6b7280';
        categoryColorPreview.querySelector('span').style.backgroundColor = color;
    }
    
    // Initialiser la couleur
    updateCategoryColor();
    
    // Mettre à jour la couleur lors du changement de sélection
    categorySelect.addEventListener('change', updateCategoryColor);

    // Aperçu du nom du fichier -->
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
</script>

@push('scripts')
<script>
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
@endpush
@endsection
