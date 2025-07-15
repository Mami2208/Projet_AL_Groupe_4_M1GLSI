import tkinter as tk
from tkinter import ttk, messagebox
import sys
import os

# Assurer que les bibliothèques nécessaires sont installées
try:
    from suds.client import Client
    import requests
except ImportError:
    messagebox.showerror(
        "Dépendances manquantes", 
        "Les bibliothèques 'suds-py3' et 'requests' sont requises. Installez-les avec 'pip install suds-py3 requests'."
    )
    sys.exit(1)

from services import create_service
from dashboard import AdminDashboard

class LoginApp:
    """
    Application de connexion pour la gestion des utilisateurs
    """
    
    def __init__(self, root):
        """
        Initialise l'application de connexion
        
        Args:
            root: Fenêtre principale Tkinter
        """
        self.root = root
        self.root.title("Connexion - Gestion des Utilisateurs")
        self.root.geometry("400x350")  # Hauteur augmentée pour le choix d'API
        self.root.resizable(False, False)
        
        # Centrer la fenêtre
        self.center_window()
        
        # Service API - sera initialisé lors de la connexion
        self.api_service = None
        
        # Interface utilisateur
        self.setup_ui()
    
    def center_window(self):
        """Centre la fenêtre sur l'écran"""
        self.root.update_idletasks()
        width = self.root.winfo_width()
        height = self.root.winfo_height()
        x = (self.root.winfo_screenwidth() // 2) - (width // 2)
        y = (self.root.winfo_screenheight() // 2) - (height // 2)
        self.root.geometry(f'{width}x{height}+{x}+{y}')
    
    def setup_ui(self):
        """Configure l'interface utilisateur"""
        # Style
        style = ttk.Style()
        style.configure('TFrame', background='#f0f0f0')
        style.configure('TLabel', background='#f0f0f0')
        style.configure('TButton', font=('Arial', 10))
        
        # Frame principal
        main_frame = ttk.Frame(self.root, padding=20)
        main_frame.pack(fill=tk.BOTH, expand=True)
        
        # Titre
        title_label = ttk.Label(
            main_frame, 
            text="Gestion des Utilisateurs", 
            font=('Arial', 16, 'bold')
        )
        title_label.pack(pady=(0, 20))
        
        # Frame de connexion
        login_frame = ttk.Frame(main_frame, padding=10)
        login_frame.pack()
        
        # Email
        ttk.Label(login_frame, text="Email:").grid(row=0, column=0, sticky="w", pady=5)
        self.email_var = tk.StringVar()
        email_entry = ttk.Entry(login_frame, textvariable=self.email_var, width=30)
        email_entry.grid(row=0, column=1, pady=5)
        
        # Mot de passe
        ttk.Label(login_frame, text="Mot de passe:").grid(row=1, column=0, sticky="w", pady=5)
        self.password_var = tk.StringVar()
        ttk.Entry(login_frame, textvariable=self.password_var, show="*", width=30).grid(row=1, column=1, pady=5)
        
        # Type d'API (REST ou SOAP)
        ttk.Label(login_frame, text="Type d'API:").grid(row=2, column=0, sticky="w", pady=5)
        self.api_type_var = tk.StringVar(value="soap")
        api_frame = ttk.Frame(login_frame)
        api_frame.grid(row=2, column=1, sticky="w", pady=5)
        
        ttk.Radiobutton(
            api_frame, 
            text="SOAP", 
            variable=self.api_type_var, 
            value="soap"
        ).pack(side=tk.LEFT, padx=(0, 10))
        
        ttk.Radiobutton(
            api_frame, 
            text="REST", 
            variable=self.api_type_var, 
            value="rest"
        ).pack(side=tk.LEFT)
        
        # Bouton de connexion
        button_frame = ttk.Frame(main_frame)
        button_frame.pack(pady=20)
        
        ttk.Button(
            button_frame, 
            text="Se connecter", 
            command=self.login
        ).pack()
        
        # Message d'état
        self.status_var = tk.StringVar()
        ttk.Label(
            main_frame, 
            textvariable=self.status_var,
            foreground="red"
        ).pack(pady=10)
        
        # Focus sur le champ email
        self.root.after(100, lambda: email_entry.focus())
    
    def login(self):
        """Gère la connexion de l'utilisateur"""
        email = self.email_var.get()
        password = self.password_var.get()
        api_type = self.api_type_var.get()
        
        if not email or not password:
            self.status_var.set("Veuillez remplir tous les champs")
            return
        
        # Réinitialiser le message d'état
        self.status_var.set(f"Connexion en cours via {api_type.upper()}...")
        self.root.update()
        
        try:
            # Créer le service API approprié
            self.api_service = create_service(api_type=api_type)
            
            # Authentification
            result = self.api_service.authenticate(email, password)
            
            if result['success']:
                # Vérifier si l'utilisateur est admin
                if self.api_service.is_admin():
                    # Ouvrir le tableau de bord admin
                    self.root.withdraw()  # Cacher la fenêtre de connexion
                    dashboard = AdminDashboard(tk.Toplevel(), self.api_service, self.show_login, api_type)
                else:
                    self.status_var.set("Accès refusé. Vous devez être administrateur.")
            else:
                self.status_var.set(result['message'])
        except Exception as e:
            self.status_var.set(f"Erreur: {str(e)}")
    
    def show_login(self):
        """Affiche à nouveau la fenêtre de connexion"""
        self.root.deiconify()
        self.email_var.set("")
        self.password_var.set("")
        self.status_var.set("")

# Point d'entrée
if __name__ == "__main__":
    root = tk.Tk()
    app = LoginApp(root)
    root.mainloop()