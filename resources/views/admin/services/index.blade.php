@extends('layouts.admin')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Services API</h5>
                    <a href="{{ route('admin.dashboard') }}" class="btn btn-sm btn-secondary">
                        <i class="fas fa-arrow-left"></i> Retour
                    </a>
                </div>

                <div class="card-body">
                    @foreach ($services as $service)
                        <div class="card mb-4">
                            <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                                <h5 class="mb-0">{{ $service['name'] }}</h5>
                                <a href="{{ $service['documentation_url'] }}" class="btn btn-sm btn-light" target="_blank">
                                    <i class="fas fa-book"></i> Documentation
                                </a>
                            </div>
                            <div class="card-body">
                                <p class="card-text">{{ $service['description'] }}</p>
                                
                                <h6 class="mt-4 mb-3">Endpoints disponibles :</h6>
                                <div class="table-responsive">
                                    <table class="table table-bordered table-striped">
                                        <thead class="thead-light">
                                            <tr>
                                                <th>Méthode</th>
                                                <th>Chemin</th>
                                                <th>Description</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($service['endpoints'] as $endpoint)
                                                <tr>
                                                    <td>
                                                        <span class="badge 
                                                            {{ $endpoint['method'] === 'GET' ? 'bg-primary' : '' }}
                                                            {{ $endpoint['method'] === 'POST' ? 'bg-success' : '' }}
                                                            {{ $endpoint['method'] === 'PUT' ? 'bg-warning text-dark' : '' }}
                                                            {{ $endpoint['method'] === 'DELETE' ? 'bg-danger' : '' }}
                                                            ">
                                                            {{ $endpoint['method'] }}
                                                        </span>
                                                    </td>
                                                    <td><code>{{ $endpoint['path'] }}</code></td>
                                                    <td>{{ $endpoint['description'] }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    @endforeach

                    <div class="card">
                        <div class="card-header bg-info text-white">
                            <h5 class="mb-0">Documentation de l'API</h5>
                        </div>
                        <div class="card-body">
                            <p>Consultez la documentation complète de l'API pour plus de détails sur son utilisation :</p>
                            <a href="{{ url('/api/documentation') }}" class="btn btn-primary" target="_blank">
                                <i class="fas fa-book-open"></i> Accéder à la documentation
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .badge {
        font-size: 0.8rem;
        padding: 0.35em 0.65em;
        font-weight: 600;
    }
    .card {
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
        margin-bottom: 1.5rem;
        border: none;
    }
    .card-header {
        font-weight: 600;
    }
    .table th {
        font-weight: 600;
        background-color: #f8f9fa;
    }
    code {
        color: #e83e8c;
        background-color: #f8f9fa;
        padding: 0.2rem 0.4rem;
        border-radius: 0.25rem;
    }
</style>
@endpush
