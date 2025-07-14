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
                        <h2 class="text-2xl font-bold text-white">Détails de la catégorie</h2>
                        <p class="mt-1 text-blue-100">Informations complètes sur la catégorie</p>
                    </div>
                    <div class="flex space-x-3">
                        <a href="{{ route('editor.categories.edit', $categorie) }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-blue-700 bg-blue-100 hover:bg-blue-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path>
                            </svg>
                            Modifier
                        </a>
                    </div>
                </div>
            </div>
            
            <div class="px-6 py-8 sm:p-8">
                <div class="space-y-6">
                    <!-- Nom -->
                    <div class="border-b border-gray-200 pb-5">
                        <h3 class="text-lg font-medium text-gray-900">Nom de la catégorie</h3>
                        <div class="mt-2 max-w-4xl text-sm text-gray-700">
                            <p>{{ $categorie->nom }}</p>
                        </div>
                    </div>
                    
                    <!-- Description -->
                    <div class="border-b border-gray-200 pb-5">
                        <h3 class="text-lg font-medium text-gray-900">Description</h3>
                        <div class="mt-2 max-w-4xl text-sm text-gray-700">
                            <p>{{ $categorie->description ?? 'Aucune description fournie' }}</p>
                        </div>
                    </div>
                    
                    <!-- Couleur -->
                    <div class="border-b border-gray-200 pb-5">
                        <h3 class="text-lg font-medium text-gray-900">Couleur d'identification</h3>
                        <div class="mt-2 flex items-center">
                            <span class="inline-block w-6 h-6 rounded-full mr-3" style="background-color: {{ $categorie->couleur }}"></span>
                            <span class="text-sm text-gray-700">{{ $categorie->couleur }}</span>
                        </div>
                    </div>
                    
                    <!-- Nombre d'articles -->
                    <div class="border-b border-gray-200 pb-5">
                        <h3 class="text-lg font-medium text-gray-900">Articles associés</h3>
                        <div class="mt-2">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                {{ $categorie->articles()->count() }} {{ Str::plural('article', $categorie->articles()->count()) }}
                            </span>
                        </div>
                    </div>
                    
                    <!-- Dates -->
                    <div class="sm:grid sm:grid-cols-3 sm:gap-4">
                        <div>
                            <h3 class="text-sm font-medium text-gray-500">Créé le</h3>
                            <div class="mt-1 text-sm text-gray-900">
                                {{ $categorie->created_at->format('d/m/Y H:i') }}
                            </div>
                        </div>
                        <div class="mt-3 sm:mt-0">
                            <h3 class="text-sm font-medium text-gray-500">Dernière mise à jour</h3>
                            <div class="mt-1 text-sm text-gray-900">
                                {{ $categorie->updated_at->format('d/m/Y H:i') }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
