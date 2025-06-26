<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Demande de Service #{{ $serviceRequest->id }}</title>
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
        .status-assignee { background-color: #DBEAFE; color: #1E40AF; }
        .status-en_cours { background-color: #FED7AA; color: #C2410C; }
        .status-terminee { background-color: #D1FAE5; color: #065F46; }
        
        .priority-badge {
            display: inline-block;
            padding: 3px 8px;
            border-radius: 3px;
            font-size: 9px;
            font-weight: bold;
        }
        
        .priority-basse { background-color: #F3F4F6; color: #374151; }
        .priority-normale { background-color: #DBEAFE; color: #1E40AF; }
        .priority-haute { background-color: #FED7AA; color: #C2410C; }
        .priority-urgente { background-color: #FEE2E2; color: #DC2626; }
        
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
        
        .description-box {
            background-color: #F9FAFB;
            border: 1px solid #E5E7EB;
            border-radius: 4px;
            padding: 15px;
            margin: 10px 0;
        }
        
        .technician-box {
            background-color: #EFF6FF;
            border: 1px solid #BFDBFE;
            border-radius: 4px;
            padding: 15px;
            margin: 10px 0;
        }
        
        .notes-box {
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
    </style>
</head>
<body>
    <div class="header">
        <h1>DEMANDE DE SERVICE</h1>
        <p>Demande #{{ $serviceRequest->id }} - {{ $serviceRequest->created_at->format('d/m/Y à H:i') }}</p>
        <p>
            <span class="status-badge status-{{ $serviceRequest->status }}">
                {{ ucfirst(str_replace('_', ' ', $serviceRequest->status)) }}
            </span>
        </p>
    </div>

    <div class="section">
        <div class="section-title">Informations générales</div>
        <table class="two-columns">
            <tr>
                <td>
                    <div class="info-grid">
                        <div class="info-row">
                            <div class="info-label">Client :</div>
                            <div class="info-value">{{ $serviceRequest->client->name }}</div>
                        </div>
                        <div class="info-row">
                            <div class="info-label">Email :</div>
                            <div class="info-value">{{ $serviceRequest->client->email }}</div>
                        </div>
                        <div class="info-row">
                            <div class="info-label">Téléphone :</div>
                            <div class="info-value">{{ $serviceRequest->phone }}</div>
                        </div>
                        <div class="info-row">
                            <div class="info-label">Service :</div>
                            <div class="info-value">{{ $serviceRequest->service->name }}</div>
                        </div>
                    </div>
                </td>
                <td>
                    <div class="info-grid">
                        <div class="info-row">
                            <div class="info-label">Priorité :</div>
                            <div class="info-value">
                                <span class="priority-badge priority-{{ $serviceRequest->priority }}">
                                    {{ ucfirst($serviceRequest->priority) }}
                                </span>
                            </div>
                        </div>
                        <div class="info-row">
                            <div class="info-label">Date préférée :</div>
                            <div class="info-value">
                                @if($serviceRequest->preferred_date)
                                    {{ $serviceRequest->preferred_date->format('d/m/Y à H:i') }}
                                @else
                                    Non spécifiée
                                @endif
                            </div>
                        </div>
                        <div class="info-row">
                            <div class="info-label">Créée le :</div>
                            <div class="info-value">{{ $serviceRequest->created_at->format('d/m/Y à H:i') }}</div>
                        </div>
                    </div>
                </td>
            </tr>
        </table>
    </div>

    <div class="section">
        <div class="section-title">Adresse d'intervention</div>
        <div class="info-grid">
            <div class="info-row">
                <div class="info-label">Adresse :</div>
                <div class="info-value">{{ $serviceRequest->address }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Ville :</div>
                <div class="info-value">{{ $serviceRequest->city }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Code postal :</div>
                <div class="info-value">{{ $serviceRequest->postal_code }}</div>
            </div>
        </div>
    </div>

    <div class="section">
        <div class="section-title">Description de la demande</div>
        <div class="description-box">
            {{ $serviceRequest->description }}
        </div>
    </div>

    @if($serviceRequest->technicien)
    <div class="section">
        <div class="section-title">Technicien assigné</div>
        <div class="technician-box">
            <div class="info-grid">
                <div class="info-row">
                    <div class="info-label">Nom :</div>
                    <div class="info-value">{{ $serviceRequest->technicien->name }}</div>
                </div>
                <div class="info-row">
                    <div class="info-label">Email :</div>
                    <div class="info-value">{{ $serviceRequest->technicien->email }}</div>
                </div>
                @if($serviceRequest->technicien->speciality)
                <div class="info-row">
                    <div class="info-label">Spécialité :</div>
                    <div class="info-value">{{ $serviceRequest->technicien->speciality }}</div>
                </div>
                @endif
                @if($serviceRequest->assigned_at)
                <div class="info-row">
                    <div class="info-label">Assigné le :</div>
                    <div class="info-value">{{ $serviceRequest->assigned_at->format('d/m/Y à H:i') }}</div>
                </div>
                @endif
            </div>
        </div>
    </div>
    @endif

    <div class="section">
        <div class="section-title">Historique</div>
        <div class="info-grid">
            <div class="info-row">
                <div class="info-label">Créée le :</div>
                <div class="info-value">{{ $serviceRequest->created_at->format('d/m/Y à H:i') }}</div>
            </div>
            @if($serviceRequest->assigned_at)
            <div class="info-row">
                <div class="info-label">Assignée le :</div>
                <div class="info-value">{{ $serviceRequest->assigned_at->format('d/m/Y à H:i') }}</div>
            </div>
            @endif
            @if($serviceRequest->started_at)
            <div class="info-row">
                <div class="info-label">Commencée le :</div>
                <div class="info-value">{{ $serviceRequest->started_at->format('d/m/Y à H:i') }}</div>
            </div>
            @endif
            @if($serviceRequest->completed_at)
            <div class="info-row">
                <div class="info-label">Terminée le :</div>
                <div class="info-value">{{ $serviceRequest->completed_at->format('d/m/Y à H:i') }}</div>
            </div>
            @endif
        </div>
    </div>

    @if($serviceRequest->admin_notes)
    <div class="section">
        <div class="section-title">Notes administrateur</div>
        <div style="background-color: #EFF6FF; border: 1px solid #BFDBFE; border-radius: 4px; padding: 15px; margin: 10px 0;">
            {{ $serviceRequest->admin_notes }}
        </div>
    </div>
    @endif

    @if($serviceRequest->technicien_notes)
    <div class="section">
        <div class="section-title">Notes du technicien</div>
        <div style="background-color: #F0FDF4; border: 1px solid #BBF7D0; border-radius: 4px; padding: 15px; margin: 10px 0;">
            {{ $serviceRequest->technicien_notes }}
        </div>
    </div>
    @endif

    @if($serviceRequest->final_notes)
    <div class="section">
        <div class="section-title">Rapport final</div>
        <div style="background-color: #F3F4F6; border: 1px solid #D1D5DB; border-radius: 4px; padding: 15px; margin: 10px 0;">
            {{ $serviceRequest->final_notes }}
        </div>
    </div>
    @endif

    @if($serviceRequest->completion_notes && !$serviceRequest->final_notes)
    <div class="section">
        <div class="section-title">Notes de fin</div>
        <div class="notes-box">
            {{ $serviceRequest->completion_notes }}
        </div>
    </div>
    @endif

    <div class="footer">
        <p>Document généré le {{ now()->format('d/m/Y à H:i') }}</p>
        <p>Plateforme Mobilier - Gestion des Services</p>
    </div>
</body>
</html> 