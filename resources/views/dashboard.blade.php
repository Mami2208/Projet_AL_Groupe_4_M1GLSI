@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                <h2 class="text-2xl font-semibold text-gray-800">Tableau de bord</h2>
                <p class="mt-2 text-gray-600">Bienvenue sur votre tableau de bord.</p>
                
                @auth
                    @if(auth()->user()->role === 'admin')
                        <div class="mt-4">
                            <a href="{{ route('admin.dashboard') }}" class="text-blue-600 hover:text-blue-800">
                                Accéder au panneau d'administration →
                            </a>
                        </div>
                    @endif
                @endauth
            </div>
        </div>
    </div>
</div>
@endsection
