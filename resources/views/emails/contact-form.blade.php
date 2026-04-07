<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nouveau message de contact</title>
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
            background: linear-gradient(135deg, #3B82F6, #1D4ED8);
            color: white;
            padding: 20px;
            border-radius: 8px 8px 0 0;
            text-align: center;
        }
        .content {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 0 0 8px 8px;
        }
        .field {
            margin-bottom: 15px;
        }
        .field-label {
            font-weight: bold;
            color: #1D4ED8;
        }
        .field-value {
            background: white;
            padding: 10px;
            border-radius: 4px;
            border-left: 4px solid #3B82F6;
        }
        .footer {
            text-align: center;
            margin-top: 20px;
            color: #666;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Nouveau message de contact</h1>
        <p>Plateforme I.M System - Services Gouvernementaux</p>
    </div>
    
    <div class="content">
        <p>Vous avez reçu un nouveau message de contact via le site web :</p>
        
        <div class="field">
            <div class="field-label">Nom :</div>
            <div class="field-value">{{ $name }}</div>
        </div>
        
        <div class="field">
            <div class="field-label">Email :</div>
            <div class="field-value">{{ $email }}</div>
        </div>
        
        <div class="field">
            <div class="field-label">Sujet :</div>
            <div class="field-value">{{ $subject }}</div>
        </div>
        
        <div class="field">
            <div class="field-label">Message :</div>
            <div class="field-value">{{ $message }}</div>
        </div>
    </div>
    
    <div class="footer">
        <p>Ce message a été envoyé automatiquement depuis le site web I.M System.</p>
        <p>Répondez directement à l'email de l'expéditeur pour le contacter.</p>
    </div>
</body>
</html>





























