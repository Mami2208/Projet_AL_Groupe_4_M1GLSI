import tkinter as tk
from tkinter import ttk, messagebox, simpledialog
import json

class AdminDashboard:
    """
    Tableau de bord administrateur pour la gestion des utilisateurs
    """
    
    def __init__(self, root, api_service, on_close_callback, api_type="soap"):
        """
        Initialise le tableau de bord administrateur
        
        Args:
            root: Fenêtre Tkinter
            api_service: Service API (SOAP ou REST)
            on_close_callback: Fonction à appeler à la fermeture
            api_type: Type d'API utilisé ("soap" ou "rest")
        """
        self.root = root
        self.api_service = api_service
        self.on_close_callback = on_close_callback
        self.api_type = api_type
        
        self.root.title("Tableau de bord administrateur")
        self.root.geometry("800x600")
        self.root.protocol("WM_DELETE_WINDOW", self.on_close)
        
        # Centrer la fenêtre
        self.center_window()
        
        # Configuration de l'interface
        self.setup_ui()
        
        # Charger les utilisateurs
        self.load_users()
    
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
        
        # Titre avec indication du type d'API
        title_label = ttk.Label(
            main_frame, 
            text=f"Gestion des Utilisateurs (API {self.api_type.upper()})", 
            font=('Arial', 16, 'bold')
        )
        title_label.pack(pady=(0, 20))
        
        # Tableau des utilisateurs
        columns = ('id', 'name', 'email', 'role')
        self.tree = ttk.Treeview(main_frame, columns=columns, show='headings')
        
        # Définir les en-têtes
        self.tree.heading('id', text='ID')
        self.tree.heading('name', text='Nom')
        self.tree.heading('email', text='Email')
        self.tree.heading('role', text='Rôle')
        
        # Définir les largeurs de colonnes
        self.tree.column('id', width=50)
        self.tree.column('name', width=200)
        self.tree.column('email', width=250)
        self.tree.column('role', width=100)
        
        # Scrollbar
        scrollbar = ttk.Scrollbar(main_frame, orient=tk.VERTICAL, command=self.tree.yview)
        self.tree.configure(yscroll=scrollbar.set)
        
        # Placement du tableau et scrollbar
        self.tree.pack(side=tk.LEFT, fill=tk.BOTH, expand=True)
        scrollbar.pack(side=tk.RIGHT, fill=tk.Y)
        
        # Frame pour les boutons
        button_frame = ttk.Frame(main_frame)
        button_frame.pack(pady=20, fill=tk.X)
        
        # Boutons d'action
        ttk.Button(
            button_frame, 
            text="Ajouter un utilisateur", 
            command=self.add_user
        ).pack(side=tk.LEFT, padx=5)
        
        ttk.Button(
            button_frame, 
            text="Modifier l'utilisateur", 
            command=self.edit_user
        ).pack(side=tk.LEFT, padx=5)
        
        ttk.Button(
            button_frame, 
            text="Supprimer l'utilisateur", 
            command=self.delete_user
        ).pack(side=tk.LEFT, padx=5)
        
        ttk.Button(
            button_frame, 
            text="Rafraîchir", 
            command=self.load_users
        ).pack(side=tk.LEFT, padx=5)
        
        ttk.Button(
            button_frame, 
            text="Déconnexion", 
            command=self.on_close
        ).pack(side=tk.RIGHT, padx=5)
        
        # Message d'état
        self.status_var = tk.StringVar()
        ttk.Label(
            main_frame, 
            textvariable=self.status_var,
            foreground="blue"
        ).pack(pady=10)
    
    def load_users(self):
        """Charge la liste des utilisateurs depuis l'API"""
        self.status_var.set("Chargement des utilisateurs...")
        self.root.update()
        
        # Vider le tableau
        for item in self.tree.get_children():
            self.tree.delete(item)
        
        # Récupérer les utilisateurs
        result = self.api_service.list_users()
        
        if result['success']:
            users = result.get('users', [])
            
            for user in users:
                # Adapter le format selon le type d'API
                if isinstance(user, dict):
                    self.tree.insert('', tk.END, values=(
                        user.get('id', ''),
                        user.get('name', ''),
                        user.get('email', ''),
                        user.get('role', '')
                    ))
                else:
                    # Pour les objets SUDS
                    self.tree.insert('', tk.END, values=(
                        getattr(user, 'id', ''),
                        getattr(user, 'name', ''),
                        getattr(user, 'email', ''),
                        getattr(user, 'role', '')
                    ))
            
            self.status_var.set(f"{len(users)} utilisateurs chargés")
        else:
            self.status_var.set(f"Erreur: {result['message']}")
    
    def add_user(self):
        """Ajoute un nouvel utilisateur"""
        # Créer une fenêtre de dialogue
        dialog = tk.Toplevel(self.root)
        dialog.title("Ajouter un utilisateur")
        dialog.geometry("400x250")
        dialog.transient(self.root)
        dialog.grab_set()
        
        # Centrer la fenêtre
        dialog.update_idletasks()
        x = self.root.winfo_x() + (self.root.winfo_width() // 2) - (dialog.winfo_width() // 2)
        y = self.root.winfo_y() + (self.root.winfo_height() // 2) - (dialog.winfo_height() // 2)
        dialog.geometry(f"+{x}+{y}")
        
        # Variables
        name_var = tk.StringVar()
        email_var = tk.StringVar()
        password_var = tk.StringVar()
        role_var = tk.StringVar(value="visiteur")
        
        # Frame principal
        frame = ttk.Frame(dialog, padding=20)
        frame.pack(fill=tk.BOTH, expand=True)
        
        # Champs
        ttk.Label(frame, text="Nom:").grid(row=0, column=0, sticky="w", pady=5)
        ttk.Entry(frame, textvariable=name_var, width=30).grid(row=0, column=1, pady=5)
        
        ttk.Label(frame, text="Email:").grid(row=1, column=0, sticky="w", pady=5)
        ttk.Entry(frame, textvariable=email_var, width=30).grid(row=1, column=1, pady=5)
        
        ttk.Label(frame, text="Mot de passe:").grid(row=2, column=0, sticky="w", pady=5)
        ttk.Entry(frame, textvariable=password_var, show="*", width=30).grid(row=2, column=1, pady=5)
        
        ttk.Label(frame, text="Rôle:").grid(row=3, column=0, sticky="w", pady=5)
        role_frame = ttk.Frame(frame)
        role_frame.grid(row=3, column=1, sticky="w", pady=5)
        
        ttk.Radiobutton(role_frame, text="Visiteur", variable=role_var, value="visiteur").pack(side=tk.LEFT, padx=(0, 10))
        ttk.Radiobutton(role_frame, text="Éditeur", variable=role_var, value="editeur").pack(side=tk.LEFT, padx=(0, 10))
        ttk.Radiobutton(role_frame, text="Admin", variable=role_var, value="admin").pack(side=tk.LEFT)
        
        # Boutons
        button_frame = ttk.Frame(frame)
        button_frame.grid(row=4, column=0, columnspan=2, pady=20)
        
        def save_user():
            name = name_var.get()
            email = email_var.get()
            password = password_var.get()
            role = role_var.get()
            
            if not name or not email or not password:
                messagebox.showerror("Erreur", "Veuillez remplir tous les champs", parent=dialog)
                return
            
            result = self.api_service.add_user(name, email, password, role)
            
            if result['success']:
                messagebox.showinfo("Succès", "Utilisateur ajouté avec succès", parent=dialog)
                dialog.destroy()
                self.load_users()
            else:
                messagebox.showerror("Erreur", result['message'], parent=dialog)
        
        ttk.Button(button_frame, text="Enregistrer", command=save_user).pack(side=tk.LEFT, padx=5)
        ttk.Button(button_frame, text="Annuler", command=dialog.destroy).pack(side=tk.LEFT, padx=5)
    
    def edit_user(self):
        """Modifie un utilisateur existant"""
        # Vérifier si un utilisateur est sélectionné
        selected = self.tree.selection()
        if not selected:
            messagebox.showerror("Erreur", "Veuillez sélectionner un utilisateur")
            return
        
        # Récupérer les données de l'utilisateur sélectionné
        user_id = self.tree.item(selected[0])['values'][0]
        user_name = self.tree.item(selected[0])['values'][1]
        user_email = self.tree.item(selected[0])['values'][2]
        user_role = self.tree.item(selected[0])['values'][3]
        
        # Créer une fenêtre de dialogue
        dialog = tk.Toplevel(self.root)
        dialog.title("Modifier un utilisateur")
        dialog.geometry("400x250")
        dialog.transient(self.root)
        dialog.grab_set()
        
        # Centrer la fenêtre
        dialog.update_idletasks()
        x = self.root.winfo_x() + (self.root.winfo_width() // 2) - (dialog.winfo_width() // 2)
        y = self.root.winfo_y() + (self.root.winfo_height() // 2) - (dialog.winfo_height() // 2)
        dialog.geometry(f"+{x}+{y}")
        
        # Variables
        name_var = tk.StringVar(value=user_name)
        email_var = tk.StringVar(value=user_email)
        password_var = tk.StringVar()
        role_var = tk.StringVar(value=user_role)
        
        # Frame principal
        frame = ttk.Frame(dialog, padding=20)
        frame.pack(fill=tk.BOTH, expand=True)
        
        # Champs
        ttk.Label(frame, text="Nom:").grid(row=0, column=0, sticky="w", pady=5)
        ttk.Entry(frame, textvariable=name_var, width=30).grid(row=0, column=1, pady=5)
        
        ttk.Label(frame, text="Email:").grid(row=1, column=0, sticky="w", pady=5)
        ttk.Entry(frame, textvariable=email_var, width=30).grid(row=1, column=1, pady=5)
        
        ttk.Label(frame, text="Mot de passe:").grid(row=2, column=0, sticky="w", pady=5)
        ttk.Entry(frame, textvariable=password_var, show="*", width=30).grid(row=2, column=1, pady=5)
        ttk.Label(frame, text="(Laisser vide pour ne pas modifier)").grid(row=2, column=2, sticky="w", pady=5)
        
        ttk.Label(frame, text="Rôle:").grid(row=3, column=0, sticky="w", pady=5)
        role_frame = ttk.Frame(frame)
        role_frame.grid(row=3, column=1, sticky="w", pady=5)
        
        ttk.Radiobutton(role_frame, text="Visiteur", variable=role_var, value="visiteur").pack(side=tk.LEFT, padx=(0, 10))
        ttk.Radiobutton(role_frame, text="Éditeur", variable=role_var, value="editeur").pack(side=tk.LEFT, padx=(0, 10))
        ttk.Radiobutton(role_frame, text="Admin", variable=role_var, value="admin").pack(side=tk.LEFT)
        
        # Boutons
        button_frame = ttk.Frame(frame)
        button_frame.grid(row=4, column=0, columnspan=2, pady=20)
        
        def save_user():
            name = name_var.get()
            email = email_var.get()
            password = password_var.get()
            role = role_var.get()
            
            if not name or not email:
                messagebox.showerror("Erreur", "Le nom et l'email sont obligatoires", parent=dialog)
                return
            
            # Préparer les données à mettre à jour
            data = {
                'name': name,
                'email': email,
                'role': role
            }
            
            # Ajouter le mot de passe uniquement s'il est fourni
            if password:
                data['password'] = password
            
            result = self.api_service.update_user(user_id, data)
            
            if result['success']:
                messagebox.showinfo("Succès", "Utilisateur mis à jour avec succès", parent=dialog)
                dialog.destroy()
                self.load_users()
            else:
                messagebox.showerror("Erreur", result['message'], parent=dialog)
        
        ttk.Button(button_frame, text="Enregistrer", command=save_user).pack(side=tk.LEFT, padx=5)
        ttk.Button(button_frame, text="Annuler", command=dialog.destroy).pack(side=tk.LEFT, padx=5)
    
    def delete_user(self):
        """Supprime un utilisateur"""
        # Vérifier si un utilisateur est sélectionné
        selected = self.tree.selection()
        if not selected:
            messagebox.showerror("Erreur", "Veuillez sélectionner un utilisateur")
            return
        
        # Récupérer l'ID de l'utilisateur sélectionné
        user_id = self.tree.item(selected[0])['values'][0]
        user_name = self.tree.item(selected[0])['values'][1]
        
        # Demander confirmation
        if not messagebox.askyesno("Confirmation", f"Voulez-vous vraiment supprimer l'utilisateur {user_name} ?"):
            return
        
        # Supprimer l'utilisateur
        result = self.api_service.delete_user(user_id)
        
        if result['success']:
            messagebox.showinfo("Succès", "Utilisateur supprimé avec succès")
            self.load_users()
        else:
            messagebox.showerror("Erreur", result['message'])
    
    def on_close(self):
        """Ferme le tableau de bord et appelle le callback"""
        self.root.destroy()
        if self.on_close_callback:
            self.on_close_callback() 