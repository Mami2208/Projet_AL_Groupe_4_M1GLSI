<!-- Sidebar -->
<div class="sidebar
    @if(session('sidebar_collapsed') === 'true') collapsed @endif
    @if(Agent::isMobile() && !session('sidebar_force_show')) d-none @endif" 
    id="sidebar">
    <div class="sidebar-inner">
        <!-- Logo -->
        <div class="sidebar-logo">
            <a href="{{ route('admin.dashboard') }}" class="d-flex align-items-center">
                <img src="{{ asset('images/logo.png') }}" alt="Logo" class="sidebar-logo-img">
                <span class="sidebar-logo-text">{{ config('app.name') }}</span>
            </a>
            <button class="sidebar-toggle d-lg-none" id="sidebarToggle">
                <i class="fas fa-times"></i>
            </button>
        </div>

        <!-- Menu de navigation -->
        <div class="sidebar-menu">
            <ul class="nav flex-column">
                <!-- Tableau de bord -->
                <li class="nav-item">
                    <a href="{{ route('admin.dashboard') }}" class="nav-link @if(request()->routeIs('admin.dashboard')) active @endif">
                        <i class="fas fa-tachometer-alt me-2"></i>
                        <span>Tableau de bord</span>
                    </a>
                </li>

                <!-- Articles -->
                <li class="nav-item">
                    <a href="{{ route('admin.articles.index') }}" class="nav-link @if(request()->routeIs('admin.articles.*')) active @endif">
                        <i class="fas fa-newspaper me-2"></i>
                        <span>Articles</span>
                    </a>
                </li>

                <!-- Catégories -->
                <li class="nav-item">
                    <a href="{{ route('admin.categories.index') }}" class="nav-link @if(request()->routeIs('admin.categories.*')) active @endif">
                        <i class="fas fa-tags me-2"></i>
                        <span>Catégories</span>
                    </a>
                </li>

                <!-- Utilisateurs -->
                <li class="nav-item">
                    <a href="{{ route('admin.users.index') }}" class="nav-link @if(request()->routeIs('admin.users.*')) active @endif">
                        <i class="fas fa-users me-2"></i>
                        <span>Utilisateurs</span>
                    </a>
                </li>

                <!-- Rôles et permissions -->
                <li class="nav-item">
                    <a href="#rolePermissionMenu" data-bs-toggle="collapse" class="nav-link @if(request()->routeIs('admin.roles.*') || request()->routeIs('admin.permissions.*')) active @endif">
                        <i class="fas fa-user-shield me-2"></i>
                        <span>Sécurité</span>
                        <i class="fas fa-chevron-right float-end mt-1"></i>
                    </a>
                    <div class="collapse @if(request()->routeIs('admin.roles.*') || request()->routeIs('admin.permissions.*')) show @endif" id="rolePermissionMenu">
                        <ul class="nav flex-column ms-4">
                            <li class="nav-item">
                                <a href="{{ route('admin.roles.index') }}" class="nav-link @if(request()->routeIs('admin.roles.*')) active @endif">
                                    <i class="fas fa-user-tag me-2"></i>
                                    <span>Rôles</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('admin.permissions.index') }}" class="nav-link @if(request()->routeIs('admin.permissions.*')) active @endif">
                                    <i class="fas fa-key me-2"></i>
                                    <span>Permissions</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>

                <!-- API -->
                <li class="nav-item">
                    <a href="#apiMenu" data-bs-toggle="collapse" class="nav-link @if(request()->routeIs('admin.api-tokens.*') || request()->routeIs('admin.services.*')) active @endif">
                        <i class="fas fa-plug me-2"></i>
                        <span>API</span>
                        <i class="fas fa-chevron-right float-end mt-1"></i>
                    </a>
                    <div class="collapse @if(request()->routeIs('admin.api-tokens.*') || request()->routeIs('admin.services.*')) show @endif" id="apiMenu">
                        <ul class="nav flex-column ms-4">
                            <li class="nav-item">
                                <a href="{{ route('admin.api-tokens.index') }}" class="nav-link @if(request()->routeIs('admin.api-tokens.*')) active @endif">
                                    <i class="fas fa-key me-2"></i>
                                    <span>Jetons API</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('admin.services.index') }}" class="nav-link @if(request()->routeIs('admin.services.*')) active @endif">
                                    <i class="fas fa-server me-2"></i>
                                    <span>Services</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>

                <!-- Documentation -->
                <li class="nav-item">
                    <a href="{{ url('/api/documentation') }}" target="_blank" class="nav-link">
                        <i class="fas fa-book me-2"></i>
                        <span>Documentation API</span>
                        <i class="fas fa-external-link-alt ms-2 small"></i>
                    </a>
                </li>
            </ul>
        </div>

        <!-- Utilisateur connecté -->
        <div class="sidebar-footer">
            <div class="user-info">
                <div class="user-avatar">
                    <img src="{{ Auth::user()->avatar_url ?? 'https://www.gravatar.com/avatar/' . md5(strtolower(trim(Auth::user()->email))) . '?d=mp&s=80' }}" 
                         alt="{{ Auth::user()->name }}" 
                         class="rounded-circle">
                </div>
                <div class="user-details">
                    <h6 class="mb-0">{{ Auth::user()->name }}</h6>
                    <small class="text-muted">{{ ucfirst(Auth::user()->role) }}</small>
                </div>
                <div class="user-actions">
                    <a href="{{ route('profile.show') }}" class="btn btn-sm btn-outline-light" title="Profil">
                        <i class="fas fa-user-cog"></i>
                    </a>
                    <form method="POST" action="{{ route('logout') }}" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-sm btn-outline-light" title="Déconnexion">
                            <i class="fas fa-sign-out-alt"></i>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Overlay pour les mobiles -->
<div class="sidebar-overlay @if(session('sidebar_force_show')) show @endif" id="sidebarOverlay"></div>

<!-- Bouton pour afficher/masquer la sidebar sur mobile -->
<button class="btn btn-primary sidebar-mobile-toggle d-lg-none" id="mobileSidebarToggle">
    <i class="fas fa-bars"></i>
</button>

@push('scripts')
<script>
    // Gestion de l'affichage/masquage de la sidebar sur mobile
    document.addEventListener('DOMContentLoaded', function() {
        const sidebar = document.getElementById('sidebar');
        const sidebarToggle = document.getElementById('sidebarToggle');
        const mobileSidebarToggle = document.getElementById('mobileSidebarToggle');
        const sidebarOverlay = document.getElementById('sidebarOverlay');
        
        // Afficher/masquer la sidebar sur mobile
        function toggleSidebar(show) {
            if (show) {
                sidebar.classList.add('show');
                sidebarOverlay.classList.add('show');
                document.body.style.overflow = 'hidden';
                // Enregistrer l'état dans sessionStorage
                sessionStorage.setItem('sidebar_force_show', 'true');
            } else {
                sidebar.classList.remove('show');
                sidebarOverlay.classList.remove('show');
                document.body.style.overflow = '';
                // Enregistrer l'état dans sessionStorage
                sessionStorage.setItem('sidebar_force_show', 'false');
            }
        }
        
        // Bouton de bascule sur mobile
        if (mobileSidebarToggle) {
            mobileSidebarToggle.addEventListener('click', function() {
                const isVisible = sidebar.classList.contains('show');
                toggleSidebar(!isVisible);
            });
        }
        
        // Bouton de fermeture dans la sidebar
        if (sidebarToggle) {
            sidebarToggle.addEventListener('click', function() {
                toggleSidebar(false);
            });
        }
        
        // Clic sur l'overlay pour fermer la sidebar
        if (sidebarOverlay) {
            sidebarOverlay.addEventListener('click', function() {
                toggleSidebar(false);
            });
        }
        
        // Gestion des sous-menus
        const hasActiveSubmenu = document.querySelector('.sidebar-menu .nav-link.active') !== null;
        if (hasActiveSubmenu) {
            const activeLink = document.querySelector('.sidebar-menu .nav-link.active');
            const parentCollapse = activeLink.closest('.collapse');
            if (parentCollapse) {
                parentCollapse.classList.add('show');
            }
        }
        
        // Gestion du redimensionnement de la fenêtre
        function handleResize() {
            if (window.innerWidth >= 992) {
                // Sur desktop, toujours afficher la sidebar
                sidebar.classList.remove('d-none');
                sidebarOverlay.classList.remove('show');
                document.body.style.overflow = '';
            } else {
                // Sur mobile, cacher la sidebar sauf si explicitement affichée
                const forceShow = sessionStorage.getItem('sidebar_force_show') === 'true';
                if (!forceShow) {
                    sidebar.classList.add('d-none');
                }
            }
        }
        
        // Écouter les changements de taille de fenêtre
        window.addEventListener('resize', handleResize);
        
        // Initialiser l'état au chargement
        handleResize();
        
        // Vérifier l'état de la sidebar au chargement
        const forceShow = sessionStorage.getItem('sidebar_force_show') === 'true';
        if (forceShow) {
            toggleSidebar(true);
        }
    });
</script>
@endpush

@push('styles')
<style>
    .sidebar {
        width: 250px;
        height: 100vh;
        position: fixed;
        top: 0;
        left: 0;
        z-index: 1040;
        background-color: #2c3e50;
        color: #ecf0f1;
        transition: all 0.3s ease;
        overflow-y: auto;
    }
    
    .sidebar.collapsed {
        width: 70px;
        overflow: hidden;
    }
    
    .sidebar.collapsed .sidebar-logo-text,
    .sidebar.collapsed .nav-link span,
    .sidebar.collapsed .user-details,
    .sidebar.collapsed .user-actions {
        display: none;
    }
    
    .sidebar.collapsed .nav-link {
        text-align: center;
        padding: 0.75rem 0.5rem;
    }
    
    .sidebar.collapsed .nav-link i {
        margin-right: 0;
        font-size: 1.25rem;
    }
    
    .sidebar-logo {
        padding: 1rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
        border-bottom: 1px solid rgba(255, 255, 255, 0.1);
    }
    
    .sidebar-logo-img {
        height: 30px;
        margin-right: 10px;
    }
    
    .sidebar-logo-text {
        font-weight: 600;
        font-size: 1.1rem;
        color: #fff;
    }
    
    .sidebar-toggle {
        background: none;
        border: none;
        color: #ecf0f1;
        font-size: 1.25rem;
        padding: 0.25rem 0.5rem;
        cursor: pointer;
    }
    
    .sidebar-menu {
        padding: 1rem 0;
    }
    
    .nav-link {
        color: #b8c7ce;
        padding: 0.75rem 1.5rem;
        display: flex;
        align-items: center;
        transition: all 0.3s;
    }
    
    .nav-link:hover, .nav-link.active {
        color: #fff;
        background-color: rgba(255, 255, 255, 0.1);
        text-decoration: none;
    }
    
    .nav-link i {
        width: 20px;
        text-align: center;
        margin-right: 10px;
    }
    
    .sidebar-footer {
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        padding: 1rem;
        border-top: 1px solid rgba(255, 255, 255, 0.1);
    }
    
    .user-info {
        display: flex;
        align-items: center;
    }
    
    .user-avatar img {
        width: 40px;
        height: 40px;
        object-fit: cover;
    }
    
    .user-details {
        margin-left: 10px;
        flex-grow: 1;
    }
    
    .user-details h6 {
        margin: 0;
        font-size: 0.9rem;
        color: #fff;
    }
    
    .user-details small {
        font-size: 0.75rem;
    }
    
    .user-actions {
        display: flex;
        gap: 5px;
    }
    
    .user-actions .btn {
        padding: 0.25rem 0.5rem;
        font-size: 0.8rem;
    }
    
    /* Sous-menus */
    .nav.flex-column .nav-link {
        padding-left: 2.5rem;
        font-size: 0.9rem;
    }
    
    /* Style pour les flèches des menus déroulants */
    .nav-link[data-bs-toggle="collapse"]::after {
        display: inline-block;
        margin-left: auto;
        transition: transform 0.2s;
    }
    
    .nav-link[data-bs-toggle="collapse"][aria-expanded="true"]::after {
        transform: rotate(90deg);
    }
    
    /* Style pour la version mobile */
    @media (max-width: 991.98px) {
        .sidebar {
            transform: translateX(-100%);
            z-index: 1050;
        }
        
        .sidebar.show {
            transform: translateX(0);
        }
        
        .sidebar-overlay {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 1040;
            opacity: 0;
            visibility: hidden;
            transition: opacity 0.3s, visibility 0.3s;
        }
        
        .sidebar-overlay.show {
            opacity: 1;
            visibility: visible;
        }
        
        .sidebar-mobile-toggle {
            position: fixed;
            top: 1rem;
            left: 1rem;
            z-index: 1030;
            display: flex;
            align-items: center;
            justify-content: center;
            width: 40px;
            height: 40px;
            padding: 0;
            border-radius: 50%;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);
        }
    }
    
    /* Style pour les écrans larges */
    @media (min-width: 992px) {
        .sidebar-overlay {
            display: none !important;
        }
        
        .sidebar-mobile-toggle {
            display: none !important;
        }
    }
</style>
@endpush
