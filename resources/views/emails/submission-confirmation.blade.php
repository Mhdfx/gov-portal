<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirmation de Soumission - Plateforme Boiema</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px;
            text-align: center;
            border-radius: 10px 10px 0 0;
        }
        .content {
            background: #f8f9fa;
            padding: 30px;
            border-radius: 0 0 10px 10px;
        }
        .success-icon {
            font-size: 48px;
            color: #28a745;
            margin-bottom: 20px;
        }
        .tracking-number {
            background: #e3f2fd;
            border: 2px solid #2196f3;
            padding: 15px;
            border-radius: 8px;
            text-align: center;
            margin: 20px 0;
        }
        .tracking-number h3 {
            margin: 0;
            color: #1976d2;
        }
        .tracking-number p {
            margin: 5px 0 0 0;
            font-size: 18px;
            font-weight: bold;
            color: #1976d2;
        }
        .info-box {
            background: white;
            border-left: 4px solid #28a745;
            padding: 20px;
            margin: 20px 0;
            border-radius: 0 8px 8px 0;
        }
        .footer {
            text-align: center;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #dee2e6;
            color: #6c757d;
            font-size: 14px;
        }
        .btn {
            display: inline-block;
            background: #007bff;
            color: white;
            padding: 12px 24px;
            text-decoration: none;
            border-radius: 6px;
            margin: 20px 0;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Plateforme Boiema</h1>
        <p>Services Gouvernementaux</p>
    </div>
    
    <div class="content">
        <div style="text-align: center;">
            <div class="success-icon">✓</div>
            <h2>Confirmation de Soumission</h2>
        </div>
        
        <p>Bonjour,</p>
        
        <p>Nous avons bien reçu votre soumission pour le service <strong>{{ ucfirst(str_replace('_', ' ', $submissionType)) }}</strong>.</p>
        
        @if($trackingNumber)
        <div class="tracking-number">
            <h3>Numéro de Suivi</h3>
            <p>{{ $trackingNumber }}</p>
        </div>
        @endif
        
        <div class="info-box">
            <h3>Détails de votre soumission :</h3>
            <ul>
                <li><strong>Type :</strong> {{ ucfirst(str_replace('_', ' ', $submissionType)) }}</li>
                <li><strong>Date de soumission :</strong> {{ $submission->created_at->format('d/m/Y à H:i') }}</li>
                <li><strong>Statut :</strong> En attente de traitement</li>
            </ul>
        </div>
        
        <p>Votre demande est maintenant en cours de traitement par nos équipes. Vous recevrez une notification par email dès que le statut de votre soumission sera mis à jour.</p>
        
        <p>Vous pouvez suivre l'avancement de votre demande en vous connectant à votre espace personnel sur la plateforme Boiema.</p>
        
        <div style="text-align: center;">
            <a href="{{ route('login') }}" class="btn">Accéder à mon espace</a>
        </div>
        
        <p>Si vous avez des questions, n'hésitez pas à nous contacter.</p>
        
        <p>Cordialement,<br>
        L'équipe Boiema</p>
    </div>
    
    <div class="footer">
        <p>Cet email a été envoyé automatiquement, merci de ne pas y répondre.</p>
        <p>© {{ date('Y') }} Plateforme Boiema - Tous droits réservés</p>
    </div>
</body>
</html>






























