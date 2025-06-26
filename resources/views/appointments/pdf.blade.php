<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rendez-vous #{{ $appointment->id }}</title>
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
        
        .status-planifie { background-color: #FEF3C7; color: #92400E; }
        .status-confirme { background-color: #DBEAFE; color: #1E40AF; }
        .status-termine { background-color: #D1FAE5; color: #065F46; }
        .status-annule { background-color: #FEE2E2; color: #DC2626; }
        
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
        
        .appointment-box {
            background-color: #EFF6FF;
            border: 1px solid #BFDBFE;
            border-radius: 4px;
            padding: 15px;
            margin: 10px 0;
            text-align: center;
        }
        
        .service-box {
            background-color: #F0FDF4;
            border: 1px solid #BBF7D0;
            border-radius: 4px;
            padding: 15px;
            margin: 10px 0;
        }
        
        .notes-box {
            background-color: #FFFBEB;
            border: 1px solid #FCD34D;
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

        .datetime-large {
            font-size: 18px;
            font-weight: bold;
            color: #1F2937;
        }

        .duration-badge {
            background-color: #F3F4F6;
            color: #374151;
            padding: 3px 8px;
            border-radius: 3px;
            font-size: 10px;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>RENDEZ-VOUS</h1>
        <p>RDV #{{ $appointment->id }} - Créé le {{ $appointment->created_at->format('d/m/Y à H:i') }}</p>
        <p>
            <span class="status-badge status-{{ $appointment->status }}">
                @switch($appointment->status)
                    @case('planifie') Planifié @break
                    @case('confirme') Confirmé @break
                    @case('termine') Terminé @break
                    @case('annule') Annulé @break
                    @default {{ ucfirst($appointment->status) }}
                @endswitch
            </span>
        </p>
    </div>

    <!-- Date et Heure du RDV -->
    <div class="appointment-box">
        <div class="datetime-large">
            📅 {{ $appointment->scheduled_at->format('l d F Y') }}
        </div>
        <div class="datetime-large" style="margin-top: 10px;">
            🕐 {{ $appointment->scheduled_at->format('H:i') }}
        </div>
        <div style="margin-top: 10px;">
            <span class="duration-badge">Durée: {{ $appointment->duration }} minutes</span>
        </div>
        @if($appointment->completed_at)
        <div style="margin-top: 10px; color: #065F46;">
            ✅ Terminé le {{ $appointment->completed_at->format('d/m/Y à H:i') }}
        </div>
        @endif
    </div>

    <div class="section">
        <div class="section-title">Informations du Client</div>
        <table class="two-columns">
            <tr>
                <td>
                    <div class="info-grid">
                        <div class="info-row">
                            <div class="info-label">Nom :</div>
                            <div class="info-value">{{ $appointment->client->name }}</div>
                        </div>
                        <div class="info-row">
                            <div class="info-label">Email :</div>
                            <div class="info-value">{{ $appointment->client->email }}</div>
                        </div>
                        @if($appointment->client->phone)
                        <div class="info-row">
                            <div class="info-label">Téléphone :</div>
                            <div class="info-value">{{ $appointment->client->phone }}</div>
                        </div>
                        @endif
                    </div>
                </td>
                <td>
                    @if($appointment->client->address)
                    <div class="info-grid">
                        <div class="info-row">
                            <div class="info-label">Adresse :</div>
                            <div class="info-value">{{ $appointment->client->address }}</div>
                        </div>
                    </div>
                    @endif
                </td>
            </tr>
        </table>
    </div>

    <div class="section">
        <div class="section-title">Service Associé</div>
        <div class="service-box">
            <div class="info-grid">
                <div class="info-row">
                    <div class="info-label">Service :</div>
                    <div class="info-value">{{ $appointment->serviceRequest->service->name }}</div>
                </div>
                <div class="info-row">
                    <div class="info-label">Description :</div>
                    <div class="info-value">{{ $appointment->serviceRequest->description }}</div>
                </div>
                @if($appointment->serviceRequest->technicien)
                <div class="info-row">
                    <div class="info-label">Technicien assigné :</div>
                    <div class="info-value">
                        {{ $appointment->serviceRequest->technicien->name }}
                        @if($appointment->serviceRequest->technicien->email)
                            <br><small>{{ $appointment->serviceRequest->technicien->email }}</small>
                        @endif
                        @if($appointment->serviceRequest->technicien->speciality)
                            <br><small>Spécialité: {{ $appointment->serviceRequest->technicien->speciality }}</small>
                        @endif
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>

    @if($appointment->notes)
    <div class="section">
        <div class="section-title">Notes</div>
        <div class="notes-box">
            {{ $appointment->notes }}
        </div>
    </div>
    @endif

    @if($appointment->cancellation_reason)
    <div class="section">
        <div class="section-title">Raison d'annulation</div>
        <div style="background-color: #FEE2E2; border: 1px solid #FECACA; border-radius: 4px; padding: 15px; color: #DC2626;">
            {{ $appointment->cancellation_reason }}
        </div>
    </div>
    @endif

    <div class="section">
        <div class="section-title">Détails techniques</div>
        <table class="two-columns">
            <tr>
                <td>
                    <div class="info-grid">
                        <div class="info-row">
                            <div class="info-label">Demande de service :</div>
                            <div class="info-value">#{{ $appointment->serviceRequest->id }}</div>
                        </div>
                        <div class="info-row">
                            <div class="info-label">Créé le :</div>
                            <div class="info-value">{{ $appointment->created_at->format('d/m/Y à H:i') }}</div>
                        </div>
                    </div>
                </td>
                <td>
                    <div class="info-grid">
                        <div class="info-row">
                            <div class="info-label">Dernière modification :</div>
                            <div class="info-value">{{ $appointment->updated_at->format('d/m/Y à H:i') }}</div>
                        </div>
                        @if($appointment->serviceRequest->priority)
                        <div class="info-row">
                            <div class="info-label">Priorité :</div>
                            <div class="info-value">{{ ucfirst($appointment->serviceRequest->priority) }}</div>
                        </div>
                        @endif
                    </div>
                </td>
            </tr>
        </table>
    </div>

    <div class="footer">
        <p>Document généré le {{ now()->format('d/m/Y à H:i') }}</p>
        <p>Mobilier - Plateforme de gestion immobilière</p>
    </div>
</body>
</html> 