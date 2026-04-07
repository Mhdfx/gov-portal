<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mise à Jour du Statut - Plateforme I.M System</title>
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
        .status-icon {
            font-size: 48px;
            margin-bottom: 20px;
        }
        .status-approved { color: #28a745; }
        .status-rejected { color: #dc3545; }
        .status-pending { color: #ffc107; }
        .status-review { color: #17a2b8; }
        
        .status-box {
            background: white;
            border: 2px solid;
            padding: 20px;
            border-radius: 8px;
            text-align: center;
            margin: 20px 0;
        }
        .status-approved { border-color: #28a745; }
        .status-rejected { border-color: #dc3545; }
        .status-pending { border-color: #ffc107; }
        .status-review { border-color: #17a2b8; }
        
        .status-box h3 {
            margin: 0;
            font-size: 24px;
        }
        .status-approved h3 { color: #28a745; }
        .status-rejected h3 { color: #dc3545; }
        .status-pending h3 { color: #ffc107; }
        .status-review h3 { color: #17a2b8; }
        
        .info-box {
            background: white;
            border-left: 4px solid #007bff;
            padding: 20px;
            margin: 20px 0;
            border-radius: 0 8px 8px 0;
        }
        .admin-notes {
            background: #fff3cd;
            border: 1px solid #ffeaa7;
            padding: 15px;
            border-radius: 6px;
            margin: 20px 0;
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
        <h1>Plateforme I.M System</h1>
        <p>Services Gouvernementaux</p>
    </div>
    
    <div class="content">
        <div style="text-align: center;">
            <div class="status-icon status-{{ $newStatus }}">
                @if($newStatus === 'approved')
                    ✓
                @elseif($newStatus === 'rejected')
                    ✗
                @elseif($newStatus === 'pending')
                    ⏳
                @elseif($newStatus === 'in_review')
                    🔍
                @else
                    📋
                @endif
            </div>
            <h2>Mise à Jour du Statut</h2>
        </div>
        
        <p>Bonjour,</p>
        
        <p>Le statut de votre soumission pour le service <strong>{{ ucfirst(str_replace('_', ' ', $submissionType)) }}</strong> a été mis à jour.</p>
        
        <div class="status-box status-{{ $newStatus }}">
            <h3>
                @if($newStatus === 'approved')
                    Approuvé
                @elseif($newStatus === 'rejected')
                    Rejeté
                @elseif($newStatus === 'pending')
                    En Attente
                @elseif($newStatus === 'in_review')
                    En Cours d'Examen
                @else
                    {{ ucfirst($newStatus) }}
                @endif
            </h3>
            <p>Statut précédent : 
                @if($oldStatus === 'approved')
                    Approuvé
                @elseif($oldStatus === 'rejected')
                    Rejeté
                @elseif($oldStatus === 'pending')
                    En Attente
                @elseif($oldStatus === 'in_review')
                    En Cours d'Examen
                @else
                    {{ ucfirst($oldStatus) }}
                @endif
            </p>
        </div>
        
        <div class="info-box">
            <h3>Détails de votre soumission :</h3>
            <ul>
                <li><strong>Type :</strong> {{ ucfirst(str_replace('_', ' ', $submissionType)) }}</li>
                <li><strong>Date de soumission :</strong> {{ $submission->created_at->format('d/m/Y à H:i') }}</li>
                <li><strong>Date de mise à jour :</strong> {{ now()->format('d/m/Y à H:i') }}</li>
            </ul>
        </div>
        
        @if($adminNotes)
        <div class="admin-notes">
            <h4>Commentaires de l'administrateur :</h4>
            <p>{{ $adminNotes }}</p>
        </div>
        @endif
        
        @if($newStatus === 'approved')
        <p><strong>Félicitations !</strong> Votre soumission a été approuvée. Vous devriez recevoir des instructions supplémentaires dans les prochains jours.</p>
        @elseif($newStatus === 'rejected')
        <p>Nous regrettons de vous informer que votre soumission n'a pas pu être approuvée. Veuillez consulter les commentaires ci-dessus pour plus de détails.</p>
        @elseif($newStatus === 'in_review')
        <p>Votre soumission est actuellement en cours d'examen par nos équipes. Nous vous tiendrons informé de toute évolution.</p>
        @else
        <p>Votre soumission est en cours de traitement. Nous vous tiendrons informé de toute évolution.</p>
        @endif
        
        <div style="text-align: center;">
            <a href="{{ route('login') }}" class="btn">Accéder à mon espace</a>
        </div>
        
        <p>Si vous avez des questions, n'hésitez pas à nous contacter.</p>
        
        <p>Cordialement,<br>
        L'équipe I.M System</p>
    </div>
    
    <div class="footer">
        <p>Cet email a été envoyé automatiquement, merci de ne pas y répondre.</p>
        <p>© {{ date('Y') }} Plateforme I.M System - Tous droits réservés</p>
    </div>
</body>
</html>






























