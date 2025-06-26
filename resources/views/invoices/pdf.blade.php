<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Facture #{{ $invoice->invoice_number }}</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 0; padding: 20px; color: #333; }
        .header { border-bottom: 2px solid #3B82F6; padding-bottom: 20px; margin-bottom: 30px; }
        .company-info { text-align: right; }
        .company-name { font-size: 24px; font-weight: bold; color: #3B82F6; }
        .invoice-title { font-size: 28px; font-weight: bold; margin: 20px 0; }
        .invoice-details { display: flex; justify-content: space-between; margin-bottom: 30px; }
        .client-info, .invoice-info { width: 45%; }
        .table { width: 100%; border-collapse: collapse; margin: 20px 0; }
        .table th, .table td { border: 1px solid #ddd; padding: 12px; text-align: left; }
        .table th { background-color: #f8f9fa; font-weight: bold; }
        .total-row { background-color: #3B82F6; color: white; font-weight: bold; }
        .footer { margin-top: 40px; text-align: center; font-size: 12px; color: #666; }
        .status { 
            display: inline-block; 
            padding: 4px 12px; 
            border-radius: 20px; 
            font-size: 12px; 
            font-weight: bold;
            background-color: #10B981;
            color: white;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="company-info">
            <div class="company-name">MOBILIER SERVICES</div>
            <div>Services de mobilier et rénovation</div>
            <div>Alger, Algérie</div>
            <div>Email: contact@mobilier.dz</div>
        </div>
    </div>

    <div class="invoice-title">
        FACTURE #{{ $invoice->invoice_number }}
        <span class="status">{{ strtoupper($invoice->status) }}</span>
    </div>

    <div class="invoice-details">
        <div class="client-info">
            <h4>FACTURER À :</h4>
            <strong>{{ $invoice->client->name }}</strong><br>
            {{ $invoice->client->email }}<br>
            {{ $invoice->serviceRequest->address }}<br>
            {{ $invoice->serviceRequest->city }} {{ $invoice->serviceRequest->postal_code }}
        </div>
        <div class="invoice-info">
            <h4>DÉTAILS FACTURE :</h4>
            <strong>Date d'émission :</strong> {{ $invoice->created_at->format('d/m/Y') }}<br>
            <strong>Date d'échéance :</strong> {{ $invoice->due_date->format('d/m/Y') }}<br>
            <strong>Service :</strong> {{ $invoice->serviceRequest->service->name }}<br>
            <strong>Technicien :</strong> {{ $invoice->serviceRequest->technicien->name }}
        </div>
    </div>

    <table class="table">
        <thead>
            <tr>
                <th>DESCRIPTION</th>
                <th style="text-align: right;">MONTANT (DZD)</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>Service {{ $invoice->serviceRequest->service->name }}</td>
                <td style="text-align: right;">{{ number_format($invoice->base_amount, 0, ',', ' ') }}</td>
            </tr>
            @if($invoice->additional_amount > 0)
                <tr>
                    <td>Frais supplémentaires</td>
                    <td style="text-align: right;">{{ number_format($invoice->additional_amount, 0, ',', ' ') }}</td>
                </tr>
            @endif
            @if($invoice->discount_amount > 0)
                <tr>
                    <td>Remise</td>
                    <td style="text-align: right; color: #DC2626;">-{{ number_format($invoice->discount_amount, 0, ',', ' ') }}</td>
                </tr>
            @endif
            <tr class="total-row">
                <td><strong>TOTAL À PAYER</strong></td>
                <td style="text-align: right;"><strong>{{ number_format($invoice->total_amount, 0, ',', ' ') }} DZD</strong></td>
            </tr>
        </tbody>
    </table>

    @if($invoice->serviceRequest->taskReports->count() > 0)
        <h4>TRAVAUX EFFECTUÉS :</h4>
        @foreach($invoice->serviceRequest->taskReports as $report)
            <div style="margin-bottom: 15px; border: 1px solid #ddd; padding: 10px; border-radius: 5px;">
                <strong>{{ $report->task_title }}</strong><br>
                <em>{{ $report->task_description }}</em><br>
                Durée : {{ $report->formatted_duration }} | 
                Matériaux : {{ number_format($report->material_cost, 0, ',', ' ') }} DZD | 
                Date : {{ $report->created_at->format('d/m/Y') }}
            </div>
        @endforeach
    @endif

    <div class="footer">
        <p>Merci pour votre confiance !</p>
        <p>Pour toute question concernant cette facture, contactez-nous à contact@mobilier.dz</p>
    </div>
</body>
</html>
