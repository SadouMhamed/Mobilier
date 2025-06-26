# Système de Rendez-vous pour Annonces Immobilières

## Vue d'ensemble

Le système de rendez-vous d'annonces permet aux clients de demander des visites pour les propriétés publiées, et aux administrateurs de gérer ces demandes efficacement.

## Fonctionnalités Implémentées

### 🏠 **Prise de Rendez-vous depuis les Annonces**

-   **Bouton "Prendre rendez-vous"** directement dans les détails d'annonce
-   **Formulaire modal** avec informations pré-remplies du client connecté
-   **Validation automatique** des créneaux (minimum 2h après maintenant)
-   **Message optionnel** pour questions spécifiques

### 📋 **Gestion Administrative**

-   **Confirmation des rendez-vous** avec modification de date/heure possible
-   **Notes administratives** pour instructions particulières
-   **Finalisation des visites** avec compte-rendu
-   **Annulation** avec motif obligatoire

### 👥 **Interface Client**

-   **Dashboard enrichi** avec sections rendez-vous de visite
-   **Statistiques visuelles** : RDV services vs RDV visites
-   **Prochaines visites** et historique récent
-   **Suivi du statut** en temps réel

## Structure Technique

### 🗄️ **Base de Données**

**Table `property_appointments`** :

```sql
- id (Primary Key)
- property_id (Foreign Key → properties)
- client_id (Foreign Key → users)
- client_name, client_email, client_phone
- requested_date (Date demandée par le client)
- confirmed_date (Date confirmée par l'admin)
- message (Message optionnel du client)
- status (pending, confirmed, cancelled, completed)
- admin_notes (Notes de l'administrateur)
- cancellation_reason (Motif d'annulation)
- completed_at (Date de finalisation)
- timestamps
```

### 🔗 **Relations Eloquent**

```php
// User Model
public function propertyAppointments() → hasMany(PropertyAppointment)

// Property Model
public function propertyAppointments() → hasMany(PropertyAppointment)

// PropertyAppointment Model
public function client() → belongsTo(User)
public function property() → belongsTo(Property)
```

### 🛠️ **Contrôleur PropertyAppointmentController**

```php
- index()      → Liste filtré par rôle
- store()      → Création nouvelle demande
- show()       → Détails avec permissions
- confirm()    → Confirmation admin avec nouvelle date
- cancel()     → Annulation avec motif
- complete()   → Finalisation par admin
- pending()    → Demandes en attente (admin)
- today()      → Visites du jour (admin)
```

## Workflow Utilisateur

### 🔄 **Processus Client**

1. **Navigation** : Parcourir annonces publiques
2. **Sélection** : Cliquer sur une annonce d'intérêt
3. **Demande** : Cliquer "Prendre rendez-vous"
4. **Formulaire** : Remplir date/heure souhaitées + message
5. **Confirmation** : Recevoir notification d'envoi
6. **Suivi** : Voir statut dans dashboard

### ⚙️ **Processus Admin**

1. **Notification** : Nouvelle demande dans dashboard
2. **Évaluation** : Consulter détails et disponibilités
3. **Confirmation** : Valider avec date/heure définitive
4. **Suivi** : Gérer les visites du jour
5. **Finalisation** : Marquer terminé avec compte-rendu

## Interface Utilisateur

### 📱 **Vue Annonce (properties/show.blade.php)**

```blade
<!-- Section Organiser une visite -->
@if($property->status === 'validee' && $property->user_id !== Auth::id())
    <button onclick="openAppointmentModal()">
        📅 Prendre rendez-vous
    </button>
    <button>💬 Contacter le propriétaire</button>
@endif

<!-- Section Propriétaire : Demandes de visite -->
@if($property->user_id === Auth::id())
    <!-- Aperçu des dernières demandes -->
@endif
```

### 🏠 **Dashboard Client**

**Nouvelles sections** :

-   **Statistiques** : RDV Services (🔧) + RDV Visites (🏠)
-   **Prochaines visites** : Statut pending/confirmed
-   **Visites récentes** : Historique completed/cancelled

### 🔧 **Dashboard Admin**

**Nouvelles sections** :

-   **Visites en attente** : Demandes à confirmer
-   **Visites aujourd'hui** : Planning du jour
-   **Liens rapides** : Accès aux vues de gestion

### 📋 **Vue Index (property-appointments/index.blade.php)**

-   **Filtrage automatique** par rôle utilisateur
-   **Affichage optimisé** : Cards avec informations essentielles
-   **Actions contextuelles** : Selon statut et permissions
-   **Pagination** et états vides

## Statuts et Transitions

### 🔄 **Cycle de Vie d'un Rendez-vous**

```
pending → confirmed → completed
   ↓         ↓           ↓
cancelled  cancelled    ✓
```

**États** :

-   `pending` : En attente de validation admin
-   `confirmed` : Confirmé avec date/heure définitive
-   `cancelled` : Annulé (client ou admin)
-   `completed` : Visite terminée

### 🎨 **Codes Couleurs**

-   **Pending** : Jaune (⏳ En attente)
-   **Confirmed** : Bleu (✅ Confirmé)
-   **Cancelled** : Rouge (❌ Annulé)
-   **Completed** : Vert (🏁 Terminé)

## Routes et Permissions

### 🛣️ **Routes Principales**

```php
// Prise de RDV depuis annonce
POST /properties/{property}/appointment

// Gestion des RDV
GET  /property-appointments             → index (client/admin)
GET  /property-appointments/{id}        → show (propriétaire/admin)
PATCH /property-appointments/{id}/confirm   → admin seulement
PATCH /property-appointments/{id}/cancel    → client propriétaire/admin
PATCH /property-appointments/{id}/complete  → admin seulement

// Vues spécialisées admin
GET /property-appointments-pending     → admin seulement
GET /property-appointments-today       → admin seulement
```

### 🔐 **Contrôles de Permissions**

-   **Clients** : Voient uniquement leurs propres rendez-vous
-   **Propriétaires** : Voient demandes pour leurs annonces dans property/show
-   **Admins** : Accès complet + fonctions de gestion

## Intégration Dashboard

### 📊 **Métriques Ajoutées**

**Dashboard Client** :

-   Compteur RDV Visites vs RDV Services
-   Section "Prochaines visites"
-   Section "Visites récentes"

**Dashboard Admin** :

-   Widget "Visites en attente"
-   Widget "Visites aujourd'hui"
-   Séparation RDV Services vs RDV Visites

## Avantages du Système

### ✅ **Pour les Clients**

-   **Simplicité** : Prise de RDV directe depuis l'annonce
-   **Transparence** : Suivi du statut en temps réel
-   **Flexibilité** : Proposition de créneaux personnalisés
-   **Centralisation** : Tout dans le dashboard

### ✅ **Pour les Administrateurs**

-   **Efficacité** : Gestion centralisée des demandes
-   **Organisation** : Planning du jour et en attente
-   **Traçabilité** : Historique et notes détaillées
-   **Contrôle** : Validation et finalisation

### ✅ **Pour les Propriétaires**

-   **Visibilité** : Aperçu des demandes dans l'annonce
-   **Passivité** : Gestion déléguée à l'administration
-   **Information** : Notifications des visites programmées

## Tests et Validation

### 🧪 **Scénarios de Test**

1. **Client** : Prendre RDV → Voir dans dashboard → Suivre statut
2. **Admin** : Recevoir demande → Confirmer → Marquer terminé
3. **Permissions** : Vérifier accès selon rôles
4. **Edge cases** : Annulations, dates passées, etc.

### ✅ **Validation Technique**

-   **Migration** : Table créée avec indexes
-   **Routes** : Toutes enregistrées correctement
-   **Relations** : Modèles Eloquent connectés
-   **UI/UX** : Interface responsive et intuitive

## Conclusion

Le système de rendez-vous d'annonces enrichit considérablement la plateforme Mobilier en :

-   **Facilitant les visites** pour les clients intéressés
-   **Optimisant la gestion** pour les administrateurs
-   **Centralisant l'information** dans les dashboards
-   **Respectant les permissions** et la sécurité

Cette fonctionnalité transforme une simple plateforme d'annonces en un **véritable outil de gestion immobilière** avec suivi des visites et organisation des rendez-vous.

🎉 **Système opérationnel et prêt à l'utilisation !**
