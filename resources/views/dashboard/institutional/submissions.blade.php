@extends('layouts.dashboard')

@section('dashboard-name', 'Admin Institutionnel')
@section('dashboard-icon', 'ri-government-line')
@section('page-title', 'Gestion des Soumissions')
@section('profile-route', '#')
@section('settings-route', '#')

@section('sidebar')
<nav class="flex-1 px-2 py-4 space-y-1">
    <a href="{{ route('institutional.dashboard') }}" class="group flex items-center px-3 py-2 text-sm font-medium rounded-lg text-gray-700 hover:bg-gray-100">
        <i class="ri-dashboard-3-line text-xl mr-3"></i>
        Tableau de Bord
    </a>
    
    <a href="{{ route('institutional.submissions') }}" class="group flex items-center px-3 py-2 text-sm font-medium rounded-lg bg-blue-50 text-blue-700">
        <i class="ri-file-list-3-line text-xl mr-3"></i>
        Soumissions
        @if($submissions->where('status', 'pending')->count() > 0)
        <span class="ml-auto bg-yellow-100 text-yellow-700 px-2 py-0.5 text-xs rounded-full">{{ $submissions->where('status', 'pending')->count() }}</span>
        @endif
    </a>
    
    <a href="{{ route('institutional.users') }}" class="group flex items-center px-3 py-2 text-sm font-medium rounded-lg text-gray-700 hover:bg-gray-100">
        <i class="ri-user-line text-xl mr-3"></i>
        Utilisateurs
    </a>
    
    <a href="{{ route('institutional.companies') }}" class="group flex items-center px-3 py-2 text-sm font-medium rounded-lg text-gray-700 hover:bg-gray-100">
        <i class="ri-building-line text-xl mr-3"></i>
        Entreprises
    </a>
    
    <a href="{{ route('institutional.reports') }}" class="group flex items-center px-3 py-2 text-sm font-medium rounded-lg text-gray-700 hover:bg-gray-100">
        <i class="ri-bar-chart-line text-xl mr-3"></i>
        Rapports
    </a>
    
    <a href="{{ route('institutional.notifications') }}" class="group flex items-center px-3 py-2 text-sm font-medium rounded-lg text-gray-700 hover:bg-gray-100">
        <i class="ri-notification-3-line text-xl mr-3"></i>
        Notifications
    </a>
    
    <a href="{{ route('institutional.settings') }}" class="group flex items-center px-3 py-2 text-sm font-medium rounded-lg text-gray-700 hover:bg-gray-100">
        <i class="ri-settings-3-line text-xl mr-3"></i>
        Paramètres
    </a>
</nav>

<div class="px-2 py-4 border-t border-gray-200">
    <a href="{{ route('home') }}" class="group flex items-center px-3 py-2 text-sm font-medium rounded-lg text-gray-700 hover:bg-gray-100">
        <i class="ri-home-line text-xl mr-3"></i>
        Retour au site
    </a>
</div>
@endsection

@section('content')
<div class="px-4 sm:px-6 lg:px-8">
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Gestion des Soumissions</h1>
        <p class="mt-2 text-gray-600">Examinez et traitez les soumissions de votre institution</p>
    </div>

    <!-- Filters -->
    <div class="mb-6 bg-white p-4 rounded-lg shadow">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Statut</label>
                <select class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">Tous les statuts</option>
                    <option value="pending">En Attente</option>
                    <option value="under_review">En Révision</option>
                    <option value="approved">Approuvées</option>
                    <option value="rejected">Rejetées</option>
                </select>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Type</label>
                <select class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">Tous les types</option>
                    <option value="investment">Investissement</option>
                    <option value="project-carrier">Porteur de Projet</option>
                    <option value="idea-carrier">Porteur d'Idée</option>
                    <option value="auto-entrepreneur">Auto-Entrepreneur</option>
                    <option value="indh">INDH</option>
                    <option value="training">Formation</option>
                </select>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Région</label>
                <select class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">Toutes les régions</option>
                    <option value="casablanca">Casablanca</option>
                    <option value="rabat">Rabat</option>
                    <option value="marrakech">Marrakech</option>
                    <option value="fes">Fès</option>
                </select>
            </div>
            
            <div class="flex items-end">
                <button class="w-full bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors">
                    <i class="ri-search-line mr-2"></i>
                    Filtrer
                </button>
            </div>
        </div>
    </div>

    <!-- Submissions Table -->
    <div class="bg-white shadow rounded-lg overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-900">Soumissions ({{ $submissions->count() }})</h3>
        </div>
        
        @if($submissions->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Utilisateur</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Statut</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($submissions as $submission)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                #{{ $submission->id }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                @php
                                    $typeLabels = [
                                        'InvestmentSubmission' => 'Investissement',
                                        'ProjectCarrierSubmission' => 'Porteur de Projet',
                                        'IdeaCarrierSubmission' => 'Porteur d\'Idée',
                                        'AutoEntrepreneurSubmission' => 'Auto-Entrepreneur',
                                        'INDHSubmission' => 'INDH',
                                        'TrainingSubmission' => 'Formation'
                                    ];
                                @endphp
                                {{ $typeLabels[class_basename($submission)] ?? class_basename($submission) }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $submission->user->username ?? 'Utilisateur inconnu' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @php
                                    $statusColors = [
                                        'pending' => 'bg-yellow-100 text-yellow-800',
                                        'approved' => 'bg-green-100 text-green-800',
                                        'rejected' => 'bg-red-100 text-red-800',
                                        'under_review' => 'bg-blue-100 text-blue-800'
                                    ];
                                    $statusLabels = [
                                        'pending' => 'En Attente',
                                        'approved' => 'Approuvée',
                                        'rejected' => 'Rejetée',
                                        'under_review' => 'En Révision'
                                    ];
                                @endphp
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $statusColors[$submission->status] ?? 'bg-gray-100 text-gray-800' }}">
                                    {{ $statusLabels[$submission->status] ?? ucfirst($submission->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $submission->created_at->format('d/m/Y H:i') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex space-x-2">
                                    <a href="{{ route('institutional.submissions.show', [strtolower(str_replace('Submission', '', class_basename($submission))), $submission->id]) }}" 
                                       class="text-blue-600 hover:text-blue-900">
                                        <i class="ri-eye-line"></i>
                                    </a>
                                    <button class="text-green-600 hover:text-green-900" onclick="updateStatus({{ $submission->id }}, 'approved')">
                                        <i class="ri-check-line"></i>
                                    </button>
                                    <button class="text-red-600 hover:text-red-900" onclick="updateStatus({{ $submission->id }}, 'rejected')">
                                        <i class="ri-close-line"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="text-center py-12">
                <i class="ri-file-list-3-line text-gray-400 text-6xl mb-4"></i>
                <h3 class="text-lg font-medium text-gray-900 mb-2">Aucune soumission</h3>
                <p class="text-gray-500">Il n'y a actuellement aucune soumission à traiter.</p>
            </div>
        @endif
    </div>
</div>

<!-- Status Update Modal -->
<div id="statusModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Mettre à jour le statut</h3>
            <form id="statusForm" method="POST">
                @csrf
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Nouveau statut</label>
                    <select name="status" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="pending">En Attente</option>
                        <option value="under_review">En Révision</option>
                        <option value="approved">Approuvée</option>
                        <option value="rejected">Rejetée</option>
                    </select>
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Notes institutionnelles</label>
                    <textarea name="institutional_notes" rows="3" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Ajoutez des notes..."></textarea>
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Prochaines étapes</label>
                    <textarea name="next_steps" rows="3" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Décrivez les prochaines étapes..."></textarea>
                </div>
                <div class="flex justify-end space-x-3">
                    <button type="button" onclick="closeModal()" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400">
                        Annuler
                    </button>
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                        Mettre à jour
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function updateStatus(submissionId, status) {
    const modal = document.getElementById('statusModal');
    const form = document.getElementById('statusForm');
    const statusSelect = form.querySelector('select[name="status"]');
    
    statusSelect.value = status;
    form.action = `/institutional/submissions/${submissionId}/status`;
    modal.classList.remove('hidden');
}

function closeModal() {
    document.getElementById('statusModal').classList.add('hidden');
}
</script>
@endsection





























