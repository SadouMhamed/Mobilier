<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Propriété #{{ $property->id }}</title>
    <style>
        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 12px;
            line-height: 1.4;
            color: #333;
            margin: 0;
            padding: 20px;
        }
        
        .header {
            text-align: center;
            border-bottom: 2px solid #3B82F6;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }
        
        .header h1 {
            color: #3B82F6;
            font-size: 24px;
            margin: 0;
        }
        
        .header p {
            margin: 5px 0 0 0;
            color: #666;
        }
        
        .status-badge {
            display: inline-block;
            padding: 5px 10px;
            border-radius: 4px;
            font-size: 10px;
            font-weight: bold;
            text-transform: uppercase;
        }
        
        .status-en_attente { background-color: #FEF3C7; color: #92400E; }
        .status-validee { background-color: #D1FAE5; color: #065F46; }
        .status-rejetee { background-color: #FEE2E2; color: #DC2626; }
        
        .type-badge {
            display: inline-block;
            padding: 3px 8px;
            border-radius: 3px;
            font-size: 9px;
            font-weight: bold;
        }
        
        .type-location { background-color: #DBEAFE; color: #1E40AF; }
        .type-vente { background-color: #FED7AA; color: #C2410C; }
        
        .section {
            margin-bottom: 25px;
        }
        
        .section-title {
            font-size: 16px;
            font-weight: bold;
            color: #1F2937;
            border-bottom: 1px solid #E5E7EB;
            padding-bottom: 5px;
            margin-bottom: 15px;
        }
        
        .info-grid {
            display: table;
            width: 100%;
        }
        
        .info-row {
            display: table-row;
        }
        
        .info-label {
            display: table-cell;
            font-weight: bold;
            width: 30%;
            padding: 5px 10px 5px 0;
            vertical-align: top;
        }
        
        .info-value {
            display: table-cell;
            padding: 5px 0;
            vertical-align: top;
        }
        
        .property-box {
            background-color: #EFF6FF;
            border: 1px solid #BFDBFE;
            border-radius: 4px;
            padding: 15px;
            margin: 10px 0;
        }
        
        .description-box {
            background-color: #F9FAFB;
            border: 1px solid #E5E7EB;
            border-radius: 4px;
            padding: 15px;
            margin: 10px 0;
        }
        
        .contact-box {
            background-color: #F0FDF4;
            border: 1px solid #BBF7D0;
            border-radius: 4px;
            padding: 15px;
            margin: 10px 0;
        }
        
        .footer {
            margin-top: 40px;
            padding-top: 20px;
            border-top: 1px solid #E5E7EB;
            text-align: center;
            font-size: 10px;
            color: #6B7280;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
        }
        
        .two-columns {
            width: 100%;
        }
        
        .two-columns td {
            width: 50%;
            vertical-align: top;
            padding-right: 15px;
        }
        
        .two-columns td:last-child {
            padding-right: 0;
            padding-left: 15px;
        }

        .price-large {
            font-size: 24px;
            font-weight: bold;
            color: #059669;
            text-align: center;
            margin: 15px 0;
        }

        .features-list {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .features-list li {
            padding: 3px 0;
            border-bottom: 1px solid #E5E7EB;
        }

        .features-list li:last-child {
            border-bottom: none;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>{{ strtoupper($property->title) }}</h1>
        <p>Propriété #{{ $property->id }} - Ajoutée le {{ $property->created_at->format('d/m/Y') }}</p>
        <p>
            <span class="status-badge status-{{ $property->status }}">
                @switch($property->status)
                    @case('en_attente') En attente @break
                    @case('validee') Validée @break
                    @case('rejetee') Rejetée @break
                    @default {{ ucfirst($property->status) }}
                @endswitch
            </span>
            <span class="type-badge type-{{ $property->type }}">
                {{ ucfirst($property->type) }}
            </span>
        </p>
    </div>

    <!-- Prix et Informations principales -->
    <div class="property-box">
        <div class="price-large">
            {{ number_format($property->price, 0, ',', ' ') }} DZD
            @if($property->type === 'location')
                /mois
            @endif
        </div>
        <div style="text-align: center; margin-top: 10px;">
            <strong>{{ $property->property_type }}</strong> • {{ $property->surface }} m²
            @if($property->rooms)
                • {{ $property->rooms }} pièces
            @endif
        </div>
    </div>

    <div class="section">
        <div class="section-title">Localisation</div>
        <table class="two-columns">
            <tr>
                <td>
                    <div class="info-grid">
                        <div class="info-row">
                            <div class="info-label">Adresse :</div>
                            <div class="info-value">{{ $property->address }}</div>
                        </div>
                        <div class="info-row">
                            <div class="info-label">Ville :</div>
                            <div class="info-value">{{ $property->city }}</div>
                        </div>
                    </div>
                </td>
                <td>
                    <div class="info-grid">
                        <div class="info-row">
                            <div class="info-label">Code postal :</div>
                            <div class="info-value">{{ $property->postal_code }}</div>
                        </div>
                        @if($property->country)
                        <div class="info-row">
                            <div class="info-label">Pays :</div>
                            <div class="info-value">{{ $property->country }}</div>
                        </div>
                        @endif
                    </div>
                </td>
            </tr>
        </table>
    </div>

    <div class="section">
        <div class="section-title">Caractéristiques</div>
        <table class="two-columns">
            <tr>
                <td>
                    <ul class="features-list">
                        <li><strong>Surface :</strong> {{ $property->surface }} m²</li>
                        @if($property->rooms)
                        <li><strong>Nombre de pièces :</strong> {{ $property->rooms }}</li>
                        @endif
                        @if($property->bedrooms)
                        <li><strong>Chambres :</strong> {{ $property->bedrooms }}</li>
                        @endif
                        @if($property->bathrooms)
                        <li><strong>Salles de bains :</strong> {{ $property->bathrooms }}</li>
                        @endif
                    </ul>
                </td>
                <td>
                    <ul class="features-list">
                        @if($property->floor)
                        <li><strong>Étage :</strong> {{ $property->floor }}</li>
                        @endif
                        @if($property->elevator !== null)
                        <li><strong>Ascenseur :</strong> {{ $property->elevator ? 'Oui' : 'Non' }}</li>
                        @endif
                        @if($property->parking !== null)
                        <li><strong>Parking :</strong> {{ $property->parking ? 'Oui' : 'Non' }}</li>
                        @endif
                        @if($property->garden !== null)
                        <li><strong>Jardin :</strong> {{ $property->garden ? 'Oui' : 'Non' }}</li>
                        @endif
                    </ul>
                </td>
            </tr>
        </table>
    </div>

    @if($property->description)
    <div class="section">
        <div class="section-title">Description</div>
        <div class="description-box">
            {{ $property->description }}
        </div>
    </div>
    @endif

    <div class="section">
        <div class="section-title">Contact</div>
        <div class="contact-box">
            <div class="info-grid">
                <div class="info-row">
                    <div class="info-label">Propriétaire :</div>
                    <div class="info-value">{{ $property->user->name }}</div>
                </div>
                <div class="info-row">
                    <div class="info-label">Email :</div>
                    <div class="info-value">{{ $property->user->email }}</div>
                </div>
                @if($property->user->phone)
                <div class="info-row">
                    <div class="info-label">Téléphone :</div>
                    <div class="info-value">{{ $property->user->phone }}</div>
                </div>
                @endif
            </div>
        </div>
    </div>

    @if($property->energy_class || $property->ges_class)
    <div class="section">
        <div class="section-title">Performance Énergétique</div>
        <table class="two-columns">
            <tr>
                <td>
                    @if($property->energy_class)
                    <div class="info-grid">
                        <div class="info-row">
                            <div class="info-label">Classe énergétique :</div>
                            <div class="info-value">{{ $property->energy_class }}</div>
                        </div>
                    </div>
                    @endif
                </td>
                <td>
                    @if($property->ges_class)
                    <div class="info-grid">
                        <div class="info-row">
                            <div class="info-label">Classe GES :</div>
                            <div class="info-value">{{ $property->ges_class }}</div>
                        </div>
                    </div>
                    @endif
                </td>
            </tr>
        </table>
    </div>
    @endif

    <div class="section">
        <div class="section-title">Informations techniques</div>
        <table class="two-columns">
            <tr>
                <td>
                    <div class="info-grid">
                        <div class="info-row">
                            <div class="info-label">Créée le :</div>
                            <div class="info-value">{{ $property->created_at->format('d/m/Y à H:i') }}</div>
                        </div>
                        @if($property->validated_at)
                        <div class="info-row">
                            <div class="info-label">Validée le :</div>
                            <div class="info-value">{{ $property->validated_at->format('d/m/Y à H:i') }}</div>
                        </div>
                        @endif
                    </div>
                </td>
                <td>
                    <div class="info-grid">
                        <div class="info-row">
                            <div class="info-label">Dernière modification :</div>
                            <div class="info-value">{{ $property->updated_at->format('d/m/Y à H:i') }}</div>
                        </div>
                        <div class="info-row">
                            <div class="info-label">Référence :</div>
                            <div class="info-value">#{{ $property->id }}</div>
                        </div>
                    </div>
                </td>
            </tr>
        </table>
    </div>

    @if($property->admin_comment && $property->status === 'rejetee')
    <div class="section">
        <div class="section-title">Commentaire d'administration</div>
        <div style="background-color: #FEE2E2; border: 1px solid #FECACA; border-radius: 4px; padding: 15px; color: #DC2626;">
            {{ $property->admin_comment }}
        </div>
    </div>
    @endif

    <div class="footer">
        <p>Document généré le {{ now()->format('d/m/Y à H:i') }}</p>
        <p>Mobilier - Plateforme de gestion immobilière</p>
        @if($property->status === 'validee')
        <p><strong>Cette propriété est validée et visible publiquement</strong></p>
        @endif
    </div>
</body>
</html> 