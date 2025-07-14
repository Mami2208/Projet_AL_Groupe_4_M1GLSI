@extends('layouts.admin')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Gestion des jetons d'API</h5>
                    <a href="{{ route('admin.dashboard') }}" class="btn btn-sm btn-secondary">
                        <i class="fas fa-arrow-left"></i> Retour
                    </a>
                </div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <div class="mb-4">
                        <h6>Générer un nouveau jeton</h6>
                        <form method="POST" action="{{ route('admin.api.tokens.create') }}">
                            @csrf
                            <div class="form-row align-items-center">
                                <div class="col-md-6">
                                    <input type="text" name="name" class="form-control" placeholder="Nom du jeton" required>
                                </div>
                                <div class="col-md-4">
                                    <select name="abilities[]" class="form-control" multiple>
                                        <option value="users:read">Lire les utilisateurs</option>
                                        <option value="users:write">Écrire les utilisateurs</option>
                                        <option value="articles:read">Lire les articles</option>
                                        <option value="articles:write">Écrire les articles</option>
                                    </select>
                                    <small class="form-text text-muted">Maintenez Ctrl (ou Cmd) pour sélectionner plusieurs permissions</small>
                                </div>
                                <div class="col-md-2">
                                    <button type="submit" class="btn btn-primary btn-block">
                                        <i class="fas fa-plus"></i> Créer
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>

                    <hr>

                    <h6>Jetons existants</h6>
                    @if($tokens->isEmpty())
                        <div class="alert alert-info">
                            Aucun jeton n'a été généré pour le moment.
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Nom</th>
                                        <th>Dernière utilisation</th>
                                        <th>Créé le</th>
                                        <th>Permissions</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($tokens as $token)
                                        <tr>
                                            <td>{{ $token->name }}</td>
                                            <td>{{ $token->last_used_at ? $token->last_used_at->diffForHumans() : 'Jamais' }}</td>
                                            <td>{{ $token->created_at->format('d/m/Y H:i') }}</td>
                                            <td>
                                                @foreach($token->abilities as $ability)
                                                    <span class="badge badge-info">{{ $ability }}</span>
                                                @endforeach
                                            </td>
                                            <td>
                                                <form method="POST" action="{{ route('admin.api.tokens.delete', $token->id) }}" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce jeton ?')">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                                
                                                <button class="btn btn-sm btn-info show-token" data-token="{{ $token->token }}">
                                                    <i class="fas fa-eye"></i> Voir le jeton
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal pour afficher le jeton -->
<div class="modal fade" id="tokenModal" tabindex="-1" role="dialog" aria-labelledby="tokenModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="tokenModalLabel">Votre jeton d'API</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="alert alert-warning">
                    <strong>Attention :</strong> Ce jeton ne sera affiché qu'une seule fois. Assurez-vous de le copier et de le stocker en lieu sûr.
                </div>
                <div class="form-group">
                    <label>Votre jeton d'API :</label>
                    <div class="input-group">
                        <input type="text" id="apiToken" class="form-control" readonly>
                        <div class="input-group-append">
                            <button class="btn btn-outline-secondary" type="button" id="copyTokenBtn" data-toggle="tooltip" title="Copier dans le presse-papier">
                                <i class="fas fa-copy"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Fermer</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        // Afficher le jeton dans la modale
        $('.show-token').click(function() {
            var token = $(this).data('token');
            $('#apiToken').val(token);
            $('#tokenModal').modal('show');
        });

        // Copier le jeton dans le presse-papier
        $('#copyTokenBtn').click(function() {
            var copyText = document.getElementById("apiToken");
            copyText.select();
            copyText.setSelectionRange(0, 99999);
            document.execCommand("copy");
            
            // Afficher un message de confirmation
            var originalTitle = $(this).attr('title');
            $(this).attr('title', 'Copié !').tooltip('_fixTitle').tooltip('show');
            
            // Réinitialiser le titre après 2 secondes
            setTimeout(function() {
                $('#copyTokenBtn').attr('title', originalTitle).tooltip('_fixTitle');
            }, 2000);
        });

        // Initialiser les tooltips
        $('[data-toggle="tooltip"]').tooltip();
    });
</script>
@endpush
