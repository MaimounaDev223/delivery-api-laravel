@extends('layouts.app')

@section('content')
<div class="max-w-5xl mx-auto space-y-8">
    
    <!-- En-tête -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h1 class="text-3xl font-extrabold text-white">Espace Administration</h1>
            <p class="text-slate-400 text-sm mt-1">Gérez l'enregistrement des colis et la mise à jour des statuts.</p>
        </div>
    </div>

    <!-- Messages Flash -->
    @if(session('success'))
        <div class="p-4 bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 rounded-xl text-sm flex items-center gap-3">
            <svg class="w-5 h-5 shrink-0 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <!-- Formulaire de création (1 Colonne) -->
        <div class="bg-slate-900/80 border border-slate-800 p-6 rounded-2xl shadow-xl h-fit">
            <h2 class="text-lg font-bold text-white mb-4 flex items-center gap-2">
                <svg class="w-5 h-5 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Nouveau Colis
            </h2>

            <form action="/admin/parcels" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-semibold text-slate-400 mb-1">Expéditeur</label>
                    <input type="text" name="sender_name" required placeholder="Nom de l'expéditeur"
                        class="w-full px-3.5 py-2.5 bg-slate-950/60 border border-slate-800 rounded-xl text-white text-sm focus:outline-none focus:border-indigo-500">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-400 mb-1">Destinataire</label>
                    <input type="text" name="recipient_name" required placeholder="Nom du destinataire"
                        class="w-full px-3.5 py-2.5 bg-slate-950/60 border border-slate-800 rounded-xl text-white text-sm focus:outline-none focus:border-indigo-500">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-400 mb-1">Téléphone</label>
                    <input type="text" name="recipient_phone" required placeholder="+223..."
                        class="w-full px-3.5 py-2.5 bg-slate-950/60 border border-slate-800 rounded-xl text-white text-sm focus:outline-none focus:border-indigo-500">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-400 mb-1">Adresse de destination</label>
                    <input type="text" name="destination_address" required placeholder="Ville, Quartier..."
                        class="w-full px-3.5 py-2.5 bg-slate-950/60 border border-slate-800 rounded-xl text-white text-sm focus:outline-none focus:border-indigo-500">
                </div>

                <button type="submit" class="w-full mt-2 py-3 bg-indigo-600 hover:bg-indigo-500 text-white font-semibold text-sm rounded-xl shadow-lg shadow-indigo-600/30 transition">
                    Créer le colis
                </button>
            </form>
        </div>

        <!-- Liste des colis (2 Colonnes) -->
        <div class="lg:col-span-2 bg-slate-900/80 border border-slate-800 p-6 rounded-2xl shadow-xl space-y-4">
            <h2 class="text-lg font-bold text-white mb-4">Liste des Colis Registrés</h2>

            <div class="space-y-3 max-h-[600px] overflow-y-auto pr-1">
                @forelse($parcels as $p)
                    <div class="p-4 bg-slate-950/50 border border-slate-800/80 rounded-xl flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                        <div>
                            <div class="flex items-center gap-2">
                                <span class="font-mono text-sm font-bold text-indigo-400">{{ $p->tracking_code }}</span>
                                <span class="text-xs text-slate-500">• {{ $p->created_at->diffForHumans() }}</span>
                            </div>
                            <p class="text-xs text-slate-300 mt-1">
                                De <strong class="text-white">{{ $p->sender_name }}</strong> à <strong class="text-white">{{ $p->recipient_name }}</strong> ({{ $p->destination_address }})
                            </p>
                        </div>

                        <!-- Formulaire rapide pour changer de statut -->
                        <form action="/admin/parcels/{{ $p->tracking_code }}/status" method="POST" class="flex items-center gap-2">
                            @csrf
                            @method('PATCH')
                            <select name="status" onchange="this.form.submit()"
                                class="px-3 py-1.5 bg-slate-900 border border-slate-700 rounded-lg text-xs font-medium text-slate-200 focus:outline-none focus:border-indigo-500">
                                <option value="pending" {{ $p->status === 'pending' ? 'selected' : '' }}>En attente</option>
                                <option value="in_transit" {{ $p->status === 'in_transit' ? 'selected' : '' }}>En transit</option>
                                <option value="delivered" {{ $p->status === 'delivered' ? 'selected' : '' }}>Livré</option>
                                <option value="cancelled" {{ $p->status === 'cancelled' ? 'selected' : '' }}>Annulé</option>
                            </select>
                        </form>
                    </div>
                @empty
                    <p class="text-sm text-slate-500 text-center py-8">Aucun colis enregistré pour le moment.</p>
                @endforelse
            </div>
        </div>

    </div>

</div>
@endsection